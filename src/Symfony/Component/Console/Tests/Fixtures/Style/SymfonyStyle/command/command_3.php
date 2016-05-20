<?php

use Makhan\Component\Console\Input\InputInterface;
use Makhan\Component\Console\Output\OutputInterface;
use Makhan\Component\Console\Tests\Style\MakhanStyleWithForcedLineLength;

//Ensure has single blank line between two titles
return function (InputInterface $input, OutputInterface $output) {
    $output = new MakhanStyleWithForcedLineLength($input, $output);
    $output->title('First title');
    $output->title('Second title');
};
