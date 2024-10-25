<?php

namespace App\Form;

use App\Entity\Hospital;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DoctorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name')
        ->add('dateBorn', null, [
            'widget' => 'single_text',
        ])
        ->add('Hospital', EntityType::class, [
          'class' => Hospital::class,
          'choice_label' => 'name',
      ])
    ;        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
