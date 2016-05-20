<?php

use Makhan\Component\Console\Input\InputInterface;
use Makhan\Component\Console\Output\OutputInterface;
use Makhan\Component\Console\Tests\Style\MakhanStyleWithForcedLineLength;

//Ensure questions do not output anything when input is non-interactive
return function (InputInterface $input, OutputInterface $output) {
    $output = new MakhanStyleWithForcedLineLength($input, $output);
    $output->title('Title');
    $output->askHidden('Hidden question');
    $output->choice('Choice question with default', array('choice1', 'choice2'), 'choice1');
    $output->confirm('Confirmation with yes default', true);
    $output->text('Duis aute irure dolor in reprehenderit in voluptate velit esse');
};
