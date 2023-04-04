<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
                'label'=>'Email'
            ])
            ->add('password', PasswordType::class, [
                'label'=>'Mot de passe'
            ])
            ->add('firstname', TextType::class, [
                'label'=>'Prénom'
            ])
            ->add('lastname', TextType::class, [
                'label'=>'Nom'
            ])
            ->add('gender', ChoiceType::class, [
                'label'=>'Civilité',
                'choices'=>[
                    'Homme'=>'homme',
                    'Femme'=>'femme',
                    'Non binaire'=> 'non-binaire'
                ],
                'expanded'=> true,// pour le bouton radio
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
