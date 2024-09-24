<?php

namespace App\Controller;

use App\Components\TaskStatusEnum;
use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/task')]
final class TaskController extends AbstractController
{
    #[Route(name: 'app_task_index', methods: ['GET'])]
    public function index(TaskRepository $taskRepository): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setCreatedAt(new \DateTimeImmutable());
            $task->setUpdatedAt(new \DateTime());

            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'Task Created Successfully.');
            return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUpdatedAt(new \DateTime());
            $entityManager->flush();

            $this->addFlash('success', 'Task Updated Successfully.');
            return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->getPayload()->getString('_token'))) {
            // Check if the task has any assignments
            $taskAssignment = $task->getTaskAssignment();
                        
            if ($taskAssignment) {
                $this->addFlash('error', 'This Task cannot be deleted because it is assigned to User.');
                return $this->redirectToRoute('app_task_index');
            }
        
            $entityManager->remove($task);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Task Deleted Successfully.');
        return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
    }

    public function updateTaskStatus(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $taskId = $data['id'];
        $newStatus = $data['status'];

        if (!TaskStatusEnum::tryFrom($newStatus)) {
            return new JsonResponse(['error' => 'Invalid status'], 400);
        }
        
        $task = $em->getRepository(Task::class)->find($taskId);
        if (!$task) {
            return new JsonResponse(['error' => 'Task not found'], 404);
        }

        $task->setStatus(TaskStatusEnum::from($newStatus));
        $em->flush();

        return new JsonResponse(['success' => true]);
    }
}
