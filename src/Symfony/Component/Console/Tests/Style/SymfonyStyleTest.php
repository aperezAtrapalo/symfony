<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Console\Tests\Style;

use PHPUnit_Framework_TestCase;
use Makhan\Component\Console\Command\Command;
use Makhan\Component\Console\Input\InputInterface;
use Makhan\Component\Console\Output\OutputInterface;
use Makhan\Component\Console\Style\MakhanStyle;
use Makhan\Component\Console\Tester\CommandTester;

class MakhanStyleTest extends PHPUnit_Framework_TestCase
{
    /** @var Command */
    protected $command;
    /** @var CommandTester */
    protected $tester;

    protected function setUp()
    {
        $this->command = new Command('sfstyle');
        $this->tester = new CommandTester($this->command);
    }

    protected function tearDown()
    {
        $this->command = null;
        $this->tester = null;
    }

    /**
     * @dataProvider inputCommandToOutputFilesProvider
     */
    public function testOutputs($inputCommandFilepath, $outputFilepath)
    {
        $code = require $inputCommandFilepath;
        $this->command->setCode($code);
        $this->tester->execute(array(), array('interactive' => false, 'decorated' => false));
        $this->assertStringEqualsFile($outputFilepath, $this->tester->getDisplay(true));
    }

    public function inputCommandToOutputFilesProvider()
    {
        $baseDir = __DIR__.'/../Fixtures/Style/MakhanStyle';

        return array_map(null, glob($baseDir.'/command/command_*.php'), glob($baseDir.'/output/output_*.txt'));
    }

    public function testLongWordsBlockWrapping()
    {
        $word = 'Lopadotemachoselachogaleokranioleipsanodrimhypotrimmatosilphioparaomelitokatakechymenokichlepikossyphophattoperisteralektryonoptekephalliokigklopeleiolagoiosiraiobaphetraganopterygon';
        $wordLength = strlen($word);
        $maxLineLength = MakhanStyle::MAX_LINE_LENGTH - 3;

        $this->command->setCode(function (InputInterface $input, OutputInterface $output) use ($word) {
            $sfStyle = new MakhanStyleWithForcedLineLength($input, $output);
            $sfStyle->block($word, 'CUSTOM', 'fg=white;bg=blue', ' § ', false);
        });

        $this->tester->execute(array(), array('interactive' => false, 'decorated' => false));
        $expectedCount = (int) ceil($wordLength / ($maxLineLength)) + (int) ($wordLength > $maxLineLength - 5);
        $this->assertSame($expectedCount, substr_count($this->tester->getDisplay(true), ' § '));
    }
}

/**
 * Use this class in tests to force the line length
 * and ensure a consistent output for expectations.
 */
class MakhanStyleWithForcedLineLength extends MakhanStyle
{
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        parent::__construct($input, $output);

        $ref = new \ReflectionProperty(get_parent_class($this), 'lineLength');
        $ref->setAccessible(true);
        $ref->setValue($this, 120);
    }
}
