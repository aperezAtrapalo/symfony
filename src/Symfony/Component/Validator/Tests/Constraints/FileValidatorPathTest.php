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

use Makhan\Component\Validator\Constraints\File;

class FileValidatorPathTest extends FileValidatorTest
{
    protected function getFile($filename)
    {
        return $filename;
    }

    public function testFileNotFound()
    {
        $constraint = new File(array(
            'notFoundMessage' => 'myMessage',
        ));

        $this->validator->validate('foobar', $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ file }}', '"foobar"')
            ->setCode(File::NOT_FOUND_ERROR)
            ->assertRaised();
    }
}
