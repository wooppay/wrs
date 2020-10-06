<?php

namespace App\Entity;

class Skill
{
    private $id;
    
    private $content;
    
    private $role;
    
    private $type;

    private $showNote;

    private $note;
    
    private $status = 1;

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
    
    public function setStatus(int $status) : self
    {
        $this->status = $status;
        
        return $this;
    }
    
    public function getStatus() : ?int
    {
        return $this->status;
    }

    public function setShowNote(bool $showNote) : self
    {
        $this->showNote = $showNote;
        
        return $this;
    }
    
    public function getShowNote() : ?bool
    {
        return $this->showNote;
    }
    
    public function setNote(string $note) : self
    {
        $this->note = $note;
        
        return $this;
    }

    public function getNote() : ?string
    {
        return $this->note;
    }
}
