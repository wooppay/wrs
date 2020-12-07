<?php

namespace App\Service;

use App\Enum\ActivityEnum;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ProfileInfo;
use App\Entity\User;

class ProfileInfoService
{
    private EntityManagerInterface $entityManager;

    private $avatarDirectory;

    private ActivityService $activityService;
    
    public function __construct(string $avatarDirectory, EntityManagerInterface $manager, ActivityService $activityService)
    {
        $this->entityManager = $manager;
        $this->avatarDirectory = $avatarDirectory;
        $this->activityService = $activityService;
    }
    
    public function save(ProfileInfo $profileInfo) : ProfileInfo
    {
        $this->entityManager->persist($profileInfo);
        $this->entityManager->flush();

	    $this->activityService->dispatchActivity(ActivityEnum::CHANGE_JOB_POSITION, $profileInfo->getUser(), $profileInfo->getJobPosition());
        
        return $profileInfo;
    }

    public function changeAvatar(User $user, UploadedFile $avatar): ProfileInfo
    {
        $profileInfo = $user->getProfileInfo();
        $avatarFilename = $user->getEmail().'-avatar.'.$avatar->guessExtension();

        $avatar->move(
            $this->avatarDirectory,
            $avatarFilename
        );

        $profileInfo->setAvatar($avatarFilename);
        $this->save($profileInfo);

        return $profileInfo;
    }
}