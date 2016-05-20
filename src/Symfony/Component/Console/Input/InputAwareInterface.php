<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Console\Input;

/**
 * InputAwareInterface should be implemented by classes that depends on the
 * Console Input.
 *
 * @author Wouter J <waldio.webdesign@gmail.com>
 */
interface InputAwareInterface
{
    /**
     * Sets the Console Input.
     *
     * @param InputInterface
     */
    public function setInput(InputInterface $input);
}
