<?php

namespace App\Form;

use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignupType extends AbstractType 
{
    /**
     * {@inheritdoc}
     */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('username', TextType::class, [
			    'label' => false,
                'attr' => [
                    'placeholder' => 'Enter your username',
                ],
                'error_bubbling' => true,
            ])
			->add('email', EmailType::class, [
			    'label' => false,
                'attr' => [
                    'placeholder' => 'Enter your email',
                ],
                'error_bubbling' => true,
            ])
        	->add('plainPassword', RepeatedType::class, [
        		'type' => PasswordType::class,
        		'invalid_message' => 'Passwords do not match.',
        		'first_options' => [
        		    'label' => false,
                    'attr' => [
                        'placeholder' => 'Enter a password',
                    ]
                ],
        		'second_options' => [
        		    'label' => false,
                    'attr' => [
                        'placeholder' => 'Enter password again',
                    ]
                ],
                'error_bubbling' => true,
        	])
            ->add('captchaCode', CaptchaType::class, [
                'captchaConfig' => 'SignupCaptcha',
                'label' => false,
                'attr' => [
                    'placeholder' => 'Enter the text you see on the image',
                ],
                'error_bubbling' => true,
            ]);
	}

    /**
     * {@inheritdoc}
     */
	public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\User',
            'validation_groups' => ['Default', 'registration']
        ));
    }
}
