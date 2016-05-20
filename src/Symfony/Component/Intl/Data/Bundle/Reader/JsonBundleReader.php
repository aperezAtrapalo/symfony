<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Intl\Data\Bundle\Reader;

use Makhan\Component\Intl\Exception\ResourceBundleNotFoundException;
use Makhan\Component\Intl\Exception\RuntimeException;

/**
 * Reads .json resource bundles.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 *
 * @internal
 */
class JsonBundleReader implements BundleReaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function read($path, $locale)
    {
        $fileName = $path.'/'.$locale.'.json';

        if (!file_exists($fileName)) {
            throw new ResourceBundleNotFoundException(sprintf(
                'The resource bundle "%s/%s.json" does not exist.',
                $path,
                $locale
            ));
        }

        if (!is_file($fileName)) {
            throw new RuntimeException(sprintf(
                'The resource bundle "%s/%s.json" is not a file.',
                $path,
                $locale
            ));
        }

        $data = json_decode(file_get_contents($fileName), true);

        if (null === $data) {
            throw new RuntimeException(sprintf(
                'The resource bundle "%s/%s.json" contains invalid JSON: %s',
                $path,
                $locale,
                json_last_error_msg()
            ));
        }

        return $data;
    }
}
