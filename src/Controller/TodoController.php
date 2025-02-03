<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Repository\TodoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class TodoController extends AbstractController
{
    private $todoRepository;
    private $entityManager;
    private $serializer;

    public function __construct(TodoRepository $todoRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->todoRepository = $todoRepository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    #[Route('/todos', name: 'todos_page')]
    public function showTodos()
    {
        return $this->render('todo/index.html.twig');
    }
    #[Route('/api/todos', name: 'api_get_todos', methods: ['GET'])]
    public function getTodos(): JsonResponse
    {
        $todos = $this->todoRepository->findAll();
        $todosNormalized = $this->serializer->normalize($todos, null, ['groups' => 'todo:read']);

        return $this->json($todosNormalized);
    }

    #[Route('/api/todo', name: 'api_add_todo', methods: ['POST'])]
    public function addTodo(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $todo = new Todo();
        $todo->setTitle($data['title']);
        $todo->setCompleted($data['completed'] ?? false);
        $todo->setCreatedAt(new \DateTime());
        $this->entityManager->persist($todo);
        $this->entityManager->flush();

        return $this->json($todo, 201);
    }

    #[Route('/api/todo/{id}', name: 'api_update_todo', methods: ['PUT'])]
    public function updateTodo(Request $request, Todo $todo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $todo->setCompleted($data['completed']);
        $this->entityManager->flush();

        return $this->json($todo);
    }

    #[Route('/api/todo/{id}', name: 'api_delete_todo', methods: ['DELETE'])]
    public function deleteTodo(Todo $todo): JsonResponse
    {
        $this->entityManager->remove($todo);
        $this->entityManager->flush();

        return $this->json(null, 204);
    }
}