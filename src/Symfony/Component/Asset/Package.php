<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Asset;

use Makhan\Component\Asset\Context\ContextInterface;
use Makhan\Component\Asset\Context\NullContext;
use Makhan\Component\Asset\VersionStrategy\VersionStrategyInterface;

/**
 * Basic package that adds a version to asset URLs.
 *
 * @author Kris Wallsmith <kris@makhan.com>
 * @author Fabien Potencier <fabien@makhan.com>
 */
class Package implements PackageInterface
{
    private $versionStrategy;
    private $context;

    public function __construct(VersionStrategyInterface $versionStrategy, ContextInterface $context = null)
    {
        $this->versionStrategy = $versionStrategy;
        $this->context = $context ?: new NullContext();
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion($path)
    {
        return $this->versionStrategy->getVersion($path);
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl($path)
    {
        if ($this->isAbsoluteUrl($path)) {
            return $path;
        }

        return $this->versionStrategy->applyVersion($path);
    }

    /**
     * @return ContextInterface
     */
    protected function getContext()
    {
        return $this->context;
    }

    /**
     * @return VersionStrategyInterface
     */
    protected function getVersionStrategy()
    {
        return $this->versionStrategy;
    }

    protected function isAbsoluteUrl($url)
    {
        return false !== strpos($url, '://') || '//' === substr($url, 0, 2);
    }
}
