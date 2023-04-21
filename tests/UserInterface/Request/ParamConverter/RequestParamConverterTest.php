<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Tests\UserInterface\Request\ParamConverter;

use Neuron\BuildingBlocks\UserInterface\Request\ParamConverter\RequestParamConverter;
use Neuron\BuildingBlocks\UserInterface\Request\RequestInterface;
use Neuron\BuildingBlocks\UserInterface\Request\Validation\RequestValidationException;
use Neuron\BuildingBlocks\UserInterface\Request\Validation\RequestValidatorInterface;
use PHPUnit\Framework\TestCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class RequestParamConverterTest extends TestCase
{
    public function testThatSupportsRequestInterface(): void
    {
        $serializer = $this->createStub(SerializerInterface::class);
        $requestValidator = $this->createStub(RequestValidatorInterface::class);
        $request = $this->createStub(RequestInterface::class);

        $paramConverterConfiguration = $this->createStub(ParamConverter::class);
        $paramConverterConfiguration->method('getClass')->willReturn($request::class);

        $paramConverter = new RequestParamConverter($serializer, $requestValidator);

        $this->assertTrue($paramConverter->supports($paramConverterConfiguration));
    }

    public function testThatDoesNotSupportsNothingExceptRequestInterface(): void
    {
        $serializer = $this->createStub(SerializerInterface::class);
        $requestValidator = $this->createStub(RequestValidatorInterface::class);

        $paramConverterConfiguration = $this->createStub(ParamConverter::class);
        $paramConverterConfiguration->method('getClass')->willReturn(\stdClass::class);

        $paramConverter = new RequestParamConverter($serializer, $requestValidator);

        $this->assertFalse($paramConverter->supports($paramConverterConfiguration));
    }

    public function testApply(): void
    {
        $deserializedRequest = $this->createStub(RequestInterface::class);
        $symfonyRequest = $this->createStub(Request::class);
        $symfonyRequest->method('getContent')->willReturn('serialized_content');
        $symfonyRequest->attributes = $this->createMock(ParameterBag::class);
        $symfonyRequest->attributes->expects($this->once())
            ->method('set')
            ->with('RequestName', $deserializedRequest);

        $paramConverterConfiguration = $this->createStub(ParamConverter::class);
        $paramConverterConfiguration->method('getClass')->willReturn('RequestClass');
        $paramConverterConfiguration->method('getName')->willReturn('RequestName');

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects($this->once())
            ->method('deserialize')
            ->with($symfonyRequest->getContent(), $paramConverterConfiguration->getClass(), 'json')
            ->willReturn($deserializedRequest);

        $requestValidator = $this->createMock(RequestValidatorInterface::class);
        $requestValidator->expects($this->once())
            ->method('validate')
            ->with($deserializedRequest);

        $paramConverter = new RequestParamConverter($serializer, $requestValidator);

        $result = $paramConverter->apply($symfonyRequest, $paramConverterConfiguration);

        $this->assertTrue($result);
    }

    public function testThatThrowsRequestValidationExceptionIfRequestIsNotValid(): void
    {
        $deserializedRequest = $this->createStub(RequestInterface::class);
        $symfonyRequest = $this->createStub(Request::class);
        $symfonyRequest->method('getContent')->willReturn('serialized_content');

        $paramConverterConfiguration = $this->createStub(ParamConverter::class);
        $paramConverterConfiguration->method('getClass')->willReturn('RequestClass');

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects($this->once())
            ->method('deserialize')
            ->with($symfonyRequest->getContent(), $paramConverterConfiguration->getClass(), 'json')
            ->willReturn($deserializedRequest);

        $requestValidator = $this->createMock(RequestValidatorInterface::class);
        $requestValidator->expects($this->once())
            ->method('validate')
            ->with($deserializedRequest)
            ->willThrowException(new RequestValidationException([[
                'property' => 'someProperty',
                'message' => 'Some validation message',
            ]]));

        $paramConverter = new RequestParamConverter($serializer, $requestValidator);

        $this->expectException(RequestValidationException::class);

        $paramConverter->apply($symfonyRequest, $paramConverterConfiguration);
    }
}
