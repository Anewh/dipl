<?php

namespace App\Form;

use App\Entity\Storage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StorageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('link')
            ->add('description')
            // ->add('project', CollectionType::class, [
            //     // each entry in the array will be an "email" field
            //     'entry_type' => TextType::class,
            //     // these options are passed to each "email" type
            //     'entry_options' => [
            //         'attr' => ['name' => 'name-box'],
            //     ],
            // ])
            // ->add('project', CollectionType::class, [
            //     'entry_type'   => ChoiceType::class,
            //     'entry_options'  => [
            //         'choices'  => [
            //             'Nashville' => 'nashville',
            //             'Paris'     => 'paris',
            //             'Berlin'    => 'berlin',
            //             'London'    => 'london',
            //         ],
            //     ]])
            ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Storage::class,
        ]);
    }
}
