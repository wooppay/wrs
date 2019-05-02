<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Task;

class TaskService
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }
    
    public function all() : array
    {
        return $this->entityManager
        ->getRepository(Task::class)
        ->findAll();
    }
    
    public function oneById(int $id) : Task
    {
        return $this->entityManager
        ->getRepository(Task::class)
        ->find($id);
    }
    
    public function create(Task $task) : Task
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();
        
        return $task;
    }
}

