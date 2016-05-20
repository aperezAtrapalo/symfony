<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\SecurityBundle\Templating\Helper;

use Makhan\Component\Security\Acl\Voter\FieldVote;
use Makhan\Component\Templating\Helper\Helper;
use Makhan\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * SecurityHelper provides read-only access to the security checker.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class SecurityHelper extends Helper
{
    private $securityChecker;

    public function __construct(AuthorizationCheckerInterface $securityChecker = null)
    {
        $this->securityChecker = $securityChecker;
    }

    public function isGranted($role, $object = null, $field = null)
    {
        if (null === $this->securityChecker) {
            return false;
        }

        if (null !== $field) {
            $object = new FieldVote($object, $field);
        }

        return $this->securityChecker->isGranted($role, $object);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'security';
    }
}
