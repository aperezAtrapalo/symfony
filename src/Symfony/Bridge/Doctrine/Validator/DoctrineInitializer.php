<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Doctrine\Validator;

use Doctrine\Common\Persistence\ManagerRegistry;
use Makhan\Component\Validator\ObjectInitializerInterface;

/**
 * Automatically loads proxy object before validation.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class DoctrineInitializer implements ObjectInitializerInterface
{
    protected $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function initialize($object)
    {
        $manager = $this->registry->getManagerForClass(get_class($object));
        if (null !== $manager) {
            $manager->initializeObject($object);
        }
    }
}
