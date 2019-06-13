<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Service\UserService;
use App\Service\TeamService;
use App\Service\ProjectService;
use App\Entity\Project;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    private $teamService;

    private $userService;

    private $projectService;

    private const PROJECT_ONE_TITLE = 'Project #1';

    private const PROJECT_ONE_DESCR = 'Project #1 description';

    private const PROJECT_TWO_TITLE = 'Project #2';

    private const PROJECT_TWO_DESC = 'Project #2 description';

    public function __construct(TeamService $teamService, UserService $userService, ProjectService $projectService) {
        $this->teamService = $teamService;
        $this->userService = $userService;
        $this->projectService = $projectService;
    }

    public function load(ObjectManager $manager)
    {
        $team = $this->teamService->byName(TeamFixtures::TEAM_ONE_TITLE);
        $customer = $this->userService->byEmail(UserFixtures::EMAIL_CUSTOMER);

        $project = (new Project())
            ->setName(self::PROJECT_ONE_TITLE)
            ->setTeam($team)
            ->setCustomer($customer)
            ->setDescription(self::PROJECT_ONE_DESCR)
        ;

        $this->projectService->create($project);

        $team = $this->teamService->byName(TeamFixtures::TEAM_TWO_TITLE);

        $project = (new Project())
            ->setName(self::PROJECT_TWO_TITLE)
            ->setTeam($team)
            ->setCustomer($customer)
            ->setDescription(self::PROJECT_TWO_DESC)
        ;

        $this->projectService->create($project);
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
            TeamMemberFixtures::class,
        ];
    }
}
