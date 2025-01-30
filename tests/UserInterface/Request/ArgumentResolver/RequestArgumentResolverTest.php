<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Tests\UserInterface\Request\ArgumentResolver;

use Neuron\BuildingBlocks\UserInterface\Request\ArgumentResolver\RequestArgumentResolver;
use Neuron\BuildingBlocks\UserInterface\Request\RequestInterface;
use Neuron\BuildingBlocks\UserInterface\Request\Validation\RequestValidationException;
use Neuron\BuildingBlocks\UserInterface\Request\Validation\RequestValidatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\SerializerInterface;

class RequestArgumentResolverTest extends TestCase
{
    private SerializerInterface&MockObject $serializer;

    private RequestValidatorInterface&MockObject $validator;

    private RequestArgumentResolver $resolver;

    public function testThatReturnsEmptyArrayForInvalidArgument(): void
    {
        $request = new Request(content: \json_encode([], \JSON_THROW_ON_ERROR));

        $argumentMetadata = $this->createStub(ArgumentMetadata::class);
        $argumentMetadata->method('getType')->willReturn(\stdClass::class);

        $result = $this->resolver->resolve($request, $argumentMetadata);

        $this->assertCount(0, $result);
    }

    public function testThatSuccessfullyDeserializesAndValidatesRequest(): void
    {
        $request = new Request(content: \json_encode([], \JSON_THROW_ON_ERROR));

        $argumentMetadata = $this->createMock(ArgumentMetadata::class);
        $argumentMetadata->method('getType')->willReturn(MockRequest::class);

        $mockRequest = $this->createStub(MockRequest::class);
        $this->serializer->method('deserialize')->willReturn($mockRequest);

        $this->validator->expects($this->once())->method('validate')->with($mockRequest);

        $result = \iterator_to_array($this->resolver->resolve($request, $argumentMetadata));

        $this->assertCount(1, $result);
        $this->assertSame($mockRequest, $result[0]);
    }

    public function testThatThrowsExceptionOnInvalidRequest(): void
    {
        $request = new Request(content: \json_encode([], \JSON_THROW_ON_ERROR));

        $argumentMetadata = $this->createMock(ArgumentMetadata::class);
        $argumentMetadata->method('getType')->willReturn(MockRequest::class);

        $mockRequest = $this->createStub(MockRequest::class);
        $this->serializer->method('deserialize')->willReturn($mockRequest);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($mockRequest)
            ->willThrowException(new RequestValidationException([[
                'property' => 'someProperty',
                'message' => 'Some validation message',
            ]]));

        $this->expectException(RequestValidationException::class);

        $this->resolver->resolve($request, $argumentMetadata);
    }

    public function testThatThrowsValidationExceptionIfThereAreMissingArguments(): void
    {
        $request = new Request(content: \json_encode([], \JSON_THROW_ON_ERROR));

        $argumentMetadata = $this->createMock(ArgumentMetadata::class);
        $argumentMetadata->method('getType')->willReturn(MockRequest::class);

        $expectedException = new MissingConstructorArgumentsException('Some arguments missing', missingArguments: ['someProperty']);
        $this->serializer->method('deserialize')->willThrowException($expectedException);

        $this->expectException(RequestValidationException::class);

        try {
            $this->resolver->resolve($request, $argumentMetadata);
        } catch (RequestValidationException $exception) {
            $this->assertEquals([[
                'property' => 'someProperty',
                'message' => 'This value is required.',
            ]], $exception->getErrors());

            throw $exception;
        }
    }

    public function testThatThrowsValidationExceptionIfSomeArgumentHasUnsupportedType(): void
    {
        $request = new Request(content: \json_encode([], \JSON_THROW_ON_ERROR));

        $argumentMetadata = $this->createMock(ArgumentMetadata::class);
        $argumentMetadata->method('getType')->willReturn(MockRequest::class);

        $expectedException = new PartialDenormalizationException([], [
            NotNormalizableValueException::createForUnexpectedDataType('Some message', null, ['string'], path: 'someProperty'),
        ]);
        $this->serializer->method('deserialize')->willThrowException($expectedException);

        $this->expectException(RequestValidationException::class);

        try {
            $this->resolver->resolve($request, $argumentMetadata);
        } catch (RequestValidationException $exception) {
            $this->assertEquals([[
                'property' => 'someProperty',
                'message' => 'The type must be one of "string" ("null" given).',
            ]], $exception->getErrors());

            throw $exception;
        }
    }

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->validator = $this->createMock(RequestValidatorInterface::class);
        $this->resolver = new RequestArgumentResolver($this->serializer, $this->validator);
    }
}

class MockRequest implements RequestInterface
{
}
