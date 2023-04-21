<?php

namespace Neuron\BuildingBlocks\Tests\Domain;

use Neuron\BuildingBlocks\Domain\BaseId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class BaseIdTest extends TestCase
{
    public function testThatReturnsCorrectUuidValue(): void
    {
        $id = new BaseId(Uuid::fromString('1bc4d5ee-0ddb-4ba2-9815-21b42eebc638'));

        $this->assertInstanceOf(Uuid::class, $id->getValue());
        $this->assertEquals('1bc4d5ee-0ddb-4ba2-9815-21b42eebc638', (string) $id->getValue());
    }

    public function testThatEqualsReturnsTrueOnIdenticalIds(): void
    {
        $id = new BaseId(Uuid::fromString('1bc4d5ee-0ddb-4ba2-9815-21b42eebc638'));
        $otherId = new BaseId(Uuid::fromString('1bc4d5ee-0ddb-4ba2-9815-21b42eebc638'));

        $this->assertTrue($id->equals($otherId));
    }
}
