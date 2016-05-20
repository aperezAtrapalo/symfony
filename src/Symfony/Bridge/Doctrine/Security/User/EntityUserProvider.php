<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Doctrine\Security\User;

use Doctrine\Common\Persistence\ManagerRegistry;
use Makhan\Component\Security\Core\Exception\UnsupportedUserException;
use Makhan\Component\Security\Core\Exception\UsernameNotFoundException;
use Makhan\Component\Security\Core\User\UserProviderInterface;
use Makhan\Component\Security\Core\User\UserInterface;

/**
 * Wrapper around a Doctrine ObjectManager.
 *
 * Provides easy to use provisioning for Doctrine entity users.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class EntityUserProvider implements UserProviderInterface
{
    private $registry;
    private $managerName;
    private $classOrAlias;
    private $class;
    private $property;

    public function __construct(ManagerRegistry $registry, $classOrAlias, $property = null, $managerName = null)
    {
        $this->registry = $registry;
        $this->managerName = $managerName;
        $this->classOrAlias = $classOrAlias;
        $this->property = $property;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $repository = $this->getRepository();
        if (null !== $this->property) {
            $user = $repository->findOneBy(array($this->property => $username));
        } else {
            if (!$repository instanceof UserLoaderInterface) {
                throw new \InvalidArgumentException(sprintf('The Doctrine repository "%s" must implement Makhan\Bridge\Doctrine\Security\User\UserLoaderInterface.', get_class($repository)));
            }

            $user = $repository->loadUserByUsername($username);
        }

        if (null === $user) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $class = $this->getClass();
        if (!$user instanceof $class) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $repository = $this->getRepository();
        if ($repository instanceof UserProviderInterface) {
            $refreshedUser = $repository->refreshUser($user);
        } else {
            // The user must be reloaded via the primary key as all other data
            // might have changed without proper persistence in the database.
            // That's the case when the user has been changed by a form with
            // validation errors.
            if (!$id = $this->getClassMetadata()->getIdentifierValues($user)) {
                throw new \InvalidArgumentException('You cannot refresh a user '.
                    'from the EntityUserProvider that does not contain an identifier. '.
                    'The user object has to be serialized with its own identifier '.
                    'mapped by Doctrine.'
                );
            }

            $refreshedUser = $repository->find($id);
            if (null === $refreshedUser) {
                throw new UsernameNotFoundException(sprintf('User with id %s not found', json_encode($id)));
            }
        }

        return $refreshedUser;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $class === $this->getClass() || is_subclass_of($class, $this->getClass());
    }

    private function getObjectManager()
    {
        return $this->registry->getManager($this->managerName);
    }

    private function getRepository()
    {
        return $this->getObjectManager()->getRepository($this->classOrAlias);
    }

    private function getClass()
    {
        if (null === $this->class) {
            $class = $this->classOrAlias;

            if (false !== strpos($class, ':')) {
                $class = $this->getClassMetadata()->getName();
            }

            $this->class = $class;
        }

        return $this->class;
    }

    private function getClassMetadata()
    {
        return $this->getObjectManager()->getClassMetadata($this->classOrAlias);
    }
}
