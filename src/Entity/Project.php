<?php

namespace App\Entity;

class Project
{
    private $id;
    
    private $name;
    
    private $description;
    
    private $team;

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
}
