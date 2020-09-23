<?php

namespace App\Entity;

use App\Enum\TaskEnum;
use Doctrine\Common\Collections\ArrayCollection; 
use Doctrine\Common\Collections\Collection;

class Task
{
    private $id;
    
    private $name;
    
    private $description;
    
    private $status = TaskEnum::NEW;
    
    private $executor;
    
    private $team;
    
    private $project;

    private $author;

    private $rates;

    private $created_at;

    public function __construct()
    {
        $this->rates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getName(): ?string
    {
        return $this->name;
    }
    
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    public function getExecutor(): ?User
    {
        return $this->executor;
    }
    
    public function getTeam(): ?Team
    {
        return $this->team;
    }
    
    public function getProject(): ?Project
    {
        return $this->project;
    }
    
    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function getRates() : ?Collection
    {
        return $this->rates;
    }
    
    public function setName(string $name): self
    {
        $this->name = $name;
        
        return $this;
    }
    
    public function setDescription(string $description): self
    {
        $this->description = $description;
        
        return $this;
    }
    
    public function setExecutor(User $user): self
    {
        $this->executor = $user;
        
        return $this;
    }
    
    public function setTeam(Team $team): self
    {
        $this->team = $team;
        
        return $this;
    }
    
    public function setProject(Project $project): self
    {
        $this->project = $project;
        
        return $this;
    }
    
    public function setStatus(int $status): self
    {
        $this->status = $status;
        
        return $this;
    }

    public function setAuthor(User $user): self
    {
       $this->author = $user;
       
       return $this; 
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setCreatedAt($created_at): self
    {
       $this->created_at = $created_at;

       return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function toArrayForReport() : array
    {
    	$task = [
    		'name' => $this->getName(),
		    'author' => $this->getAuthor()->getUsername(),
		    'rates' => []
	    ];

	    /**
	     * @var RateInfo $rate
	     */
    	foreach ($this->getRates() as $rate) {
    		$skill = $rate->getSkill();
    		$task['rates'][] = [
    			'value' => $rate->getValue(),
			    'question' => $skill->getContent(),
		    ];
	    }

    	return $task;
    }
}
