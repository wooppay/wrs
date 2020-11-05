<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class Country
{
    private $id;

    private $name;

    private $cities;

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

    public function getCities(): Collection
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
}
