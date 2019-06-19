<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Service\TeamService;
use App\Service\UserService;
use App\Service\ProjectService;
use App\Service\TaskService;
use App\Entity\Task;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    private $userService;

    private $projectService;

    private $taskService;

    private const TASK_DEV_ONE_TITLE = 'Task #1 For Dev';

    private const TASK_DEV_ONE_DESCRIPTION = 'Task #1 Description';

    private const TASK_DEV_TWO_TITLE = 'Task #2 For Dev';

    private const TASK_DEV_TWO_DESCRIPTION = 'Task #2 Description';

    private const TASK_TM_ONE_TITLE = 'Task #1 For TM';

    private const TASK_TM_ONE_DESCRIPTION = 'Task #1 Description';

    private const TASK_TM_TWO_TITLE = 'Task #2 TM';

    private const TASK_TM_TWO_DESCRIPTION = 'Task #2 Description';

    public function __construct(UserService $userService, ProjectService $projectService, TaskService $taskService)
    {
        $this->userService = $userService;
        $this->projectService = $projectService;
        $this->taskService = $taskService;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadDev();
        $this->loadTm();
    }

    private function loadDev()
    {
        $user = $this->userService->byEmail(UserFixtures::EMAIL_DEV);
        $project = $this->projectService->byName(ProjectFixtures::PROJECT_ONE_TITLE);
        $team = $project->getTeam();
        $author = $this->userService->byEmail(UserFixtures::EMAIL_PO);
        
        $task = (new Task())
            ->setName(self::TASK_DEV_ONE_TITLE)
            ->setDescription(self::TASK_DEV_ONE_DESCRIPTION)
            ->setExecutor($user)
            ->setProject($project)
            ->setTeam($team)
            ->setAuthor($author)
        ;

        $this->taskService->create($task);

        $task = (new Task())
            ->setName(self::TASK_DEV_TWO_TITLE)
            ->setDescription(self::TASK_DEV_TWO_DESCRIPTION)
            ->setExecutor($user)
            ->setProject($project)
            ->setTeam($team)
            ->setAuthor($author)
        ;

        $this->taskService->create($task);
    }

    private function loadTm()
    {
        $user = $this->userService->byEmail(UserFixtures::EMAIL_TM);
        $project = $this->projectService->byName(ProjectFixtures::PROJECT_ONE_TITLE);
        $team = $project->getTeam();
        $author = $this->userService->byEmail(UserFixtures::EMAIL_PO);
        
        $task = (new Task())
            ->setName(self::TASK_TM_ONE_TITLE)
            ->setDescription(self::TASK_TM_ONE_DESCRIPTION)
            ->setExecutor($user)
            ->setProject($project)
            ->setTeam($team)
            ->setAuthor($author)
        ;

        $this->taskService->create($task);

        $task = (new Task())
            ->setName(self::TASK_TM_TWO_TITLE)
            ->setDescription(self::TASK_TM_TWO_DESCRIPTION)
            ->setExecutor($user)
            ->setProject($project)
            ->setTeam($team)
            ->setAuthor($author)
        ;

        $this->taskService->create($task);
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
            ProjectFixtures::class,
        ];
    }
}
