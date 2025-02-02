<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Tests\Domain;

use Neuron\BuildingBlocks\Domain\BaseId;
use PHPUnit\Framework\TestCase;

class BaseIdTest extends TestCase
{
    public function testThatReturnsCorrectUuidValue(): void
    {
        $id = new BaseId('1bc4d5ee-0ddb-4ba2-9815-21b42eebc638');

        $this->assertEquals('1bc4d5ee-0ddb-4ba2-9815-21b42eebc638', (string) $id->getValue());
    }

    public function testThatEqualsReturnsTrueOnIdenticalIds(): void
    {
        $id = new BaseId('1bc4d5ee-0ddb-4ba2-9815-21b42eebc638');
        $otherId = new BaseId('1bc4d5ee-0ddb-4ba2-9815-21b42eebc638');

        $this->assertTrue($id->equals($otherId));
    }
}
