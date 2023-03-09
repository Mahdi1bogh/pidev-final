<?php

namespace App\Form;

use App\Entity\Club;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints as Assert;

class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'constraints' => [
                new NotBlank(),
                new Assert\Length([
                    'min' => 4,
                    'max' => 12,
                    'minMessage' => 'Le Nom ne doit pas etre moins  4 caracteres',
                    'maxMessage' => 'Le nom ne doit pas dépasser  12 caractères.'
                ]),
            ],
        ])
        ->add('location', TextType::class, [
           
            'constraints' => [
                new NotBlank([
                    'message' => 'Le champ location ne doit pas être vide.',
                ]),
                new Assert\Choice([
                    'choices' => [
                        'Tunis', 'Sfax', 'Sousse', 'Kairouan', 'Bizerte', 'Gabès', 'Ariana',
                        'Gafsa', 'Kasserine', 'Monastir', 'Nabeul',   'Tataouine', 'Tozeur', 'Zaghouan', 'Béja', 'Ben Arous', 'Jendouba',
                        'Kef', 'Mahdia', 'Manouba', 'Medenine', 'Siliana', 'Sidi Bouzid',
                    ],
                    'message' => 'La valeur du champ location doit être l\'une des suivantes : {{ choices }}.',
                ]),
            ],
        ])
            ->add('agent')
            ->add('terain')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}
