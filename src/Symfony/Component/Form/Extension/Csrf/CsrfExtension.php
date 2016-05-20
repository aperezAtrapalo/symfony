<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Extension\Csrf;

use Makhan\Component\Form\AbstractExtension;
use Makhan\Component\Security\Csrf\CsrfTokenManagerInterface;
use Makhan\Component\Translation\TranslatorInterface;

/**
 * This extension protects forms by using a CSRF token.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class CsrfExtension extends AbstractExtension
{
    /**
     * @var CsrfTokenManagerInterface
     */
    private $tokenManager;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var null|string
     */
    private $translationDomain;

    /**
     * Constructor.
     *
     * @param CsrfTokenManagerInterface $tokenManager      The CSRF token manager
     * @param TranslatorInterface       $translator        The translator for translating error messages
     * @param null|string               $translationDomain The translation domain for translating
     */
    public function __construct(CsrfTokenManagerInterface $tokenManager, TranslatorInterface $translator = null, $translationDomain = null)
    {
        $this->tokenManager = $tokenManager;
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadTypeExtensions()
    {
        return array(
            new Type\FormTypeCsrfExtension($this->tokenManager, true, '_token', $this->translator, $this->translationDomain),
        );
    }
}
