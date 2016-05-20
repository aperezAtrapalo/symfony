<?php

use Makhan\Component\Console\Input\InputInterface;
use Makhan\Component\Console\Output\OutputInterface;
use Makhan\Component\Console\Tests\Style\MakhanStyleWithForcedLineLength;

//Ensure has proper line ending before outputing a text block like with MakhanStyle::listing() or MakhanStyle::text()
return function (InputInterface $input, OutputInterface $output) {
    $output = new MakhanStyleWithForcedLineLength($input, $output);

    $output->writeln('Lorem ipsum dolor sit amet');
    $output->listing(array(
        'Lorem ipsum dolor sit amet',
        'consectetur adipiscing elit',
    ));

    //Even using write:
    $output->write('Lorem ipsum dolor sit amet');
    $output->listing(array(
        'Lorem ipsum dolor sit amet',
        'consectetur adipiscing elit',
    ));

    $output->write('Lorem ipsum dolor sit amet');
    $output->text(array(
        'Lorem ipsum dolor sit amet',
        'consectetur adipiscing elit',
    ));

    $output->newLine();

    $output->write('Lorem ipsum dolor sit amet');
    $output->comment(array(
        'Lorem ipsum dolor sit amet',
        'consectetur adipiscing elit',
    ));
};
