<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\ProfileInfo;
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

    public const EMAIL_ADMIN = 'admin@example.com';
    
    public const EMAIL_PO = 'po@example.com';
    
    public const EMAIL_CUSTOMER = 'customer@example.com';
    
    public const EMAIL_TM = 'tm@example.com';
    
    public const EMAIL_DEV = 'dev@example.com';
    
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

            $user = $this->createUser($manager, $roles, self::EMAIL_ADMIN);
            $this->addReference(self::EMAIL_ADMIN, $user);

            $roles = new ArrayCollection([
                $this->roleService->byName(RoleFixtures::ROLE_PRODUCT_OWNER)
            ]);
            
            $user = $this->createUser($manager, $roles, self::EMAIL_PO);
            $this->addReference(self::EMAIL_PO, $user);

            $roles = new ArrayCollection([
                $this->roleService->byName(RoleFixtures::ROLE_CUSTOMER)
            ]);
            
            $user = $this->createUser($manager, $roles, self::EMAIL_CUSTOMER);
            $this->addReference(self::EMAIL_CUSTOMER, $user);

            $roles = new ArrayCollection([
                $this->roleService->byName(RoleFixtures::ROLE_TM),
                $this->roleService->byName(RoleFixtures::ROLE_DEVELOPER),
            ]);
            
            $user = $this->createUser($manager, $roles, self::EMAIL_TM);
            $this->addReference(self::EMAIL_TM, $user);

            $roles = new ArrayCollection([
                $this->roleService->byName(RoleFixtures::ROLE_DEVELOPER)
            ]);
            
            $user = $this->createUser($manager, $roles, self::EMAIL_DEV);
            $this->addReference(self::EMAIL_DEV, $user);
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
            ->setEmail($email)
        ;

        $manager->persist($user);
        $manager->flush();

        return $user;
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
