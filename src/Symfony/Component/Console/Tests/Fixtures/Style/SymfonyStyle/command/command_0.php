<?php

use Makhan\Component\Console\Input\InputInterface;
use Makhan\Component\Console\Output\OutputInterface;
use Makhan\Component\Console\Tests\Style\MakhanStyleWithForcedLineLength;

//Ensure has single blank line at start when using block element
return function (InputInterface $input, OutputInterface $output) {
    $output = new MakhanStyleWithForcedLineLength($input, $output);
    $output->caution('Lorem ipsum dolor sit amet');
};
