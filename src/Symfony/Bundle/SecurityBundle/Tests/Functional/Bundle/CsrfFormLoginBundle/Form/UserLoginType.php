<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\SecurityBundle\Tests\Functional\Bundle\CsrfFormLoginBundle\Form;

use Makhan\Component\Form\AbstractType;
use Makhan\Component\Form\FormBuilderInterface;
use Makhan\Component\Form\FormError;
use Makhan\Component\Form\FormEvents;
use Makhan\Component\Form\FormEvent;
use Makhan\Component\HttpFoundation\RequestStack;
use Makhan\Component\OptionsResolver\OptionsResolver;
use Makhan\Component\Security\Core\Security;

/**
 * Form type for use with the Security component's form-based authentication
 * listener.
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 * @author Jeremy Mikola <jmikola@gmail.com>
 */
class UserLoginType extends AbstractType
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'Makhan\Component\Form\Extension\Core\Type\TextType')
            ->add('password', 'Makhan\Component\Form\Extension\Core\Type\PasswordType')
            ->add('_target_path', 'Makhan\Component\Form\Extension\Core\Type\HiddenType')
        ;

        $request = $this->requestStack->getCurrentRequest();

        /* Note: since the Security component's form login listener intercepts
         * the POST request, this form will never really be bound to the
         * request; however, we can match the expected behavior by checking the
         * session for an authentication error and last username.
         */
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($request) {
            if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
                $error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
            } else {
                $error = $request->getSession()->get(Security::AUTHENTICATION_ERROR);
            }

            if ($error) {
                $event->getForm()->addError(new FormError($error->getMessage()));
            }

            $event->setData(array_replace((array) $event->getData(), array(
                'username' => $request->getSession()->get(Security::LAST_USERNAME),
            )));
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        /* Note: the form's csrf_token_id must correspond to that for the form login
         * listener in order for the CSRF token to validate successfully.
         */

        $resolver->setDefaults(array(
            'csrf_token_id' => 'authenticate',
        ));
    }
}
