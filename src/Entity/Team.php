<?php

namespace App\Entity;

class Team
{
    private $id;
    
    private $name;
    
    private $description;

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
}
