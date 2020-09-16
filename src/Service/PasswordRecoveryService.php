<?php
namespace App\Service;

use App\Entity\PasswordRecovery;
use Doctrine\ORM\EntityManagerInterface;

class PasswordRecoveryService
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getOneByEmail(string $email): ?PasswordRecovery
    {
        $passwordRecovery = $this->entityManager
            ->getRepository(PasswordRecovery::class)
            ->findOneBy(['email' => $email])
        ;
        
        return $passwordRecovery;
    }
    
    public function updateTokenByEmail(string $email, string $token): ?PasswordRecovery
    {
        $passwordRecovery = $this->getOneByEmail($email);

        if (!$passwordRecovery) {
            $passwordRecovery = new PasswordRecovery();
        }

        $passwordRecovery->setEmail($email);
        $passwordRecovery->setToken($token);
        $passwordRecovery->setCreated(new \DateTime());

        $this->entityManager->persist($passwordRecovery);
        $this->entityManager->flush();

        return $passwordRecovery;
    }

    public function isTokenExpired(PasswordRecovery $passwordRecovery): bool
    {
        $created = $passwordRecovery->getCreated();
        $current = new \DateTime();
        $difference = $created->diff($current);

        if ($difference->format('%h') > '1') {
            return true;
        }

        return false;
    }
}

