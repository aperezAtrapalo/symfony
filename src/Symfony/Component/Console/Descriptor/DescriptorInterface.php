<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Console\Descriptor;

use Makhan\Component\Console\Output\OutputInterface;

/**
 * Descriptor interface.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
interface DescriptorInterface
{
    /**
     * Describes an InputArgument instance.
     *
     * @param OutputInterface $output
     * @param object          $object
     * @param array           $options
     */
    public function describe(OutputInterface $output, $object, array $options = array());
}
