<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Extension\DataCollector\Proxy;

use Makhan\Component\Form\Extension\DataCollector\FormDataCollectorInterface;
use Makhan\Component\Form\FormTypeInterface;
use Makhan\Component\Form\ResolvedFormTypeFactoryInterface;
use Makhan\Component\Form\ResolvedFormTypeInterface;

/**
 * Proxy that wraps resolved types into {@link ResolvedTypeDataCollectorProxy}
 * instances.
 *
 * @since  2.4
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ResolvedTypeFactoryDataCollectorProxy implements ResolvedFormTypeFactoryInterface
{
    /**
     * @var ResolvedFormTypeFactoryInterface
     */
    private $proxiedFactory;

    /**
     * @var FormDataCollectorInterface
     */
    private $dataCollector;

    public function __construct(ResolvedFormTypeFactoryInterface $proxiedFactory, FormDataCollectorInterface $dataCollector)
    {
        $this->proxiedFactory = $proxiedFactory;
        $this->dataCollector = $dataCollector;
    }

    /**
     * {@inheritdoc}
     */
    public function createResolvedType(FormTypeInterface $type, array $typeExtensions, ResolvedFormTypeInterface $parent = null)
    {
        return new ResolvedTypeDataCollectorProxy(
            $this->proxiedFactory->createResolvedType($type, $typeExtensions, $parent),
            $this->dataCollector
        );
    }
}
