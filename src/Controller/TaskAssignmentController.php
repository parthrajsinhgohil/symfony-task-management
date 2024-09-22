<?php

namespace App\Controller;

use App\Entity\TaskAssignment;
use App\Form\TaskAssignmentType;
use App\Repository\TaskAssignmentRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/task/assignment')]
final class TaskAssignmentController extends AbstractController
{
    #[Route(name: 'app_task_assignment_index', methods: ['GET'])]
    public function index(TaskAssignmentRepository $taskAssignmentRepository): Response
    {
        return $this->render('task_assignment/index.html.twig', [
            'task_assignments' => $taskAssignmentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_task_assignment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $taskAssignment = new TaskAssignment();
        $form = $this->createForm(TaskAssignmentType::class, $taskAssignment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskAssignment->setCreatedAt(new \DateTimeImmutable());
            $taskAssignment->setUpdatedAt(new \DateTime());

            $entityManager->persist($taskAssignment);
            $entityManager->flush();

            return $this->redirectToRoute('app_task_assignment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task_assignment/new.html.twig', [
            'task_assignment' => $taskAssignment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_assignment_show', methods: ['GET'])]
    public function show(TaskAssignment $taskAssignment): Response
    {
        return $this->render('task_assignment/show.html.twig', [
            'task_assignment' => $taskAssignment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_task_assignment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TaskAssignment $taskAssignment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskAssignmentType::class, $taskAssignment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskAssignment->setUpdatedAt(new \DateTime());
            
            $entityManager->flush();

            return $this->redirectToRoute('app_task_assignment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task_assignment/edit.html.twig', [
            'task_assignment' => $taskAssignment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_assignment_delete', methods: ['POST'])]
    public function delete(Request $request, TaskAssignment $taskAssignment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$taskAssignment->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($taskAssignment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_task_assignment_index', [], Response::HTTP_SEE_OTHER);
    }
}
