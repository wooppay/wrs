<?php

namespace App\Entity;

use App\Enum\TaskEnum;

class Task
{
    private $id;
    
    private $name;
    
    private $description;
    
    private $status = TaskEnum::NEW;
    
    private $executor;
    
    private $team;
    
    private $project;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getName(): ?string
    {
        return $this->name;
    }
    
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    public function getExecutor(): ?User
    {
        return $this->executor;
    }
    
    public function getTeam(): ?Team
    {
        return $this->team;
    }
    
    public function getProject(): ?Project
    {
        return $this->project;
    }
    
    public function getStatus(): ?int
    {
        return $this->status;
    }
    
    public function setName(string $name): self
    {
        $this->name = $name;
        
        return $this;
    }
    
    public function setDescription(string $description): self
    {
        $this->description = $description;
        
        return $this;
    }
    
    public function setExecutor(User $user): self
    {
        $this->executor = $user;
        
        return $this;
    }
    
    public function setTeam(Team $team): self
    {
        $this->team = $team;
        
        return $this;
    }
    
    public function setProject(Project $project): self
    {
        $this->project = $project;
        
        return $this;
    }
    
    public function setStatus(int $status): self
    {
        $this->status = $status;
        
        return $this;
    }
}
