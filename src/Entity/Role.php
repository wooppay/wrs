<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Role
{
    private $id;

    private $name;

    private $title;

    private $users;
    
    private $permissions;
    
    private $skills;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->permissions = new ArrayCollection();
        $this->skills = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title . ' (' . $this->name . ')';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }
    
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }
    
    public function addPermission(Permission $permission): self
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions[] = $permission;
        }
        
        return $this;
    }
    
    public function removePermission(Permission $permission): self
    {
        if ($this->permissions->contains($permission)) {
            $this->permissions->removeElement($permission);
        }
        
        return $this;
    }
    
    public function getSkills() : ?Collection
    {
        return $this->skills;
    }
}
