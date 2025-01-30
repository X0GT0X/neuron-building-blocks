<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Tests\UserInterface\Request;

use Neuron\BuildingBlocks\UserInterface\Request\ConstraintViolationListTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class ConstraintViolationListTransformerTest extends TestCase
{
    public function testThatTransformsViolationListToArray(): void
    {
        $violation1 = $this->createMock(ConstraintViolationInterface::class);
        $violation1->method('getPropertyPath')->willReturn('name');
        $violation1->method('getMessage')->willReturn('This value should not be blank.');

        $violation2 = $this->createMock(ConstraintViolationInterface::class);
        $violation2->method('getPropertyPath')->willReturn('sku');
        $violation2->method('getMessage')->willReturn('This field is required.');

        $violationList = new ConstraintViolationList([$violation1, $violation2]);

        $result = ConstraintViolationListTransformer::transformToArray($violationList);

        $this->assertCount(2, $result);

        $this->assertEquals([
            'property' => 'name',
            'message' => 'This value should not be blank.',
        ], $result[0]);

        $this->assertEquals([
            'property' => 'sku',
            'message' => 'This field is required.',
        ], $result[1]);
    }

    public function testThatForEmptyViolationListReturnsEmptyArray(): void
    {
        $violationList = new ConstraintViolationList([]);

        $result = ConstraintViolationListTransformer::transformToArray($violationList);

        $this->assertCount(0, $result);
    }
}
