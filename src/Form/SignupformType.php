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
use Symfony\Component\Form\Extension\Core\Type\MimeType ;

use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\DateType ;
use Symfony\Component\Validator\Constraints\EqualTo;

use Symfony\Component\Validator\Validation;


class SignupformType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',TextType::class,[
                'attr' =>[
                    'class' =>'form-control'
                ]
            ])
            ->add('role',TextType::class,[
                'attr' =>[
                    'class' =>'form-control'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'label' => 'Mot de passe',
                'label_attr' => ['class' => 'text-blue'],
                'invalid_message' => 'les mot de passes ne sont pas identiques',
                'first_options' => [
                    'label' => 'Nouveau mot de passe',
                    'attr' => ['placeholder' => 'Veuillez saisir votre mot de passe', 'class' => 'form-control']
                ],
                'second_options' => [
                    'label' => 'Confirmer le nouveau mot de passe',
                    'attr' => ['placeholder' => 'Confirmez votre mot de passe', 'class' => 'mt-1 form-control']]
            ])
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
            
            ->add('dateNaissance', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'html5' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select a date',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'YYYY-MM-DD',
                    'max' => (new \DateTime())->format('Y-m-d'), // Set max date as today's date
                ],
                
                
            ])
            

            
            ->add('adresse',TextType::class,[
                'attr' =>[
                    'class' =>'form-control'
                ]
            ])

            ->add('image', FileType::class, [
               
                'label' => 'Photo de Profil',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'inserer une image'
                ],
            
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\File([
                        'maxSize' => '1024k',
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

            ->add('role', ChoiceType::class, [
                'label' => 'Role',
                'choices' => [
                    'Participant' => 'Participant',
                    'Visiteur' => 'Visiteur',
                    
                ],
                'expanded' => true,
                'multiple' => false,
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
