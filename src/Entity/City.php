<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class City
{
    private $id;

    private $name;

    private $country;

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

    public function getCountry() : ?Country
    {
        return $this->country;
    }
    
    public function setCountry(Country $country) : self
    {
        $this->country = $country;
        
        return $this;
    }
}
