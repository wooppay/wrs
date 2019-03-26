<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

class Permission
{
    private $id;
    
    private $name;
    
    private $roles;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    
    public function getName(): ?string
    {
        return $this->name;
    }
    
    public function getRoles(): Collection
    {
        return $this->roles;
    }
    
    public function addRoles(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }
        
        return $this;
    }
    
    public function removeRole(Role $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }
        
        return $this;
    }
}
