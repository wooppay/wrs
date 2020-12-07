<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Country
{
    private $id;

    private $name;

    private $cities;

    private $profileInfos;

    public function __construct()
    {
        $this->cities = new ArrayCollection();
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

    public function getCities(): ?Collection
    {
        return $this->cities;
    }
    
    public function addCity(City $city): self
    {
        if (!$this->cities->contains($city)) {
            $this->cities[] = $city;
        }
        
        return $this;
    }
    
    public function removeCity(City $city): self
    {
        if ($this->cities->contains($city)) {
            $this->cities->removeElement($city);
        }
        
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
