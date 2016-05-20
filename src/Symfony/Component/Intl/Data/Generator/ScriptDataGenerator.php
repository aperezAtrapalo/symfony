<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Intl\Data\Generator;

use Makhan\Component\Intl\Data\Bundle\Reader\BundleReaderInterface;
use Makhan\Component\Intl\Data\Bundle\Compiler\GenrbCompiler;
use Makhan\Component\Intl\Data\Util\LocaleScanner;

/**
 * The rule for compiling the script bundle.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 *
 * @internal
 */
class ScriptDataGenerator extends AbstractDataGenerator
{
    /**
     * Collects all available language codes.
     *
     * @var string[]
     */
    private $scriptCodes = array();

    /**
     * {@inheritdoc}
     */
    protected function scanLocales(LocaleScanner $scanner, $sourceDir)
    {
        return $scanner->scanLocales($sourceDir.'/lang');
    }

    /**
     * {@inheritdoc}
     */
    protected function compileTemporaryBundles(GenrbCompiler $compiler, $sourceDir, $tempDir)
    {
        $compiler->compile($sourceDir.'/lang', $tempDir);
    }

    /**
     * {@inheritdoc}
     */
    protected function preGenerate()
    {
        $this->scriptCodes = array();
    }

    /**
     * {@inheritdoc}
     */
    protected function generateDataForLocale(BundleReaderInterface $reader, $tempDir, $displayLocale)
    {
        $localeBundle = $reader->read($tempDir, $displayLocale);

        // isset() on \ResourceBundle returns true even if the value is null
        if (isset($localeBundle['Scripts']) && null !== $localeBundle['Scripts']) {
            $data = array(
                'Version' => $localeBundle['Version'],
                'Names' => iterator_to_array($localeBundle['Scripts']),
            );

            $this->scriptCodes = array_merge($this->scriptCodes, array_keys($data['Names']));

            return $data;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function generateDataForRoot(BundleReaderInterface $reader, $tempDir)
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function generateDataForMeta(BundleReaderInterface $reader, $tempDir)
    {
        $rootBundle = $reader->read($tempDir, 'root');

        $this->scriptCodes = array_unique($this->scriptCodes);

        sort($this->scriptCodes);

        return array(
            'Version' => $rootBundle['Version'],
            'Scripts' => $this->scriptCodes,
        );
    }
}
