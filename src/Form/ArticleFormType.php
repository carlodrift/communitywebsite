<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'attr' => [
                    'maxlength' => 100,
                ], 'empty_data' => ''
                ,
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'maxlength' => 4000,
                ],
            ])
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Général' => 'Général',
                    'Entraide' => 'Entraide',
                    'Suggestions' => 'Suggestions',
                    'Tenue' => 'Tenue',
                    'Matchmaking' => 'Matchmaking',
                    'Astuces' => 'Astuces',
                    'Autre' => 'Autre',
                ],
            ])
            ->add('thumbnails', FileType::class, [
                'label' => 'Miniature ',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '8M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => "Format d'image non supporté",
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
