<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Command;

use Makhan\Component\Console\Input\InputArgument;
use Makhan\Component\Console\Input\InputInterface;
use Makhan\Component\Console\Output\OutputInterface;
use Makhan\Component\Console\Style\MakhanStyle;

/**
 * Shows the status of a process that is running PHP's built-in web server in
 * the background.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class ServerStatusCommand extends ServerCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('address', InputArgument::OPTIONAL, 'Address:port', '127.0.0.1:8000'),
            ))
            ->setName('server:status')
            ->setDescription('Outputs the status of the built-in web server for the given address')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new MakhanStyle($input, $output);
        $address = $input->getArgument('address');

        // remove an orphaned lock file
        if (file_exists($this->getLockFile($address)) && !$this->isServerRunning($address)) {
            unlink($this->getLockFile($address));
        }

        if (file_exists($this->getLockFile($address))) {
            $io->success(sprintf('Web server still listening on http://%s', $address));
        } else {
            $io->warning(sprintf('No web server is listening on http://%s', $address));
        }
    }

    private function isServerRunning($address)
    {
        list($hostname, $port) = explode(':', $address);

        if (false !== $fp = @fsockopen($hostname, $port, $errno, $errstr, 1)) {
            fclose($fp);

            return true;
        }

        return false;
    }
}
