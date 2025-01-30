<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\UserInterface\Request\ArgumentResolver;

use Neuron\BuildingBlocks\UserInterface\Request\ConstraintViolationListTransformer;
use Neuron\BuildingBlocks\UserInterface\Request\RequestInterface;
use Neuron\BuildingBlocks\UserInterface\Request\Validation\RequestValidationException;
use Neuron\BuildingBlocks\UserInterface\Request\Validation\RequestValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

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

        try {
            /** @var RequestInterface $deserializedRequest */
            $deserializedRequest = $this->serializer->deserialize(
                $request->getContent(),
                $argumentType,
                'json',
                [
                    DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true,
                ]
            );

            $this->validator->validate($deserializedRequest);

            return [$deserializedRequest];
        } catch (MissingConstructorArgumentsException $exception) {
            $errors = \array_map(static fn ($argumentName) => [
                'property' => $argumentName,
                'message' => 'This value is required.',
            ], $exception->getMissingConstructorArguments());

            throw new RequestValidationException($errors);
        } catch (PartialDenormalizationException $exception) {
            $constraintViolations = new ConstraintViolationList();

            /** @var NotNormalizableValueException $error */
            foreach ($exception->getErrors() as $error) {
                $message = \sprintf('The type must be one of "%s" ("%s" given).', \implode(', ', $error->getExpectedTypes()), $error->getCurrentType());

                $parameters = [];

                $constraintViolations->add(new ConstraintViolation($message, '', $parameters, null, $error->getPath(), null));
            }

            $errors = ConstraintViolationListTransformer::transformToArray($constraintViolations);

            throw new RequestValidationException($errors);
        }
    }
}
