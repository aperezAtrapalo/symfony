<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\SecurityBundle\Command;

use Makhan\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Makhan\Component\Console\Input\InputArgument;
use Makhan\Component\Console\Input\InputInterface;
use Makhan\Component\Console\Input\InputOption;
use Makhan\Component\Console\Output\OutputInterface;
use Makhan\Component\Security\Acl\Domain\ObjectIdentity;
use Makhan\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Makhan\Component\Security\Acl\Domain\UserSecurityIdentity;
use Makhan\Component\Security\Acl\Exception\AclAlreadyExistsException;
use Makhan\Component\Security\Acl\Permission\MaskBuilder;
use Makhan\Component\Security\Acl\Model\MutableAclProviderInterface;

/**
 * Sets ACL for objects.
 *
 * @author Kévin Dunglas <kevin@les-tilleuls.coop>
 */
class SetAclCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        if (!$this->getContainer()->has('security.acl.provider')) {
            return false;
        }

        $provider = $this->getContainer()->get('security.acl.provider');
        if (!$provider instanceof MutableAclProviderInterface) {
            return false;
        }

        return parent::isEnabled();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('acl:set')
            ->setDescription('Sets ACL for objects')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command sets ACL.
The ACL system must have been initialized with the <info>init:acl</info> command.

To set <comment>VIEW</comment> and <comment>EDIT</comment> permissions for the user <comment>kevin</comment> on the instance of
<comment>Acme\MyClass</comment> having the identifier <comment>42</comment>:

  <info>php %command.full_name% --user=Makhan/Component/Security/Core/User/User:kevin VIEW EDIT Acme/MyClass:42</info>

Note that you can use <comment>/</comment> instead of <comment>\\ </comment>for the namespace delimiter to avoid any
problem.

To set permissions for a role, use the <info>--role</info> option:

  <info>php %command.full_name% --role=ROLE_USER VIEW Acme/MyClass:1936</info>

To set permissions at the class scope, use the <info>--class-scope</info> option:

  <info>php %command.full_name% --class-scope --user=Makhan/Component/Security/Core/User/User:anne OWNER Acme/MyClass:42</info>
  
EOF
            )
            ->addArgument('arguments', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'A list of permissions and object identities (class name and ID separated by a column)')
            ->addOption('user', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'A list of security identities')
            ->addOption('role', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'A list of roles')
            ->addOption('class-scope', null, InputOption::VALUE_NONE, 'Use class-scope entries')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Parse arguments
        $objectIdentities = array();
        $maskBuilder = $this->getMaskBuilder();
        foreach ($input->getArgument('arguments') as $argument) {
            $data = explode(':', $argument, 2);

            if (count($data) > 1) {
                $objectIdentities[] = new ObjectIdentity($data[1], strtr($data[0], '/', '\\'));
            } else {
                $maskBuilder->add($data[0]);
            }
        }

        // Build permissions mask
        $mask = $maskBuilder->get();

        $userOption = $input->getOption('user');
        $roleOption = $input->getOption('role');
        $classScopeOption = $input->getOption('class-scope');

        if (empty($userOption) && empty($roleOption)) {
            throw new \InvalidArgumentException('A Role or a User must be specified.');
        }

        // Create security identities
        $securityIdentities = array();

        if ($userOption) {
            foreach ($userOption as $user) {
                $data = explode(':', $user, 2);

                if (count($data) === 1) {
                    throw new \InvalidArgumentException('The user must follow the format "Acme/MyUser:username".');
                }

                $securityIdentities[] = new UserSecurityIdentity($data[1], strtr($data[0], '/', '\\'));
            }
        }

        if ($roleOption) {
            foreach ($roleOption as $role) {
                $securityIdentities[] = new RoleSecurityIdentity($role);
            }
        }

        /** @var $container \Makhan\Component\DependencyInjection\ContainerInterface */
        $container = $this->getContainer();
        /** @var $aclProvider MutableAclProviderInterface */
        $aclProvider = $container->get('security.acl.provider');

        // Sets ACL
        foreach ($objectIdentities as $objectIdentity) {
            // Creates a new ACL if it does not already exist
            try {
                $aclProvider->createAcl($objectIdentity);
            } catch (AclAlreadyExistsException $e) {
            }

            $acl = $aclProvider->findAcl($objectIdentity, $securityIdentities);

            foreach ($securityIdentities as $securityIdentity) {
                if ($classScopeOption) {
                    $acl->insertClassAce($securityIdentity, $mask);
                } else {
                    $acl->insertObjectAce($securityIdentity, $mask);
                }
            }

            $aclProvider->updateAcl($acl);
        }
    }

    /**
     * Gets the mask builder.
     *
     * @return MaskBuilder
     */
    protected function getMaskBuilder()
    {
        return new MaskBuilder();
    }
}
