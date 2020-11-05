<?php

namespace App\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use App\Enum\UserEnum;
use App\Entity\ProfileInfo;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Common\Collections\Collection;

class User implements UserInterface
{
    private $id;

    private $email;

    private $roles = [];

    private $password;
    
    private $status = UserEnum::NOT_APPROVED;
    
    private $teams;
    
    private $tasks;
    
    private $projects;

    private $myTasks;

    private $myProjects;

    private $rates;

    private $authorRates;

    private $myTeams;

    private $profileInfo;
    
    public function __construct()
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->teams = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->myTasks = new ArrayCollection();
        $this->myProjects = new ArrayCollection();
        $this->rates = new ArrayCollection();
        $this->authorRates = new ArrayCollection();
        $this->myTeams = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->email;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = [];
        
        foreach ($this->roles as $role) {
            $roles[$role->getId()] = $role->getName();
        }
        
        return $roles;
    }

    public function setRoles(ArrayCollection $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    
    public function getStatus() : int
    {
        return (int) $this->status;
    }
    
    public function setStatus(int $status) : self
    {
        $this->status = $status;
        
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    
    public function getTeams(): ?Collection
    {
        return $this->teams;
    }
    
    public function getTasks() : ?Collection
    {
        return $this->tasks;
    }
    
    public function getProjects() : ?Collection
    {
        return $this->projects;
    }

    public function getMyTasks() : ?Collection
    {
        return $this->myTasks;
    }

    public function getMyProjects() : ?Collection
    {
        return $this->myProjects;
    }

    public function getRates() : ?Collection
    {
        return $this->rates;
    }

    public function getAuthorRates() : ?Collection
    {
        return $this->authorRates;
    }

    public function getMyTeams() : ?Collection
    {
        return $this->myTeams;
    }

    public function getProfileInfo(): ?ProfileInfo
    {
        return $this->profileInfo;
    }

    public function setProfileInfo(ProfileInfo $profileInfo): self
    {
        $this->profileInfo = $profileInfo;

        return $this;
    }
}
