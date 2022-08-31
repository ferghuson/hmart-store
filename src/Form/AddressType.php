<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Address Name'])
            ->add('firstname', TextType::class, ['label' => 'First Name'])
            ->add('lastname', TextType::class, ['label' => 'Last Name'])
            ->add('company', TextType::class, ['label' => 'Company', 'required'=>false])
            ->add('address', TextType::class, ['label' => 'Address'])
            ->add('city', TextType::class, ['label' => 'City'])
            ->add('country', CountryType::class, ['label' => 'Country'])
            ->add('zipcode', TextType::class, ['label' => 'Zipcode'])
            ->add('phone', TelType::class, ['label' => 'Phone Number'])
            ->add('submit', SubmitType::class, ['attr' => ['class' => 'btn bg-primary text-white']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
