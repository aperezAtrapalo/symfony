<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Doctrine\Form;

use Doctrine\Common\Persistence\ManagerRegistry;
use Makhan\Bridge\Doctrine\Form\Type\EntityType;
use Makhan\Component\Form\AbstractExtension;
use Makhan\Component\Form\ChoiceList\Factory\CachingFactoryDecorator;
use Makhan\Component\Form\ChoiceList\Factory\ChoiceListFactoryInterface;
use Makhan\Component\Form\ChoiceList\Factory\DefaultChoiceListFactory;
use Makhan\Component\Form\ChoiceList\Factory\PropertyAccessDecorator;
use Makhan\Component\PropertyAccess\PropertyAccess;
use Makhan\Component\PropertyAccess\PropertyAccessorInterface;

class DoctrineOrmExtension extends AbstractExtension
{
    protected $registry;

    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * @var ChoiceListFactoryInterface
     */
    private $choiceListFactory;

    public function __construct(ManagerRegistry $registry, PropertyAccessorInterface $propertyAccessor = null, ChoiceListFactoryInterface $choiceListFactory = null)
    {
        $this->registry = $registry;
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
        $this->choiceListFactory = $choiceListFactory ?: new CachingFactoryDecorator(new PropertyAccessDecorator(new DefaultChoiceListFactory(), $this->propertyAccessor));
    }

    protected function loadTypes()
    {
        return array(
            new EntityType($this->registry, $this->propertyAccessor, $this->choiceListFactory),
        );
    }

    protected function loadTypeGuesser()
    {
        return new DoctrineOrmTypeGuesser($this->registry);
    }
}
