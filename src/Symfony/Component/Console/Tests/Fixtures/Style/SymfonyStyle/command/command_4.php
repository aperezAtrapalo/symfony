<?php

use Makhan\Component\Console\Input\InputInterface;
use Makhan\Component\Console\Output\OutputInterface;
use Makhan\Component\Console\Tests\Style\MakhanStyleWithForcedLineLength;

//Ensure has single blank line after any text and a title
return function (InputInterface $input, OutputInterface $output) {
    $output = new MakhanStyleWithForcedLineLength($input, $output);

    $output->write('Lorem ipsum dolor sit amet');
    $output->title('First title');

    $output->writeln('Lorem ipsum dolor sit amet');
    $output->title('Second title');

    $output->write('Lorem ipsum dolor sit amet');
    $output->write('');
    $output->title('Third title');

    //Ensure edge case by appending empty strings to history:
    $output->write('Lorem ipsum dolor sit amet');
    $output->write(array('', '', ''));
    $output->title('Fourth title');

    //Ensure have manual control over number of blank lines:
    $output->writeln('Lorem ipsum dolor sit amet');
    $output->writeln(array('', '')); //Should append an extra blank line
    $output->title('Fifth title');

    $output->writeln('Lorem ipsum dolor sit amet');
    $output->newLine(2); //Should append an extra blank line
    $output->title('Fifth title');
};
