<?php

namespace App\Form;

use App\Entity\Terrain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints as Assert;


class TerrainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('type', TextType::class, [
            'label' => 'Nom',
            'constraints' => [
                new NotBlank(),
                new Assert\Regex([
                    'pattern' => '/^[^0-9]*$/',
                    'message' => 'Le champ Nom ne doit pas contenir de chiffres.',
                ]),
            ],
        ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image (JPG or PNG file)',
              
                'required' => false,
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '1024k',
                        
                        'maxSizeMessage' => 'Le fichier doit être une image de type JPG, PNG .',
                    ]),
                ],
            ])  
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Terrain::class,
        ]);
    }
}
