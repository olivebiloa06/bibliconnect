<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class BookFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'constraints' => [new NotBlank()],
                'label' => 'Titre',
            ])
            ->add('auteur', TextType::class, [
                'constraints' => [new NotBlank()],
                'label' => 'Auteur',
            ])
            ->add('langue', TextType::class, [
                'required' => false,
                'label' => 'Langue',
            ])
            ->add('stock', IntegerType::class, [
                'constraints' => [new PositiveOrZero()],
                'label' => 'Stock',
            ])
            ->add('category', EntityType::class, [
                'class'        => Category::class,
                'choice_label' => 'nom',
                'required'     => false,
                'placeholder'  => 'Choisir une catégorie',
                'label'        => 'Catégorie',
            ])
            ->add('imageFile', FileType::class, [
                'label'    => 'Image de couverture',
                'mapped'   => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize'   => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Format accepté : JPG, PNG, WEBP',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Book::class]);
    }
}