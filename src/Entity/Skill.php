<?php

namespace App\Entity;

class Skill
{
    private $id;
    
    private $content;
    
    private $role;
    
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setContent(string $content) : self
    {
        $this->content = $content;
        
        return $this;
    }
    
    public function getContent() : ?string
    {
        return $this->content;
    }
    
    public function setRole(Role $role) : self
    {
        $this->role = $role;
        
        return $this;
    }
    
    public function getRole() : ?Role
    {
        return $this->role;
    }
    
    public function getType() : ?string
    {
        return $this->type;
    }
    
    public function setType(string $type) : self
    {
        $this->type = $type;
        
        return $this;
    }
}
