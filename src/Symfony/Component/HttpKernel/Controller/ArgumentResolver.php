<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\Controller;

use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpKernel\Controller\ArgumentResolver\DefaultValueResolver;
use Makhan\Component\HttpKernel\Controller\ArgumentResolver\RequestAttributeValueResolver;
use Makhan\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;
use Makhan\Component\HttpKernel\Controller\ArgumentResolver\VariadicValueResolver;
use Makhan\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactory;
use Makhan\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactoryInterface;

/**
 * Responsible for resolving the arguments passed to an action.
 *
 * @author Iltar van der Berg <kjarli@gmail.com>
 */
final class ArgumentResolver implements ArgumentResolverInterface
{
    private $argumentMetadataFactory;

    /**
     * @var ArgumentValueResolverInterface[]
     */
    private $argumentValueResolvers;

    public function __construct(ArgumentMetadataFactoryInterface $argumentMetadataFactory = null, array $argumentValueResolvers = array())
    {
        $this->argumentMetadataFactory = $argumentMetadataFactory ?: new ArgumentMetadataFactory();
        $this->argumentValueResolvers = $argumentValueResolvers ?: array(
            new RequestAttributeValueResolver(),
            new RequestValueResolver(),
            new DefaultValueResolver(),
            new VariadicValueResolver(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getArguments(Request $request, $controller)
    {
        $arguments = array();

        foreach ($this->argumentMetadataFactory->createArgumentMetadata($controller) as $metadata) {
            foreach ($this->argumentValueResolvers as $resolver) {
                if (!$resolver->supports($request, $metadata)) {
                    continue;
                }

                $resolved = $resolver->resolve($request, $metadata);

                if (!$resolved instanceof \Generator) {
                    throw new \InvalidArgumentException(sprintf('%s::resolve() must yield at least one value.', get_class($resolver)));
                }

                foreach ($resolved as $append) {
                    $arguments[] = $append;
                }

                // continue to the next controller argument
                continue 2;
            }

            $representative = $controller;

            if (is_array($representative)) {
                $representative = sprintf('%s::%s()', get_class($representative[0]), $representative[1]);
            } elseif (is_object($representative)) {
                $representative = get_class($representative);
            }

            throw new \RuntimeException(sprintf('Controller "%s" requires that you provide a value for the "$%s" argument (because there is no default value or because there is a non optional argument after this one).', $representative, $metadata->getName()));
        }

        return $arguments;
    }
}
