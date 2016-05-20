<?php

use Makhan\Component\Console\Command\Command;
use Makhan\Component\Console\Input\InputInterface;
use Makhan\Component\Console\Output\OutputInterface;

class FooSubnamespaced2Command extends Command
{
    public $input;
    public $output;

    protected function configure()
    {
        $this
            ->setName('foo:go:bret')
            ->setDescription('The foo:bar:go command')
            ->setAliases(array('foobargo'))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }
}
