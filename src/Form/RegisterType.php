<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

// Cargar los diferentes tipos de campos
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class RegisterType extends AbstractType{

    // Metodo para crear el formulario y sus campos
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array('label' => 'Nombre'))
        ->add('surname', TextType::class, array('label' => 'Apellido'))
        ->add('email', EmailType::class, array('label' => 'Email'))
        ->add('password', PasswordType::class, array('label' => 'Password'))
        ->add('submit', SubmitType::class, array('label' => 'Registro'));
        
    }
}