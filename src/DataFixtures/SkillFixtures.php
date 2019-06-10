<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Service\SkillService;
use App\Entity\Skill;
use App\Service\RoleService;

class SkillFixtures extends Fixture implements DependentFixtureInterface
{
    private $skillService;

    private $roleService;

    private const SOFT_ONE_PO = 'Is he good product owner?';

    private const SOFT_TWO_PO = 'Is product owner good at feedback?';

    private const SOFT_ONE_CUSTOMER = 'Is he good customer?';

    private const SOFT_TWO_CUSTOMER = 'Is customer good at describing task?';

    private const SOFT_ONE_TM = 'Is he good teamlead?';

    private const SOFT_TWO_TM = 'Is teamlead good at leading team?';

    private const TECHNICAL_ONE_TM = 'Is teamlead did code review';

    private const TECHNICAL_TWO_TM = 'Is teamlead run CI?';

    private const SOFT_ONE_DEV = 'Is he good developer?';

    private const SOFT_TWO_DEV = 'Is developer responsible?';

    private const TECHNICAL_ONE_DEV = 'Is developer good at code writing?';

    private const TECHNICAL_TWO_DEV = 'Is developer good at code style?';

    public function __construct(SkillService $skillService, RoleService $roleService)
    {
        $this->skillService = $skillService;
        $this->roleService = $roleService;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadPo();
        $this->loadCustomer();
        $this->loadTm();
        $this->loadDev();
    }

    private function loadPo()
    {
        $role = $this->roleService->byName(RoleFixtures::ROLE_PRODUCT_OWNER);
        $skill = (new Skill())->setContent(self::SOFT_ONE_PO)
        ->setRole($role);
        $this->skillService->createSoft($skill);

        $skill = (new Skill())->setContent(self::SOFT_TWO_PO)
        ->setRole($role);
        $this->skillService->createSoft($skill);
    }

    private function loadCustomer()
    {
        $role = $this->roleService->byName(RoleFixtures::ROLE_CUSTOMER);
        $skill = (new Skill())->setContent(self::SOFT_ONE_CUSTOMER)
        ->setRole($role);
        $this->skillService->createSoft($skill);

        $skill = (new Skill())->setContent(self::SOFT_TWO_CUSTOMER)
        ->setRole($role);
        $this->skillService->createSoft($skill);
    }

    private function loadTm()
    {
        $role = $this->roleService->byName(RoleFixtures::ROLE_TM);
        $skill = (new Skill())->setContent(self::SOFT_ONE_TM)
        ->setRole($role);
        $this->skillService->createSoft($skill);

        $skill = (new Skill())->setContent(self::SOFT_TWO_TM)
        ->setRole($role);
        $this->skillService->createSoft($skill);

        $skill = (new Skill())->setContent(self::TECHNICAL_ONE_TM)
        ->setRole($role);
        $this->skillService->createTechnical($skill);

        $skill = (new Skill())->setContent(self::TECHNICAL_TWO_TM)
        ->setRole($role);
        $this->skillService->createTechnical($skill);
    }

    private function loadDev()
    {
        $role = $this->roleService->byName(RoleFixtures::ROLE_DEVELOPER);
        $skill = (new Skill())->setContent(self::SOFT_ONE_DEV)
        ->setRole($role);
        $this->skillService->createSoft($skill);

        $skill = (new Skill())->setContent(self::SOFT_TWO_DEV)
        ->setRole($role);
        $this->skillService->createSoft($skill);

        $skill = (new Skill())->setContent(self::TECHNICAL_ONE_DEV)
        ->setRole($role);
        $this->skillService->createTechnical($skill);

        $skill = (new Skill())->setContent(self::TECHNICAL_TWO_DEV)
        ->setRole($role);
        $this->skillService->createTechnical($skill);
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
