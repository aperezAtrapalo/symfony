<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\TwigBundle\Command;

use Makhan\Bridge\Twig\Command\LintCommand as BaseLintCommand;
use Makhan\Component\DependencyInjection\ContainerAwareInterface;
use Makhan\Component\DependencyInjection\ContainerAwareTrait;
use Makhan\Component\Finder\Finder;

/**
 * Command that will validate your template syntax and output encountered errors.
 *
 * @author Marc Weistroff <marc.weistroff@sensiolabs.com>
 * @author Jérôme Tamarelle <jerome@tamarelle.net>
 */
final class LintCommand extends BaseLintCommand implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    protected function getTwigEnvironment()
    {
        return $this->container->get('twig');
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setHelp(
                $this->getHelp().<<<'EOF'

Or all template files in a bundle:

  <info>php %command.full_name% @AcmeDemoBundle</info>

EOF
            )
        ;
    }

    protected function findFiles($filename)
    {
        if (0 === strpos($filename, '@')) {
            $dir = $this->getApplication()->getKernel()->locateResource($filename);

            return Finder::create()->files()->in($dir)->name('*.twig');
        }

        return parent::findFiles($filename);
    }
}
