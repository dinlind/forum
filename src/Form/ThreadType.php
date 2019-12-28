<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThreadType extends AbstractType 
{
    /**
     * {@inheritdoc}
     */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
            ->add('title', TextType::class)
            ->add('body', TextareaType::class)
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->add('save_draft', SubmitType::class, ['label' => 'Save draft'])
        ;
	}

    /**
     * {@inheritdoc}
     */
	public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Thread',
        ));
    }
}
