<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Command;

use Makhan\Component\Config\Definition\Dumper\YamlReferenceDumper;
use Makhan\Component\Config\Definition\Dumper\XmlReferenceDumper;
use Makhan\Component\Console\Input\InputArgument;
use Makhan\Component\Console\Input\InputOption;
use Makhan\Component\Console\Input\InputInterface;
use Makhan\Component\Console\Output\OutputInterface;
use Makhan\Component\Console\Style\MakhanStyle;

/**
 * A console command for dumping available configuration reference.
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 * @author Wouter J <waldio.webdesign@gmail.com>
 * @author Grégoire Pineau <lyrixx@lyrixx.info>
 */
class ConfigDumpReferenceCommand extends AbstractConfigCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('config:dump-reference')
            ->setDefinition(array(
                new InputArgument('name', InputArgument::OPTIONAL, 'The Bundle name or the extension alias'),
                new InputOption('format', null, InputOption::VALUE_REQUIRED, 'The output format (yaml or xml)', 'yaml'),
            ))
            ->setDescription('Dumps the default configuration for an extension')
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> command dumps the default configuration for an
extension/bundle.

Either the extension alias or bundle name can be used:

  <info>php %command.full_name% framework</info>
  <info>php %command.full_name% FrameworkBundle</info>

With the <info>--format</info> option specifies the format of the configuration,
this is either <comment>yaml</comment> or <comment>xml</comment>.
When the option is not provided, <comment>yaml</comment> is used.

  <info>php %command.full_name% FrameworkBundle --format=xml</info>

EOF
            )
        ;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \LogicException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new MakhanStyle($input, $output);

        if (null === $name = $input->getArgument('name')) {
            $this->listBundles($io);
            $io->comment('Provide the name of a bundle as the first argument of this command to dump its default configuration. (e.g. <comment>config:dump-reference FrameworkBundle</comment>)');

            return;
        }

        $extension = $this->findExtension($name);

        $configuration = $extension->getConfiguration(array(), $this->getContainerBuilder());

        $this->validateConfiguration($extension, $configuration);

        if ($name === $extension->getAlias()) {
            $message = sprintf('Default configuration for extension with alias: "%s"', $name);
        } else {
            $message = sprintf('Default configuration for "%s"', $name);
        }

        switch ($input->getOption('format')) {
            case 'yaml':
                $io->writeln(sprintf('# %s', $message));
                $dumper = new YamlReferenceDumper();
                break;
            case 'xml':
                $io->writeln(sprintf('<!-- %s -->', $message));
                $dumper = new XmlReferenceDumper();
                break;
            default:
                $io->writeln($message);
                throw new \InvalidArgumentException('Only the yaml and xml formats are supported.');
        }

        $io->writeln($dumper->dump($configuration, $extension->getNamespace()));
    }
}
