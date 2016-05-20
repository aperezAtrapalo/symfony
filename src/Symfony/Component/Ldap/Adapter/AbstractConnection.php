<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Ldap\Adapter;

use Makhan\Component\OptionsResolver\Options;
use Makhan\Component\OptionsResolver\OptionsResolver;

/**
 * @author Charles Sarrazin <charles@sarraz.in>
 */
abstract class AbstractConnection implements ConnectionInterface
{
    protected $config;

    public function __construct(array $config = array())
    {
        $resolver = new OptionsResolver();

        $this->configureOptions($resolver);

        $this->config = $resolver->resolve($config);
    }

    /**
     * Configures the adapter's options.
     *
     * @param OptionsResolver $resolver An OptionsResolver instance
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'host' => 'localhost',
            'version' => 3,
            'connection_string' => null,
            'encryption' => 'none',
            'options' => array(),
        ));

        $resolver->setDefault('port', function (Options $options) {
            return 'ssl' === $options['encryption'] ? 636 : 389;
        });

        $resolver->setDefault('connection_string', function (Options $options) {
            return sprintf('ldap%s://%s:%s', 'ssl' === $options['encryption'] ? 's' : '', $options['host'], $options['port']);
        });

        $resolver->setAllowedTypes('host', 'string');
        $resolver->setAllowedTypes('port', 'numeric');
        $resolver->setAllowedTypes('connection_string', 'string');
        $resolver->setAllowedTypes('version', 'numeric');
        $resolver->setAllowedValues('encryption', array('none', 'ssl', 'tls'));
        $resolver->setAllowedTypes('options', 'array');
    }
}
