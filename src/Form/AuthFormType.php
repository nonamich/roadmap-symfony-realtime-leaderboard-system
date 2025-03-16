<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class AuthFormType extends AbstractType
{
    public function __construct(private UrlGeneratorInterface $router)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        $isRegister = $options['type'] === 'register';
        $action = $this->router->generate($isRegister ? 'app_register' : 'app_login');

        $builder->setAction($action);
        $builder->add('email', EmailType::class, [
            'constraints' => [
                new Email(),
            ]
        ]);
        $builder->add('password', PasswordType::class, [
            'constraints' => [
                new NotBlank(),
                new Length([
                    'min' => 6,
                    'max' => 4096,
                ]),
            ],
        ]);

        if ($isRegister) {
        $builder->add('agree_terms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue(),
                ],
            ]);
        } else {
            $builder->add('remember_me', CheckboxType::class, [
                'mapped' => false,
            ]);
        }

        $builder->add('submit', SubmitType::class, [
            'label' => $isRegister ? 'Register' : 'Login',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id'   => 'authenticate',
        ]);

        $resolver->setDefined('type');
    }
}
