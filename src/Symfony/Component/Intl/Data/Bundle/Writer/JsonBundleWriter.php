<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Intl\Data\Bundle\Writer;

/**
 * Writes .json resource bundles.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 *
 * @internal
 */
class JsonBundleWriter implements BundleWriterInterface
{
    /**
     * {@inheritdoc}
     */
    public function write($path, $locale, $data)
    {
        if ($data instanceof \Traversable) {
            $data = iterator_to_array($data);
        }

        array_walk_recursive($data, function (&$value) {
            if ($value instanceof \Traversable) {
                $value = iterator_to_array($value);
            }
        });

        $contents = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n";

        file_put_contents($path.'/'.$locale.'.json', $contents);
    }
}
