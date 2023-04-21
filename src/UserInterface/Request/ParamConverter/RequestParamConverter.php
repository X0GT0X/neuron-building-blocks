<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\UserInterface\Request\ParamConverter;

use Neuron\BuildingBlocks\UserInterface\Request\RequestInterface;
use Neuron\BuildingBlocks\UserInterface\Request\Validation\RequestValidationException;
use Neuron\BuildingBlocks\UserInterface\Request\Validation\RequestValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

readonly class RequestParamConverter implements ParamConverterInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private RequestValidatorInterface $validator,
    ) {
    }

    /**
     * @throws RequestValidationException
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        /** @var RequestInterface $deserializedRequest */
        $deserializedRequest = $this->serializer->deserialize($request->getContent(), $configuration->getClass(), 'json');

        $this->validator->validate($deserializedRequest);

        $request->attributes->set($configuration->getName(), $deserializedRequest);

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        $reflection = new \ReflectionClass($configuration->getClass());

        return $reflection->isSubclassOf(RequestInterface::class);
    }
}
