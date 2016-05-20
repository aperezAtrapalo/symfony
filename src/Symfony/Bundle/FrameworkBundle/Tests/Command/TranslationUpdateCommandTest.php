<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Tests\Command;

use Makhan\Component\Console\Application;
use Makhan\Component\Console\Tester\CommandTester;
use Makhan\Bundle\FrameworkBundle\Command\TranslationUpdateCommand;
use Makhan\Component\Filesystem\Filesystem;
use Makhan\Component\DependencyInjection;
use Makhan\Component\HttpKernel;

class TranslationUpdateCommandTest extends \PHPUnit_Framework_TestCase
{
    private $fs;
    private $translationDir;

    public function testDumpMessagesAndClean()
    {
        $tester = $this->createCommandTester($this->getContainer(array('foo' => 'foo')));
        $tester->execute(array('command' => 'translation:update', 'locale' => 'en', 'bundle' => 'foo', '--dump-messages' => true, '--clean' => true));
        $this->assertRegExp('/foo/', $tester->getDisplay());
        $this->assertRegExp('/2 messages were successfully extracted/', $tester->getDisplay());
    }

    public function testWriteMessages()
    {
        $tester = $this->createCommandTester($this->getContainer(array('foo' => 'foo')));
        $tester->execute(array('command' => 'translation:update', 'locale' => 'en', 'bundle' => 'foo', '--force' => true));
        $this->assertRegExp('/Translation files were successfully updated./', $tester->getDisplay());
    }

    protected function setUp()
    {
        $this->fs = new Filesystem();
        $this->translationDir = sys_get_temp_dir().'/'.uniqid('sf2_translation', true);
        $this->fs->mkdir($this->translationDir.'/Resources/translations');
        $this->fs->mkdir($this->translationDir.'/Resources/views');
    }

    protected function tearDown()
    {
        $this->fs->remove($this->translationDir);
    }

    /**
     * @return CommandTester
     */
    private function createCommandTester(DependencyInjection\ContainerInterface $container)
    {
        $command = new TranslationUpdateCommand();
        $command->setContainer($container);

        $application = new Application();
        $application->add($command);

        return new CommandTester($application->find('translation:update'));
    }

    private function getContainer($extractedMessages = array(), $loadedMessages = array(), HttpKernel\KernelInterface $kernel = null)
    {
        $translator = $this->getMockBuilder('Makhan\Component\Translation\Translator')
            ->disableOriginalConstructor()
            ->getMock();

        $translator
            ->expects($this->any())
            ->method('getFallbackLocales')
            ->will($this->returnValue(array('en')));

        $extractor = $this->getMock('Makhan\Component\Translation\Extractor\ExtractorInterface');
        $extractor
            ->expects($this->any())
            ->method('extract')
            ->will(
                $this->returnCallback(function ($path, $catalogue) use ($extractedMessages) {
                  $catalogue->add($extractedMessages);
                })
            );

        $loader = $this->getMock('Makhan\Bundle\FrameworkBundle\Translation\TranslationLoader');
        $loader
            ->expects($this->any())
            ->method('loadMessages')
            ->will(
                $this->returnCallback(function ($path, $catalogue) use ($loadedMessages) {
                  $catalogue->add($loadedMessages);
                })
            );

        $writer = $this->getMock('Makhan\Component\Translation\Writer\TranslationWriter');
        $writer
            ->expects($this->any())
            ->method('getFormats')
            ->will(
                $this->returnValue(array('xlf', 'yml'))
            );

        if (null === $kernel) {
            $kernel = $this->getMock('Makhan\Component\HttpKernel\KernelInterface');
            $kernel
                ->expects($this->any())
                ->method('getBundle')
                ->will($this->returnValueMap(array(
                    array('foo', true, $this->getBundle($this->translationDir)),
                    array('test', true, $this->getBundle('test')),
                )));
        }

        $kernel
            ->expects($this->any())
            ->method('getRootDir')
            ->will($this->returnValue($this->translationDir));

        $container = $this->getMock('Makhan\Component\DependencyInjection\ContainerInterface');
        $container
            ->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap(array(
                array('translation.extractor', 1, $extractor),
                array('translation.loader', 1, $loader),
                array('translation.writer', 1, $writer),
                array('translator', 1, $translator),
                array('kernel', 1, $kernel),
            )));

        return $container;
    }

    private function getBundle($path)
    {
        $bundle = $this->getMock('Makhan\Component\HttpKernel\Bundle\BundleInterface');
        $bundle
            ->expects($this->any())
            ->method('getPath')
            ->will($this->returnValue($path))
        ;

        return $bundle;
    }
}
