<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\PhpUnit\TextUI;

/**
 * {@inheritdoc}
 */
class Command extends \PHPUnit_TextUI_Command
{
    /**
     * {@inheritdoc}
     */
    protected function createRunner()
    {
        return new TestRunner($this->arguments['loader']);
    }

    /**
     * {@inheritdoc}
     */
    protected function handleBootstrap($filename)
    {
        parent::handleBootstrap($filename);

        // By default, we want PHPUnit's autoloader before Makhan's one
        if (!getenv('SYMFONY_PHPUNIT_OVERLOAD')) {
            $filename = realpath(stream_resolve_include_path($filename));
            $makhanLoader = realpath(dirname(PHPUNIT_COMPOSER_INSTALL).'/../../../vendor/autoload.php');

            if ($filename === $makhanLoader) {
                $makhanLoader = require $makhanLoader;
                $makhanLoader->unregister();
                $makhanLoader->register(false);
            }
        }
    }
}
