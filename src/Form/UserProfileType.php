<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label'=>'Email',
                'attr'=>['class'=>'form-control-lg', 'disabled'=>true]
            ])
            ->add('firstname', TextType::class, ['label' => 'Prénoms'])
            ->add('lastname', TextType::class, ['label' => 'Nom'])
            ->add('submit', SubmitType::class, [
                'label'=>"Update",
                'attr'=>['class'=>'btn-lg rounded-pill text-white bg-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}