<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Service\TeamService;
use App\Service\UserService;
use App\Entity\Team;

class TeamFixtures extends Fixture implements DependentFixtureInterface
{
    private const TEAM_ONE_TITLE = 'Team #1';

    private const TEAM_ONE_DESC = 'Team #1 description';

    private const TEAM_TWO_TITLE = 'Team #2';

    private const TEAM_TWO_DESC = 'Team #2 description';

    private $teamService;

    private $userService;

    public function __construct(TeamService $teamService, UserService $userService) {
        $this->teamService = $teamService;
        $this->userService = $userService;
    }

    public function load(ObjectManager $manager)
    {
        $author = $this->userService->byEmail(UserFixtures::EMAIL_PO);

        $team = new Team();
        $team
            ->setName(self::TEAM_ONE_TITLE)
            ->setDescription(self::TEAM_ONE_DESC)
            ->setOwner($author)
        ;

        $this->teamService->create($team);

        $team = new Team();
        $team
            ->setName(self::TEAM_TWO_TITLE)
            ->setDescription(self::TEAM_TWO_DESC)
            ->setOwner($author)
        ;

        $this->teamService->create($team);

    }


    public function getDependencies()
    {
        return [
            PermissionFixtures::class,
            RoleFixtures::class,
            RolePermissionFixtures::class,
            UserFixtures::class,
            SkillFixtures::class,
        ];
    }
}
