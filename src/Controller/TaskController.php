<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function listAction(Request $request)
    {
        $user = $this->getUser();
        return $this->render(
            'task/list.html.twig',
            ['tasks' => $this->em->getRepository(Task::class)->findAll(),
                'user' => $user]
        );
    }


    public function createAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $user = $this->getUser();
        if ($user == null) {
            return $this->redirectToRoute('login');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $now = new \DateTime();
            $now->format('Y-m-d H:i:s');
            /*$em = $this->getDoctrine()->getManager();*/
            $task->setUser($user);
            $task->setCreatedAt($now);
            $this->em->persist($task);
            $this->em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }


    public function editAction(Task $task, Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user === $task->getUser() || in_array('ROLE_ADMIN', $user->getRoles())) {
                $this->em->flush();
                $this->addFlash('success', 'La tâche a bien été modifiée.');
            } else {
                $this->addFlash('error', 'Vous n\'êtes pas autorisé à modifier cette tâche.');
            }
            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
            'user' => $user,
        ]);
    }


    public function toggleTaskAction(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->em->flush();
        if ($task->isDone()) {
            $tasktoggleInfo = 'faite';
        } else {
            $tasktoggleInfo = 'à faire';
        }

        $this->addFlash(
            'success',
            sprintf('La tâche %s a bien été marquée comme %s.', $task->getTitle(), $tasktoggleInfo)
        );

        return $this->redirectToRoute('task_list');
    }


    public function deleteTaskAction(Task $task)
    {
        $this->em->remove($task);
        $this->em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
