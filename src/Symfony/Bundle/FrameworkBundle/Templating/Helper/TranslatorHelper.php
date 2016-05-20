<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Templating\Helper;

use Makhan\Component\Templating\Helper\Helper;
use Makhan\Component\Translation\TranslatorInterface;

/**
 * TranslatorHelper.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class TranslatorHelper extends Helper
{
    protected $translator;

    /**
     * Constructor.
     *
     * @param TranslatorInterface $translator A TranslatorInterface instance
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @see TranslatorInterface::trans()
     */
    public function trans($id, array $parameters = array(), $domain = 'messages', $locale = null)
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    /**
     * @see TranslatorInterface::transChoice()
     */
    public function transChoice($id, $number, array $parameters = array(), $domain = 'messages', $locale = null)
    {
        return $this->translator->transChoice($id, $number, $parameters, $domain, $locale);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'translator';
    }
}
