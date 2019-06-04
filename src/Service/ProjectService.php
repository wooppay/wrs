<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Project;

class ProjectService
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }
    
    public function all() : array
    {
        return $this->entityManager
        ->getRepository(Project::class)
        ->findAll();
    }
    
    public function oneById(int $id) : Project
    {
        return $this->entityManager
        ->getRepository(Project::class)
        ->oneById($id);
    }
    
    public function create(Project $project) : Project
    {
        $this->entityManager->persist($project);
        $this->entityManager->flush();
        
        return $project;
    }
}

