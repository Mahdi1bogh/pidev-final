<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\EqualTo;
class UpdateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
        ->add('nom',TextType::class,[
            'attr' =>[
                'class' =>'form-control'
            ]
        ])
        ->add('prenom',TextType::class,[
            'attr' =>[
                'class' =>'form-control'
            ]
        ])
        ->add('adresse',TextType::class,[
            'attr' =>[
                'class' =>'form-control'
            ]
        ])
        ->add('image', FileType::class, [
            'data_class' => null ,
            'label' => 'Photo de Profil',
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'inserer une image'
            ],
        
            'constraints' => [
                new \Symfony\Component\Validator\Constraints\File([
                    
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                        'image/jpg'
                    ],
                    'mimeTypesMessage' => 'Veuillez saisir une image valide (jpeg, png, gif)',
                ])
            ],
        ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
         
        ]);
    }
}
