<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\Team;
use App\Enum\ActivityEnum;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    private $queryBuilder;
    private ActivityService $activityService;

    public function __construct(EntityManagerInterface $manager, ActivityService $activityService)
    {
        $this->queryBuilder = $manager->getConnection()->createQueryBuilder();
        $this->activityService = $activityService;
    }

    //TODO: REFACTOR IT, REMOVE SERVICE!!!
    public function addMemberToTeam(User $user, Team $team, User $initiator = null) : bool
    {
	    $this->queryBuilder->insert('member_team')->values([
		    'member_id' => ':member_id',
		    'team_id' => ':team_id',
	    ])->setParameters([
		    ':member_id' => $user->getId(),
		    ':team_id' => $team->getId(),
	    ])->execute();

	    $this->activityService->dispatchActivity(ActivityEnum::TEAM_JOIN, $user, $team, $initiator);

	    return true;
    }

	//TODO: REFACTOR IT, REMOVE SERVICE!!!
    public function deleteTeamMember(Team $team, User $member, User $initiator = null) : bool
    {
	    $this->queryBuilder
		    ->delete('member_team')
		    ->where('member_id = :member_id AND team_id = :team_id')
		    ->setParameters([
			    ':member_id' => $member->getId(),
			    ':team_id' => $team->getId(),
		    ])
		    ->execute();

	    $this->activityService->dispatchActivity(ActivityEnum::TEAM_LEFT, $member, $team, $initiator);

	    return true;
    }
}

