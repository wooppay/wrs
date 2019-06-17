<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

class Project
{
    private $id;
    
    private $name;
    
    private $description;
    
    private $team;
    
    private $customer;
    
    private $tasks;

    private $owner;
    
    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }
    
    public function getTeam() : ?Team
    {
        return $this->team;
    }
    
    public function setName(string $name) : self
    {
        $this->name = $name;
        
        return $this;
    }

    public function setDescription($description) : self
    {
        $this->description = $description;
        
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setTeam(Team $team): self
    {
        $this->team = $team;
        
        return $this;
    }
    
    public function setCustomer(User $customer) : self
    {
        $this->customer = $customer;
        
        return $this;
    }
    
    public function getCustomer() : ?User
    {
        return $this->customer;
    }
    
    public function getTasks() : ?Collection
    {
        return $this->tasks;
    }


    public function getOwner() : ?User
    {
        return $this->owner;
    }
    
    public function setOwner(User $user) : self
    {
        $this->owner = $user;
        
        return $this;
    }
}
