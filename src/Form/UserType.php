<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'label' => 'Email',
            ])
            ->add('phone', TextType::class, [
                'label' => 'Телефон',
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Имя',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Фамилия',
            ])
            ->add('githubName', TextType::class, [
                'label' => 'Github имя',
            ])
            ->add('token', TextType::class, [
                'label' => 'Токен',
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
