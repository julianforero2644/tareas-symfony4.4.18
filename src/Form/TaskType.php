<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

// Cargar los diferentes tipos de campos
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

// Libreria para crear un campo -- select --
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

// Libreria para crear campo  --textarea--
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class TaskType extends AbstractType{

    // Metodo para crear el formulario y sus campos
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array('label' => 'Titulo'))
        ->add('content', TextareaType::class, array('label' => 'Contenido'))
        ->add('priority', ChoiceType::class, array(
            'label' => 'Prioridad',
            'choices' => array(
                'Seleccione...' => '', 
                'Alta' => 'high',
                'Medio' => 'medium',
                'Baja' => 'low'
            )
            ))
        ->add('hours', TextType::class, array('label' => 'Horas'))
        ->add('submit', SubmitType::class, array('label' => 'Guardar tarea'));
        
    }
}