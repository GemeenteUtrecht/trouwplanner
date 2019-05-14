<?php
// src/Form/AmbtenaarType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('naam', TextType::class, [
				'attr' => ['class' => 'form-control'],
				'label' => 'Naam',
				'label_attr' => ['class' => 'control-label col-sm-2'],
				'required'   => false,
				'empty_data' => 'Fantastisch product',
				
		])
		->add('samenvatting', TextareaType::class, [
				'attr' => ['class' => 'form-control'],
				'label_attr' => ['class' => 'control-label col-sm-2'],
				'required'   => false,
				'empty_data' => 'Dit fantastische product....',
				
		])
		->add('beschrijving', TextareaType::class, [
				'attr' => ['class' => 'form-control'],
				'label_attr' => ['class' => 'control-label col-sm-2'],
				'required'   => false,
				'empty_data' => 'Is natuurlijk super fantastisch',
				
		])
		->add('opslaan', SubmitType::class, [
				'attr' => ['class' => 'btn btn-primary'],
		])
		;
	}
}
