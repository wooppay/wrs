<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Service\ProductService;
use App\Service\UserService;
use App\Service\TeamService;
use App\Entity\Team;

class TeamMemberFixtures extends Fixture implements DependentFixtureInterface
{
    private $teamService;

    private $userService;

    private $productService;

    public function __construct(TeamService $teamService, UserService $userService, ProductService $productService) {
        $this->teamService = $teamService;
        $this->userService = $userService;
        $this->productService = $productService;
    }

    public function load(ObjectManager $manager)
    {
        $team = $this->teamService->byName(TeamFixtures::TEAM_ONE_TITLE);
        $member = $this->userService->byEmail(UserFixtures::EMAIL_DEV);
        $this->productService->addMemberToTeam($member, $team);
        $member = $this->userService->byEmail(UserFixtures::EMAIL_TM);
        $this->productService->addMemberToTeam($member, $team);

        $team = $this->teamService->byName(TeamFixtures::TEAM_TWO_TITLE);
        $member = $this->userService->byEmail(UserFixtures::EMAIL_DEV);
        $this->productService->addMemberToTeam($member, $team);
        $member = $this->userService->byEmail(UserFixtures::EMAIL_TM);
        $this->productService->addMemberToTeam($member, $team);

    }

    public function getDependencies()
    {
        return [
            PermissionFixtures::class,
            RoleFixtures::class,
            RolePermissionFixtures::class,
            UserFixtures::class,
            SkillFixtures::class,
            TeamFixtures::class,
        ];
    }
}
