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

use Makhan\Bridge\PhpUnit\MakhanTestsListener;

/**
 * {@inheritdoc}
 */
class TestRunner extends \PHPUnit_TextUI_TestRunner
{
    /**
     * {@inheritdoc}
     */
    protected function handleConfiguration(array &$arguments)
    {
        $arguments['listeners'] = isset($arguments['listeners']) ? $arguments['listeners'] : array();
        $arguments['listeners'][] = new MakhanTestsListener();

        return parent::handleConfiguration($arguments);
    }
}
