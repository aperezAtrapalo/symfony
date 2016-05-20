<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\PropertyInfo\Tests\Extractors;

use Doctrine\Common\Annotations\AnnotationReader;
use Makhan\Component\PropertyInfo\Extractor\SerializerExtractor;
use Makhan\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Makhan\Component\Serializer\Mapping\Loader\AnnotationLoader;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class SerializerExtractorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SerializerExtractor
     */
    private $extractor;

    protected function setUp()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $this->extractor = new SerializerExtractor($classMetadataFactory);
    }

    public function testGetProperties()
    {
        $this->assertEquals(
            array('collection'),
            $this->extractor->getProperties('Makhan\Component\PropertyInfo\Tests\Fixtures\Dummy', array('serializer_groups' => array('a')))
        );
    }
}
