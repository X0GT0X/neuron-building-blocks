<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Tests\Domain;

use Neuron\BuildingBlocks\Domain\BusinessRuleInterface;
use Neuron\BuildingBlocks\Domain\BusinessRuleValidationException;
use Neuron\BuildingBlocks\Domain\DomainEventInterface;
use Neuron\BuildingBlocks\Domain\Entity;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class EntityTest extends TestCase
{
    private Entity $entity;

    private BusinessRuleInterface $businessRule;

    public function testAddDomainEvent(): void
    {
        $entity = new Entity();
        $domainEvent = $this->createStub(DomainEventInterface::class);
        $domainEvent->method('getId')->willReturn(Uuid::fromString('1bc4d5ee-0ddb-4ba2-9815-21b42eebc638'));
        $domainEvent->method('getOccurredOn')->willReturn(new \DateTimeImmutable('2023-04-16 15:06:11'));

        $this->assertCount(0, $entity->getDomainEvents());

        $entity->addDomainEvent($domainEvent);

        $this->assertCount(1, $entity->getDomainEvents());
        $receivedDomainEvent = $entity->getDomainEvents()[0];
        $this->assertEquals($domainEvent, $receivedDomainEvent);
        $this->assertTrue(Uuid::fromString('1bc4d5ee-0ddb-4ba2-9815-21b42eebc638')->equals($receivedDomainEvent->getId()));
        $this->assertEquals(new \DateTimeImmutable('2023-04-16 15:06:11'), $receivedDomainEvent->getOccurredOn());
    }

    public function testClearDomainEvents(): void
    {
        $entity = new Entity();
        $entity->addDomainEvent($this->createStub(DomainEventInterface::class));

        $this->assertCount(1, $entity->getDomainEvents());

        $entity->clearDomainEvents();

        $this->assertCount(0, $entity->getDomainEvents());
    }

    public function testThatBrokenRuleThrowsBusinessRuleValidationException(): void
    {
        $this->businessRule->expects($this->once())
            ->method('isBroken')
            ->willReturn(true);

        $this->businessRule->expects($this->exactly(2))
            ->method('getMessage')
            ->willReturn('Exception message');

        $this->expectException(BusinessRuleValidationException::class);
        $this->expectExceptionMessage('Exception message');

        $this->entity->doSomethingWithOneRule();
    }

    public function testThatThrowsBusinessRuleValidationExceptionIfOneOfBusinessRulesAreBroken(): void
    {
        $this->businessRule->expects($this->once())
            ->method('isBroken')
            ->willReturn(true);

        $this->businessRule->expects($this->exactly(2))
            ->method('getMessage')
            ->willReturn('Exception message');

        $this->expectException(BusinessRuleValidationException::class);
        $this->expectExceptionMessage('Exception message');

        $this->entity->doSomethingElseWithSeveralRules();
    }

    protected function setUp(): void
    {
        $this->businessRule = $this->createMock(BusinessRuleInterface::class);
        $otherBusinessRule = $this->createMock(BusinessRuleInterface::class);

        $this->entity = new class($this->businessRule, $otherBusinessRule) extends Entity {
            public function __construct(
                private readonly BusinessRuleInterface $businessRule,
                private readonly BusinessRuleInterface $otherBusinessRule
            ) {
            }

            public function doSomethingWithOneRule(): void
            {
                $this->checkRule($this->businessRule);
            }

            public function doSomethingElseWithSeveralRules(): void
            {
                $this->checkRules($this->businessRule, $this->otherBusinessRule);
            }
        };
    }
}
