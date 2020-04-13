<?php

namespace App\Form;

use App\Entity\PropertySearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

class PropertySearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('maxPrice', IntergerType::class, [
                'required' => false,
                'label' =>false,
                'attr' => [
                    'placeholder' => 'budgetMax'
                ]
            ])
            ->add('minSurface', IntergerType::class, [
                'required' => false,
                'label' =>false,
                'attr' => [
                    'placeholder' => 'surfaceMinial'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PropertySearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }
}
