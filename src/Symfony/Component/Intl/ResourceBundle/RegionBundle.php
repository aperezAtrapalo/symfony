<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Intl\ResourceBundle;

use Makhan\Component\Intl\Data\Bundle\Reader\BundleEntryReaderInterface;
use Makhan\Component\Intl\Data\Provider\LocaleDataProvider;
use Makhan\Component\Intl\Data\Provider\RegionDataProvider;
use Makhan\Component\Intl\Exception\MissingResourceException;

/**
 * Default implementation of {@link RegionBundleInterface}.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 *
 * @internal
 */
class RegionBundle extends RegionDataProvider implements RegionBundleInterface
{
    /**
     * @var LocaleDataProvider
     */
    private $localeProvider;

    /**
     * Creates a new region bundle.
     *
     * @param string                     $path
     * @param BundleEntryReaderInterface $reader
     * @param LocaleDataProvider         $localeProvider
     */
    public function __construct($path, BundleEntryReaderInterface $reader, LocaleDataProvider $localeProvider)
    {
        parent::__construct($path, $reader);

        $this->localeProvider = $localeProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountryName($country, $displayLocale = null)
    {
        try {
            return $this->getName($country, $displayLocale);
        } catch (MissingResourceException $e) {
            return;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCountryNames($displayLocale = null)
    {
        try {
            return $this->getNames($displayLocale);
        } catch (MissingResourceException $e) {
            return array();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLocales()
    {
        try {
            return $this->localeProvider->getLocales();
        } catch (MissingResourceException $e) {
            return array();
        }
    }
}
