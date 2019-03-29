<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Team
{
    private $id;
    
    private $name;
    
    private $description;
    
    private $members;
    
    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setName(string $name) : self
    {
        $this->name = $name;
        
        return $this;
    }
    
    public function getName() : ?string
    {
        return $this->name;
    }
    
    public function setDescription(string $description) : self
    {
        $this->description = $description;
        
        return $this;
    }
    
    public function getDescription() : ?string
    {
        return $this->description;
    }
    
    public function getMembers() : Collection
    {
        return $this->members;
    }
    
    public function addMember(User $user): self
    {
        if (!$this->members->contains($user)) {
            $this->members[] = $user;
        }
        
        return $this;
    }
    
    public function removeMember(User $user): self
    {
        if ($this->members->contains($user)) {
            $this->members->removeElement($user);
        }
        
        return $this;
    }
}
