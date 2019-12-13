<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchJobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city',TextType::class,[
                'required'=>false
            ])
            ->add('experience_level',ChoiceType::class,[
                'choices'=>[
                    'junior'=>'junior',
                    'mid'=>'mid',
                    'expert'=>'senior'
                ]
            ])
            ->add('tech',TextType::class,[
                'required'=>false
            ])
            ->add('submit',SubmitType::class)
            ->getForm();
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
