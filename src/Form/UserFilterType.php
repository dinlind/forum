<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class UserFilterType extends AbstractType 
{
    /**
     * {@inheritdoc}
     */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('q', SearchType::class, ['attr' => ['placeholder' => 'Search username']])
			->add('active', CheckboxType::class, [
    			'label'    => 'Active',
    			'required' => false,
    		])
    		->add('banned', CheckboxType::class, [
    			'label'    => 'Banned',
    			'required' => false,
			])
		;
	}

    /**
     * {@inheritdoc}
     */
	public function getBlockPrefix()
    {
    	return 'user_filter';
    }
}
