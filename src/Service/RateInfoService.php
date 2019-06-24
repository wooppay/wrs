<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Permission;
use Doctrine\Common\Collections\Collection;
use App\Entity\RateInfo;
use App\Entity\Task;
use App\Entity\User;

class RateInfoService
{
    private $entityManager;

    private $skillService;

    private $userService;
    
    public function __construct(EntityManagerInterface $manager, SkillService $skillService, UserService $userService)
    {
        $this->entityManager = $manager;
        $this->skillService = $skillService;
        $this->userService = $userService;
    }

    public function prepareData(array $pack, Task $task, User $author) : array
    {
        $res = [];

        foreach ($pack as $key => $item) {
            $parts = explode('_', $key);

            $skillId = (int) $parts[1];
            $sigment = $parts[2];

            switch ($sigment) {
                case 'value' :
                    $res[$skillId]['value'] = $item;
                    break;
                case 'skill' :
                    $res[$skillId]['skill'] = $this->skillService->byId($item);
                    break;
                case 'user' :
                    $res[$skillId]['user'] = $this->userService->byId($item);
                    break;
            }

            $res[$skillId]['task'] = $task;
            $res[$skillId]['author'] = $author;

        }

        sort($res);

        return $res;
    }

    public function createByCheckList(array $data) : void
    {
        foreach ($data as $item) {
            $rateInfo = (new RateInfo())
                ->setValue($item['value'])
                ->setSkill($item['skill'])
                ->setUser($item['user'])
                ->setTask($item['task'])
                ->setAuthor($item['author'])
            ;
            
            // todo transaction
            $this->create($rateInfo);
        }
    }
    
    public function create(RateInfo $rateInfo) : RateInfo
    {
        $this->entityManager->persist($rateInfo);
        $this->entityManager->flush();
        
        return $rateInfo;
    }
}

