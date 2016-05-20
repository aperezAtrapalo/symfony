<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Core\Authorization;

use Makhan\Component\ExpressionLanguage\ExpressionFunction;
use Makhan\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

/**
 * Define some ExpressionLanguage functions.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class ExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions()
    {
        return array(
            new ExpressionFunction('is_anonymous', function () {
                return '$trust_resolver->isAnonymous($token)';
            }, function (array $variables) {
                return $variables['trust_resolver']->isAnonymous($variables['token']);
            }),

            new ExpressionFunction('is_authenticated', function () {
                return '$token && !$trust_resolver->isAnonymous($token)';
            }, function (array $variables) {
                return $variables['token'] && !$variables['trust_resolver']->isAnonymous($variables['token']);
            }),

            new ExpressionFunction('is_fully_authenticated', function () {
                return '$trust_resolver->isFullFledged($token)';
            }, function (array $variables) {
                return $variables['trust_resolver']->isFullFledged($variables['token']);
            }),

            new ExpressionFunction('is_remember_me', function () {
                return '$trust_resolver->isRememberMe($token)';
            }, function (array $variables) {
                return $variables['trust_resolver']->isRememberMe($variables['token']);
            }),

            new ExpressionFunction('has_role', function ($role) {
                return sprintf('in_array(%s, $roles)', $role);
            }, function (array $variables, $role) {
                return in_array($role, $variables['roles']);
            }),
        );
    }
}
