<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// relacionamos la libreria -- HttpFoundation\Request-- para recibir datos del Formulario
use Symfony\Component\HttpFoundation\Request;

// Relacionamos el modelo para trabajar con sus metodos
use App\Entity\Task;

// Relacionamos el modelo para trabajar con sus metodos
use App\Entity\User;

// Se relaciona el metodo para crear el formulario
use App\Form\TaskType;
use Symfony\Component\Security\Core\User\UserInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

class TaskController extends AbstractController
{

    public function index(): Response
    {   // Pruba de entidades y relaciones 

        // Creamos el Entity Manager
        $em = $this->getDoctrine()->getManager();

        // El repositorio de tareas
        $repo_tasks = $this->getDoctrine()->getRepository(Task::class);

        // Cargamos todas las tareas con el metodo -- findAll();
        $tasks = $repo_tasks->findBy([], ['id' => 'DESC']);

        /*
        // Recorrremos la variable de en donde queda cargada la informacion
        foreach($tasks as $task){
            echo $task->getUser()->getName().' ; '.$task->getUser()->getEmail().' - '.$task->getTitle().'<br>';
        }

        $repo_users = $this->getDoctrine()->getRepository(User::class);
        $users = $repo_users->findAll();

        foreach($users as $user){
            echo "<h2>{$user->getName()} {$user->getSurname()}</h2>";

            foreach($user->getTask() as $task){
                echo $task->getTitle().'<br>';
            }
        }
        */
        return $this->render('task/index.html.twig', [
            'tasks' => $tasks
        ]);
    }

    // Metodo para ver una tarea en concreto (objeto de tipo tarea -- Task --)
    public function detail(Task $task)
    {
        // Se verifica que no existe la tarea
        if (!$task) {

            return $this->redirectToRoute('tasks');
        }

        return $this->render('task/detail.html.twig', [
            // Le paso el objeto de tarea --$task-- que me llega como parametro
            'tasks' => $task
        ]);
    }

    // Metodo para crear una tarea
    // Se crea el objeto --Request-- para poder recibir las cosas del formulario
    public function create(Request $request, UserInterface $user)
    {
        // Se crea objeto el cual se rellenara
        $task = new Task();
        // Se crea el formulario y se pasa el objeto --$task--
        $form = $this->createForm(TaskType::class, $task);

        // Unir lo que llega por la peticion al objeto -- handleRequest() --
        $form->handleRequest($request);

        // Validar si el formulario fue enviado -- isSubmitted() --
        if ($form->isSubmitted() && $form->isValid()) {
            // Fecha de creacion de la tarea
            $task->setCreatedAt(new \DateTime('now'));

            // Sacar el objeto del usuario identificado con -- UserInterface $user --
            $task->setUser($user);

            // Guardar los los datos
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirect(
                $this->generateUrl('task_detail', [
                    'id' => $task->getId()
                ])
            );
        }
        return $this->render('task/create.html.twig', [
            // Pasar el formulario a la vist para poder mostrarlo
            'form' => $form->createView()
        ]);
    }

    // Metodo para mis tareas
    public function myTasks(UserInterface $user)
    {
        $tasks = $user->getTask();

        return $this->render('task/myTasks.html.twig', [
            'tasks' => $tasks
        ]);
    }

    // Metodo para editar una tarea con los parametros
    // Request $request que el la informacion que llega por --POST
    // --Task $task -- que va hacer una tarea que se va a editar
    public function edit(Request $request, UserInterface $user, Task $task)
    {

        // Comprobar si el usuario a creado la tarea
        if (!$user || $user->getId() != $task->getUser()->getId()) {

            return $this->redirectToRoute('tasks');
        }

        // Se crea el formulario y se pasa el objeto --$task--
        $form = $this->createForm(TaskType::class, $task);

        // Unir lo que llega por la peticion al objeto -- handleRequest() --
        $form->handleRequest($request);

        // Validar si el formulario fue enviado -- isSubmitted() --
        if ($form->isSubmitted() && $form->isValid()) {

            // Guardar los los datos
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirect(
                $this->generateUrl('task_detail', [
                    'id' => $task->getId()
                ])
            );
        }

        return $this->render('task/create.html.twig', [
            'edit' => true,
            // le paso a la vista el formulario
            'form' => $form->createView()
        ]);
    }

    // Crear metodo para eliminar una tarea
    public function delete(Task $task, UserInterface $user)
    {
        // Comprobar si el usuario a creado la tarea
        if (!$user || $user->getId() != $task->getUser()->getId()) {

            return $this->redirectToRoute('tasks');
        }

        // Se verifica que no existe la tarea
        if (!$task) {
            return $this->redirectToRoute('tasks');
        }

        // Eliminamos la tarea
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        return $this->redirectToRoute('tasks');
    }
}
