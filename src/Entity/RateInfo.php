<?php

namespace App\Entity;

class RateInfo
{
    private $id;

    private $value;

    private $user;

    private $task;

    private $skill;

    private $author;

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function setTask(Task $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function setSkill(Skill $skill): self
    {
        $this->skill = $skill;

        return $this;
    }

    public function getValue() : ?int 
    {
        return $this->value;
    }

    public function getUser() : ?User
    {
        return $this->user;
    }

    public function getTask() : ?Task
    {
        return $this->task;
    }

    public function getSkill() : ?Skill
    {
        return $this->skill;
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function setAuthor(User $user) : self
    {
        $this->author = $user;

        return $this;
    }

    public function getAuthor(User $user) : User
    {
        return $this->author;
    }
}
