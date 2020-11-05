<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use App\Entity\JobPosition;

class ProfileInfo
{
    private $id;

    private $user;

    private $firstname;

    private $surname;

    private $gender;

    private $age;

    private $jobPosition;

    private $country;

    private $city;

    private $githubLink;

    private $gitlabLink;

    private $telegramLink;

    private $skypeLink;

    private $personalLink;

    private $avatar;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getGender() : ?int
    {
        return $this->gender;
    }
    
    public function setGender(int $gender) : self
    {
        $this->gender = $gender;
        
        return $this;
    }

    public function getAge() : ?int
    {
        return $this->age;
    }
    
    public function setAge(int $age) : self
    {
        $this->age = $age;
        
        return $this;
    }

    public function getJobPosition(): ?JobPosition
    {
        return $this->jobPosition;
    }

    public function setJobPosition(JobPosition $jobPosition): self
    {
        $this->jobPosition = $jobPosition;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getGithubLink(): ?string
    {
        return $this->githubLink;
    }

    public function setGithubLink(string $githubLink): self
    {
        $this->githubLink = $githubLink;

        return $this;
    }

    public function getGitlabLink(): ?string
    {
        return $this->gitlabLink;
    }

    public function setGitlabLink(string $gitlabLink): self
    {
        $this->gitlabLink = $gitlabLink;

        return $this;
    }

    public function getTelegramLink(): ?string
    {
        return $this->telegramLink;
    }

    public function setTelegramLink(string $telegramLink): self
    {
        $this->telegramLink = $telegramLink;

        return $this;
    }

    public function getSkypeLink(): ?string
    {
        return $this->skypeLink;
    }

    public function setSkypeLink(string $skypeLink): self
    {
        $this->skypeLink = $skypeLink;

        return $this;
    }

    public function getPersonalLink(): ?string
    {
        return $this->personalLink;
    }

    public function setPersonalLink(string $personalLink): self
    {
        $this->personalLink = $personalLink;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }
}
