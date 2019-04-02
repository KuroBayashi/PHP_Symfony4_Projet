<?php

namespace App\Form;

use App\Entity\Defibrillator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DefibrillatorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('longitude' , NumberType::class)
            ->add('latitude' , NumberType::class)
            ->add('note', TextareaType::class)
            ->add('available' , CheckboxType::class)
            ->add('reported' , CheckboxType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Defibrillator::class,
        ]);
    }
}
