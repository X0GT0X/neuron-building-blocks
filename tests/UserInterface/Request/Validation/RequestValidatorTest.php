<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Tests\UserInterface\Request\Validation;

use Neuron\BuildingBlocks\UserInterface\Request\RequestInterface;
use Neuron\BuildingBlocks\UserInterface\Request\Validation\RequestValidationException;
use Neuron\BuildingBlocks\UserInterface\Request\Validation\RequestValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestValidatorTest extends TestCase
{
    public function testThatDoesNotThrowExceptionWhenNoViolationsFound(): void
    {
        $request = $this->createStub(RequestInterface::class);
        $validator = $this->createMock(ValidatorInterface::class);
        $constraintViolationList = new ConstraintViolationList();

        $validator->expects($this->once())
            ->method('validate')
            ->with($request)
            ->willReturn($constraintViolationList);

        $requestValidator = new RequestValidator($validator);
        $requestValidator->validate($request);
    }

    public function testThatThrowsValidationExceptionWhenViolationsFound(): void
    {
        $request = $this->createStub(RequestInterface::class);
        $validator = $this->createMock(ValidatorInterface::class);

        $violation = $this->createStub(ConstraintViolationInterface::class);
        $violation->method('getPropertyPath')->willReturn('property');
        $violation->method('getMessage')->willReturn('Error message');

        $constraintViolationList = new ConstraintViolationList([$violation]);

        $validator->expects($this->once())
            ->method('validate')
            ->with($request)
            ->willReturn($constraintViolationList);

        $requestValidator = new RequestValidator($validator);

        $this->expectException(RequestValidationException::class);
        $this->expectExceptionMessage('Request validation exception');

        try {
            $requestValidator->validate($request);
        } catch (RequestValidationException $exception) {
            $this->assertEquals([[
                'property' => 'property',
                'message' => 'Error message',
            ]], $exception->getErrors());

            throw $exception;
        }
    }
}
