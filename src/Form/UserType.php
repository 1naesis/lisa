<?php

namespace App\Form;

use App\Entity\Position;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, array(
                'label' => false
            ))
            ->add('new_password', PasswordType::class, array(
                'label' => false,
                'mapped' => false,
                'required' => false,
            ))
            ->add('name', TextType::class, array(
                'label' => false,
            ))
            ->add('surname', TextType::class, array(
                'label' => false,
            ))
            ->add('patronymic', TextType::class, array(
                'label' => false,
                'required' => false,
            ))
            ->add('position', EntityType::class, array(
                'label' => false,
                'class' => Position::class,
                'choice_label' => 'title'
            ))
            ->add('description', TextareaType::class, array(
                'label' => false,
                'required' => false,
            ))
            ->add('phone', TelType::class, array(
                'label' => false,
                'required' => false,
            ))
            ->add('avatar', FileType::class, array(
                'label' => false,
                'mapped' => false,
                'required' => false,
            ))
            ->add('save', SubmitType::class, array(
                'label' => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
