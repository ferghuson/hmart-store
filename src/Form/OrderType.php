<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
            ->add('address', EntityType::class, [
                'label' => false,
                'required' => true,
                'class' => Address::class,
                'choices' => $user->getAddresses(),
                'multiple' => false,
                'expanded' => true,
                'attr' => ['class' => 'small row justify-content-around'],
            ])
            ->add('carrier', EntityType::class, [
                'label' => 'Choose your favory carrier :',
                'required' => true,
                'class' => Carrier::class,
                'multiple' => false,
                'expanded' => true,
                'attr' => ['class' => 'small row justify-content-around']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'PLACE ORDER',
                'attr' => ['class' => 'btn btn-block bg-primary text-white']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => array()
        ]);
    }
}
