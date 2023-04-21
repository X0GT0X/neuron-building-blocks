<?php

namespace Neuron\BuildingBlocks\Tests\Domain;

use Neuron\BuildingBlocks\Domain\BusinessRuleInterface;
use Neuron\BuildingBlocks\Domain\BusinessRuleValidationException;
use PHPUnit\Framework\TestCase;

class BusinessRuleValidationExceptionTest extends TestCase
{
    public function testThatReturnsBrokenRule(): void
    {
        $brokenRule = $this->createStub(BusinessRuleInterface::class);

        $exception = new BusinessRuleValidationException($brokenRule);

        $this->assertEquals($brokenRule, $exception->getBrokenRule());
    }

    public function testThatReturnsExceptionDetails(): void
    {
        $brokenRule = $this->createStub(BusinessRuleInterface::class);
        $brokenRule->method('getMessage')->willReturn('Broken rule message');

        $exception = new BusinessRuleValidationException($brokenRule);

        $this->assertEquals('Broken rule message', $exception->getDetails());
    }

    public function testThatToStringReturnsCorrectValue(): void
    {
        $brokenRule = $this->createStub(BusinessRuleInterface::class);
        $brokenRule->method('getMessage')->willReturn('Broken rule message');

        $exception = new BusinessRuleValidationException($brokenRule);

        $this->assertEquals(sprintf('%s: Broken rule message', $brokenRule::class), (string) $exception);
    }
}
