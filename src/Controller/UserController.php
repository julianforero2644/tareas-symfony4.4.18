<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Relacionamos librerias objeto --Request--  para utilizar los parametros
use Symfony\Component\HttpFoundation\Request;

// Relacionamos el modelo para trabajar con sus metodos
use App\Entity\User;

// Relacionamos el formulario para poderlo utillizar
use App\Form\RegisterType;

// Relacionamos la libreria de UserPasswordEncoder para las contraseñas
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

// Relacionamos libreria de autenticacion de usuario
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    public function login(AuthenticationUtils $authenticationUtils){

        // Captura de error si es producido
        $error = $authenticationUtils->getLastAuthenticationError();

        // Guarda el usuario que intenta autenticarse
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', array(
            'error' => $error,
            'last_username' => $lastUsername
        ));
    }

    // Metodo de registro 
    public function register(Request $request, UserPasswordEncoderInterface $encoder){

        // Creamos el objeto del Usuario para rellenar el formulario
        $user = new User();

        // Creamos el formulario
        $form = $this->createForm(RegisterType::class, $user);

        // 1. Vincular el formulario con el objeto rellenandolo con los datos del formulario
        $form->handleRequest($request);

        // Comprobar si se envio el formulario 
        if($form->isSubmitted()){

            $user->setRole('ROLE_USER'); 
            //$date_now = (new \DateTime())->format('d-m-Y H:i:s');   
            
            $user->setCreatedAt(new \DateTime('now'));

            // Encriptar la contraseña
            // 1. en Config - packages - security.yaml configurar los -- encoders -- para utilizarlos
            // 2. Cargamos la libreria UserPasswordEncoder
            $cifrar_password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($cifrar_password);

            // Guardar el usuario
            // Crear el entity Manager
            $em = $this->getDoctrine()->getManager();

            // Persisto el objeto
            $em->persist($user);

            // Grabo en la DB
            $em->flush();

            return $this->redirectToRoute('tasks');          


        }


        // Renderizar una vista 
        return $this->render('user/register.html.twig', [
            // Se pasa el formulario a la vista 
            'form' => $form->createView()
        ]);
    }
}
