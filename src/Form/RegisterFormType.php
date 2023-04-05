<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label'=>'Email',
                'constraints'=>[
                    new NotBlank([
                        'message'=> 'Ce champ ne peut être vide :{{ value }}'
                    ]),
                    new Length([
                        'min'=> 6,
                        'max'=> 180,
                        'minMessage' => 'votre email doit comporter au minimum {{ limit }} caractéres.',
                        'maxMessage' => 'votre email doit comporter au maximum {{ limit }} caractéres.',

                    ]),
                ],

            ])
            ->add('password', PasswordType::class, [
                'label'=>'Mot de passe',
                'contraints' =>[
                    new NotBlank([
                        'message' =>'Ce champ ne peut etre vide : {{ value }}'
                    ]),
                    new Length([
                        'min'=> 4,
                        'max'=> 255,
                        'minMessage' => 'votre email doit comporter au minimum {{ limit }} caractéres.',
                        'maxMessage' => 'votre email doit comporter au maximum {{ limit }} caractéres.',
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'label'=>'Prénom',
                'constraints'=>[
                    new NotBlank([
                        'message'=> 'Ce champ ne peut être vide :{{ value }}'
                    ]),
                    new Length([
                        'min'=> 2,
                        'max'=> 100,
                        'minMessage' => 'votre email doit comporter au minimum {{ limit }} caractéres.',
                        'maxMessage' => 'votre email doit comporter au maximum {{ limit }} caractéres.',

                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label'=>'Nom',
                'contraints' =>[
                    new NotBlank([
                        'message' =>'Ce champ ne peut être vide : {{ value }}'
                    ]),
                    new Length([
                        'min'=> 1,
                        'max'=> 100,
                        'minMessage' => 'votre email doit comporter au minimum {{ limit }} caractéres.',
                        'maxMessage' => 'votre email doit comporter au maximum {{ limit }} caractéres.',
                    ]),
                ],
            ])
            ->add('gender', ChoiceType::class, [
                'label'=>'Civilité',
                'choices'=>[
                    'Homme'=>'homme',
                    'Femme'=>'femme',
                    'Non binaire'=> 'non-binaire'
                ],
                'expanded'=> true,// pour le bouton radio

                'label_attr' => [
                    'class' => 'radio-inline'
                ],
                'choice_attr' => [
                    'class' => 'radio-inline'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut être vide : {{ value }}'
                    ])
                ]
            ])

            ->add('submit', SubmitType::class, [
                'label'=>'Valider',
                'validate'=> False,
                'attr'=> [
                    'class' =>'d-block mx-auto col-3 btn btn-warning'
                ]
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
