<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Templating\Storage;

/**
 * FileStorage represents a template stored on the filesystem.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class FileStorage extends Storage
{
    /**
     * Returns the content of the template.
     *
     * @return string The template content
     */
    public function getContent()
    {
        return file_get_contents($this->template);
    }
}
