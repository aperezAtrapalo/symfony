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
use Makhan\Component\Asset\VersionStrategy\VersionStrategyInterface;

/**
 * Package that adds a base path to asset URLs in addition to a version.
 *
 * In addition to the provided base path, this package also automatically
 * prepends the current request base path if a Context is available to
 * allow a website to be hosted easily under any given path under the Web
 * Server root directory.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class PathPackage extends Package
{
    private $basePath;

    /**
     * @param string                   $basePath        The base path to be prepended to relative paths
     * @param VersionStrategyInterface $versionStrategy The version strategy
     */
    public function __construct($basePath, VersionStrategyInterface $versionStrategy, ContextInterface $context = null)
    {
        parent::__construct($versionStrategy, $context);

        if (!$basePath) {
            $this->basePath = '/';
        } else {
            if ('/' != $basePath[0]) {
                $basePath = '/'.$basePath;
            }

            $this->basePath = rtrim($basePath, '/').'/';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl($path)
    {
        if ($this->isAbsoluteUrl($path)) {
            return $path;
        }

        return $this->getBasePath().ltrim($this->getVersionStrategy()->applyVersion($path), '/');
    }

    /**
     * Returns the base path.
     *
     * @return string The base path
     */
    public function getBasePath()
    {
        return $this->getContext()->getBasePath().$this->basePath;
    }
}
