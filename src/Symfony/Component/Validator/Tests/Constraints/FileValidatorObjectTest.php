<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Validator\Tests\Constraints;

use Makhan\Component\HttpFoundation\File\File;

class FileValidatorObjectTest extends FileValidatorTest
{
    protected function getFile($filename)
    {
        return new File($filename);
    }
}
