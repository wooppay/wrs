<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class JobPosition
{
    private $id;

    private $name;

    private $deleted = false;

    private $profileInfos;

    public function __construct()
    {
        $this->profileInfos = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getProfileInfos(): ?Collection
    {
        return $this->profileInfos;
    }
    
    public function addProfileInfo(ProfileInfo $profileInfo): self
    {
        if (!$this->profileInfos->contains($profileInfo)) {
            $this->profileInfos[] = $profileInfo;
        }
        
        return $this;
    }
    
    public function removeProfileInfo(ProfileInfo $profileInfo): self
    {
        if ($this->profileInfos->contains($profileInfo)) {
            $this->profileInfos->removeElement($profileInfo);
        }
        
        return $this;
    }
}
