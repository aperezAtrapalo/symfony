<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Translation;

use Makhan\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Makhan\Component\Translation\Translator as BaseTranslator;
use Makhan\Component\Translation\MessageSelector;
use Makhan\Component\DependencyInjection\ContainerInterface;

/**
 * Translator.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class Translator extends BaseTranslator implements WarmableInterface
{
    protected $container;
    protected $loaderIds;

    protected $options = array(
        'cache_dir' => null,
        'debug' => false,
        'resource_files' => array(),
    );

    /**
     * @var array
     */
    private $resourceLocales;

    /**
     * Constructor.
     *
     * Available options:
     *
     *   * cache_dir: The cache directory (or null to disable caching)
     *   * debug:     Whether to enable debugging or not (false by default)
     *   * resource_files: List of translation resources available grouped by locale.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     * @param MessageSelector    $selector  The message selector for pluralization
     * @param array              $loaderIds An array of loader Ids
     * @param array              $options   An array of options
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(ContainerInterface $container, MessageSelector $selector, $loaderIds = array(), array $options = array())
    {
        $this->container = $container;
        $this->loaderIds = $loaderIds;

        // check option names
        if ($diff = array_diff(array_keys($options), array_keys($this->options))) {
            throw new \InvalidArgumentException(sprintf('The Translator does not support the following options: \'%s\'.', implode('\', \'', $diff)));
        }

        $this->options = array_merge($this->options, $options);
        $this->resourceLocales = array_keys($this->options['resource_files']);
        if (null !== $this->options['cache_dir'] && $this->options['debug']) {
            $this->loadResources();
        }

        parent::__construct($container->getParameter('kernel.default_locale'), $selector, $this->options['cache_dir'], $this->options['debug']);
    }

    /**
     * {@inheritdoc}
     */
    public function warmUp($cacheDir)
    {
        // skip warmUp when translator doesn't use cache
        if (null === $this->options['cache_dir']) {
            return;
        }

        $locales = array_merge($this->getFallbackLocales(), array($this->getLocale()), $this->resourceLocales);
        foreach (array_unique($locales) as $locale) {
            // reset catalogue in case it's already loaded during the dump of the other locales.
            if (isset($this->catalogues[$locale])) {
                unset($this->catalogues[$locale]);
            }

            $this->loadCatalogue($locale);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function initializeCatalogue($locale)
    {
        $this->initialize();
        parent::initializeCatalogue($locale);
    }

    protected function initialize()
    {
        $this->loadResources();
        foreach ($this->loaderIds as $id => $aliases) {
            foreach ($aliases as $alias) {
                $this->addLoader($alias, $this->container->get($id));
            }
        }
    }

    private function loadResources()
    {
        foreach ($this->options['resource_files'] as $locale => $files) {
            foreach ($files as $key => $file) {
                // filename is domain.locale.format
                list($domain, $locale, $format) = explode('.', basename($file), 3);
                $this->addResource($format, $file, $locale, $domain);
                unset($this->options['resource_files'][$locale][$key]);
            }
        }
    }
}
