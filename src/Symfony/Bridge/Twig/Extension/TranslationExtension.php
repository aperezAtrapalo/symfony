<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Twig\Extension;

use Makhan\Bridge\Twig\TokenParser\TransTokenParser;
use Makhan\Bridge\Twig\TokenParser\TransChoiceTokenParser;
use Makhan\Bridge\Twig\TokenParser\TransDefaultDomainTokenParser;
use Makhan\Component\Translation\TranslatorInterface;
use Makhan\Bridge\Twig\NodeVisitor\TranslationNodeVisitor;
use Makhan\Bridge\Twig\NodeVisitor\TranslationDefaultDomainNodeVisitor;

/**
 * Provides integration of the Translation component with Twig.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class TranslationExtension extends \Twig_Extension
{
    private $translator;
    private $translationNodeVisitor;

    public function __construct(TranslatorInterface $translator, \Twig_NodeVisitorInterface $translationNodeVisitor = null)
    {
        if (!$translationNodeVisitor) {
            $translationNodeVisitor = new TranslationNodeVisitor();
        }

        $this->translator = $translator;
        $this->translationNodeVisitor = $translationNodeVisitor;
    }

    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('trans', array($this, 'trans')),
            new \Twig_SimpleFilter('transchoice', array($this, 'transchoice')),
        );
    }

    /**
     * Returns the token parser instance to add to the existing list.
     *
     * @return array An array of Twig_TokenParser instances
     */
    public function getTokenParsers()
    {
        return array(
            // {% trans %}Makhan is great!{% endtrans %}
            new TransTokenParser(),

            // {% transchoice count %}
            //     {0} There is no apples|{1} There is one apple|]1,Inf] There is {{ count }} apples
            // {% endtranschoice %}
            new TransChoiceTokenParser(),

            // {% trans_default_domain "foobar" %}
            new TransDefaultDomainTokenParser(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getNodeVisitors()
    {
        return array($this->translationNodeVisitor, new TranslationDefaultDomainNodeVisitor());
    }

    public function getTranslationNodeVisitor()
    {
        return $this->translationNodeVisitor;
    }

    public function trans($message, array $arguments = array(), $domain = null, $locale = null)
    {
        return $this->translator->trans($message, $arguments, $domain, $locale);
    }

    public function transchoice($message, $count, array $arguments = array(), $domain = null, $locale = null)
    {
        return $this->translator->transChoice($message, $count, array_merge(array('%count%' => $count), $arguments), $domain, $locale);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'translator';
    }
}
