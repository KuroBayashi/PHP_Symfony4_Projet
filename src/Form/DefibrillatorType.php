<?php

namespace App\Form;

use App\Entity\Defibrillator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DefibrillatorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('latitude', NumberType::class, ['scale' => 13, 'empty_data' => 0.0])
            ->add('longitude', NumberType::class, ['scale' => 13, 'empty_data' => 0.0])
            ->add('note', TextareaType::class, ['required' => false])
            ->add('available', CheckboxType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Defibrillator::class,
        ]);
    }
}
