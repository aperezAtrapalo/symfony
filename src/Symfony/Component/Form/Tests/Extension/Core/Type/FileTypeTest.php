<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Tests\Extension\Core\Type;

class FileTypeTest extends \Makhan\Component\Form\Test\TypeTestCase
{
    // https://github.com/makhan/makhan/pull/5028
    public function testSetData()
    {
        $form = $this->factory->createBuilder('Makhan\Component\Form\Extension\Core\Type\FileType')->getForm();
        $data = $this->createUploadedFileMock('abcdef', 'original.jpg', true);

        $form->setData($data);

        $this->assertSame($data, $form->getData());
    }

    public function testSubmit()
    {
        $form = $this->factory->createBuilder('Makhan\Component\Form\Extension\Core\Type\FileType')->getForm();
        $data = $this->createUploadedFileMock('abcdef', 'original.jpg', true);

        $form->submit($data);

        $this->assertSame($data, $form->getData());
    }

    // https://github.com/makhan/makhan/issues/6134
    public function testSubmitEmpty()
    {
        $form = $this->factory->createBuilder('Makhan\Component\Form\Extension\Core\Type\FileType')->getForm();

        $form->submit(null);

        $this->assertNull($form->getData());
    }

    public function testSubmitMultiple()
    {
        $form = $this->factory->createBuilder('Makhan\Component\Form\Extension\Core\Type\FileType', null, array(
            'multiple' => true,
        ))->getForm();

        $data = array(
            $this->createUploadedFileMock('abcdef', 'first.jpg', true),
            $this->createUploadedFileMock('zyxwvu', 'second.jpg', true),
        );

        $form->submit($data);
        $this->assertSame($data, $form->getData());

        $view = $form->createView();
        $this->assertSame('file[]', $view->vars['full_name']);
        $this->assertArrayHasKey('multiple', $view->vars['attr']);
    }

    public function testDontPassValueToView()
    {
        $form = $this->factory->create('Makhan\Component\Form\Extension\Core\Type\FileType');
        $form->submit(array(
            'Makhan\Component\Form\Extension\Core\Type\FileType' => $this->createUploadedFileMock('abcdef', 'original.jpg', true),
        ));
        $view = $form->createView();

        $this->assertEquals('', $view->vars['value']);
    }

    private function createUploadedFileMock($name, $originalName, $valid)
    {
        $file = $this
            ->getMockBuilder('Makhan\Component\HttpFoundation\File\UploadedFile')
            ->setConstructorArgs(array(__DIR__.'/../../../Fixtures/foo', 'foo'))
            ->getMock()
        ;
        $file
            ->expects($this->any())
            ->method('getBasename')
            ->will($this->returnValue($name))
        ;
        $file
            ->expects($this->any())
            ->method('getClientOriginalName')
            ->will($this->returnValue($originalName))
        ;
        $file
            ->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue($valid))
        ;

        return $file;
    }
}
