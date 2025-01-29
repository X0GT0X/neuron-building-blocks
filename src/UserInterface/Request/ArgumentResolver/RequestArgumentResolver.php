<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\UserInterface\Request\ArgumentResolver;

use Neuron\BuildingBlocks\UserInterface\Request\RequestInterface;
use Neuron\BuildingBlocks\UserInterface\Request\Validation\RequestValidationException;
use Neuron\BuildingBlocks\UserInterface\Request\Validation\RequestValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class RequestArgumentResolver implements ValueResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private RequestValidatorInterface $validator,
    ) {
    }

    /**
     * @throws RequestValidationException
     *
     * @return iterable<RequestInterface>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if (!$argumentType || !\is_subclass_of($argumentType, RequestInterface::class)) {
            return [];
        }

        /** @var RequestInterface $deserializedRequest */
        $deserializedRequest = $this->serializer->deserialize($request->getContent(), $argumentType, 'json');

        $this->validator->validate($deserializedRequest);

        return [$deserializedRequest];
    }
}
