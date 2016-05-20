<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Validator\Tests;

use Makhan\Component\Validator\ValidatorBuilder;
use Makhan\Component\Validator\ValidatorBuilderInterface;

class ValidatorBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ValidatorBuilderInterface
     */
    protected $builder;

    protected function setUp()
    {
        $this->builder = new ValidatorBuilder();
    }

    protected function tearDown()
    {
        $this->builder = null;
    }

    public function testAddObjectInitializer()
    {
        $this->assertSame($this->builder, $this->builder->addObjectInitializer(
            $this->getMock('Makhan\Component\Validator\ObjectInitializerInterface')
        ));
    }

    public function testAddObjectInitializers()
    {
        $this->assertSame($this->builder, $this->builder->addObjectInitializers(array()));
    }

    public function testAddXmlMapping()
    {
        $this->assertSame($this->builder, $this->builder->addXmlMapping('mapping'));
    }

    public function testAddXmlMappings()
    {
        $this->assertSame($this->builder, $this->builder->addXmlMappings(array()));
    }

    public function testAddYamlMapping()
    {
        $this->assertSame($this->builder, $this->builder->addYamlMapping('mapping'));
    }

    public function testAddYamlMappings()
    {
        $this->assertSame($this->builder, $this->builder->addYamlMappings(array()));
    }

    public function testAddMethodMapping()
    {
        $this->assertSame($this->builder, $this->builder->addMethodMapping('mapping'));
    }

    public function testAddMethodMappings()
    {
        $this->assertSame($this->builder, $this->builder->addMethodMappings(array()));
    }

    public function testEnableAnnotationMapping()
    {
        $this->assertSame($this->builder, $this->builder->enableAnnotationMapping());
    }

    public function testDisableAnnotationMapping()
    {
        $this->assertSame($this->builder, $this->builder->disableAnnotationMapping());
    }

    public function testSetMetadataCache()
    {
        $this->assertSame($this->builder, $this->builder->setMetadataCache(
            $this->getMock('Makhan\Component\Validator\Mapping\Cache\CacheInterface'))
        );
    }

    public function testSetConstraintValidatorFactory()
    {
        $this->assertSame($this->builder, $this->builder->setConstraintValidatorFactory(
            $this->getMock('Makhan\Component\Validator\ConstraintValidatorFactoryInterface'))
        );
    }

    public function testSetTranslator()
    {
        $this->assertSame($this->builder, $this->builder->setTranslator(
            $this->getMock('Makhan\Component\Translation\TranslatorInterface'))
        );
    }

    public function testSetTranslationDomain()
    {
        $this->assertSame($this->builder, $this->builder->setTranslationDomain('TRANS_DOMAIN'));
    }

    public function testGetValidator()
    {
        $this->assertInstanceOf('Makhan\Component\Validator\Validator\RecursiveValidator', $this->builder->getValidator());
    }
}
