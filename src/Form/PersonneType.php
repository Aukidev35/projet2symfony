<?php

namespace App\Form;


use App\Entity\Hobby;
use App\Entity\Job;
use App\Entity\Profil;
use App\Entity\Personne;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PersonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('name')
            ->add('age')
            ->add('createdAt')
            ->add('updateAt')
            ->add('profil', EntityType::class, [
                'expanded'=>true,
                'required'=>false,
                'class'=>Profil::class,
                'multiple'=>false,
                'attr'=>[
                    'class'=>'select2'
                ]
            ])
            ->add('hobbies', EntityType::class, [
                'expanded'=>false,
                'required'=>false,                
                'class'=>Hobby::class,
                'multiple'=>true,
                'attr'=>[
                    'class'=>'select2'
                ] 
                ])
            ->add('job', EntityType::class, [
                'required'=>false,
                'class'=>Job::class,
                'attr'=>[
                    'class'=>'select2'
                ] 
            ])
            ->add('photo', FileType::class, [
                'label' => 'votre image de profil (image file)',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpg',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image document',
                    ])
                ],
            ])


            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personne::class,
        ]);
    }
}
