<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'label'=>'Titre du produit',
            ])
            ->add('descript',TextareaType::class,[
                'label'=>'Description du produit',
            ])
            ->add('color',TextType::class, [
                'label'=>'couleur du produit',
            ])
            ->add('size',ChoiceType::class,[
                'label'=>'taille',
                'choices'=>[
                    'xs' => 'xs',
                    's' => 's',
                    'M' => 'm',
                    'L' => 'l',
                    'XL' => 'xl'
                ]
            ])
            ->add('collection',ChoiceType::class,[
                'label'=>'Collection',
                'choices'=>[
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                    'Mixte' => 'mixte',
                ]   
            ])
            ->add('photo',FileType::class,[
                'label'=>'photo',
                'data_class' => null,
            ])
            ->add('price', TextType::class,[
                'label'=>'prix du produit'
            ])
            ->add('stock',TextType::class,[
                'label'=>'Quantité du produit'
            ])
            ->add('submit',SubmitType::class,[
                'label'=> "Ajouter",
                'validate' => false,
                'attr' => [
                    'class' => "d-block mx-auto my-3 btn btn-success col-3"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'allo_file_upload'=> true,//donner l'oturisation pour les photos
            'photo' => null,
        ]);
    }
}
