<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActivityRepository")
 */
class Activity
{
    private $id;

    private int $type;

    private \DateTime $date;

    private ?string $message;

    private ?Task $task;

    private ?Team $team;

    private ?User $user;

    private ?User $initiator;

    private ?JobPosition $jobPosition;

    private ?int $negativeRates;

    private ?int $positiveRates;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setTask(Task $task): self
    {
        $this->task = $task;
        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTeam(Team $team): self
    {
        $this->team = $team;
        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
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

    public function setDate(\DateTime $dateTime): self
    {
        $this->date = $dateTime;
        return $this;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function getPrettyDate(): string
    {
        return $this->date->format('d M. Y \a\t H:i');
    }

    public function setInitiator(User $user): self
    {
        $this->initiator = $user;
        return $this;
    }

    public function getInitiator(): User
    {
        return $this->initiator;
    }

    public function setPositiveRatesCount(int $count) : self
    {
        $this->positiveRates = $count;
        return $this;
    }

    public function setNegativeRatesCount(int $count) : self
    {
        $this->negativeRates = $count;
        return $this;
    }

    public function getPositiveRatesCount() : ?int
    {
        return $this->positiveRates;
    }

    public function getNegativeRatesCount() : ?int
    {
        return $this->negativeRates;
    }
}
