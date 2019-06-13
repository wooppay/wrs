<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use App\Service\SecurityService;
use Doctrine\Common\Collections\ArrayCollection;
use App\Enum\UserEnum;
use App\Service\RoleService;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private const PASS = 'qwerty';

    private $passwordEncoder;

    private $security;

    private $roleService;

    private const ROLES = [
        RoleFixtures::ROLE_ADMIN,
        RoleFixtures::ROLE_PRODUCT_OWNER,
        RoleFixtures::ROLE_TM,
        RoleFixtures::ROLE_DEVELOPER,
    ];

    private const EMAIL_ADMIN = 'admin@example.com';
    
    public const EMAIL_PO = 'po@example.com';
    
    private const EMAIL_CUSTOMER = 'customer@example.com';
    
    private const EMAIL_TM = 'tm@example.com';
    
    private const EMAIL_DEV = 'dev@example.com';
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, SecurityService $security, RoleService $roleService)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->security = $security;
        $this->roleService = $roleService;
    }

    public function load(ObjectManager $manager)
    {
            $roles = new ArrayCollection([
                $this->roleService->byName(RoleFixtures::ROLE_ADMIN)
            ]);

            $this->createUser($manager, $roles, self::EMAIL_ADMIN);

            $roles = new ArrayCollection([
                $this->roleService->byName(RoleFixtures::ROLE_PRODUCT_OWNER)
            ]);
            
            $this->createUser($manager, $roles, self::EMAIL_PO);

            $roles = new ArrayCollection([
                $this->roleService->byName(RoleFixtures::ROLE_CUSTOMER)
            ]);
            
            $this->createUser($manager, $roles, self::EMAIL_CUSTOMER);

            $roles = new ArrayCollection([
                $this->roleService->byName(RoleFixtures::ROLE_TM)
            ]);
            
            $this->createUser($manager, $roles, self::EMAIL_TM);

            $roles = new ArrayCollection([
                $this->roleService->byName(RoleFixtures::ROLE_DEVELOPER)
            ]);
            
            $this->createUser($manager, $roles, self::EMAIL_DEV);
    }

    private function createUser(ObjectManager $manager, ArrayCollection $roles, string $email)
    {
        $user = new User();

        $user
        ->setPassword($this->passwordEncoder->encodePassword(
            $user,
            self::PASS
        ))
        ->setRoles($roles)
        ->setStatus(UserEnum::APPROVED)
        ->setEmail($email);
        ;

        $manager->persist($user);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PermissionFixtures::class,
            RoleFixtures::class,
            RolePermissionFixtures::class,
        ];
    }
}
