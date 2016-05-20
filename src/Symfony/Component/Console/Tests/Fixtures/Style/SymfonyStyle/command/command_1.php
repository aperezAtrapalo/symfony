<?php

use Makhan\Component\Console\Input\InputInterface;
use Makhan\Component\Console\Output\OutputInterface;
use Makhan\Component\Console\Tests\Style\MakhanStyleWithForcedLineLength;

//Ensure has single blank line between titles and blocks
return function (InputInterface $input, OutputInterface $output) {
    $output = new MakhanStyleWithForcedLineLength($input, $output);
    $output->title('Title');
    $output->warning('Lorem ipsum dolor sit amet');
    $output->title('Title');
};
