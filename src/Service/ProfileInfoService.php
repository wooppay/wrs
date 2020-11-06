<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ProfileInfo;
use App\Entity\User;

class ProfileInfoService
{
    private $entityManager;

    private $avatarDirectory;
    
    public function __construct(string $avatarDirectory, EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
        $this->avatarDirectory = $avatarDirectory;
    }
    
    public function save(ProfileInfo $profileInfo) : ProfileInfo
    {
        $this->entityManager->persist($profileInfo);
        $this->entityManager->flush();
        
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