<?php
namespace App\Service;

use App\Entity\Team;
use App\Enum\RoleEnum;

class TeamService
{
    public function hasLeadearInTeam(Team $team) : bool
    {
        $members = $team->getMembers();
        
        $roles = [];
        
        foreach ($members as $member) {
            $roles = array_merge($roles, $member->getRoles());
        }
        
        array_unique($roles);
        
        return in_array(RoleEnum::TEAM_LEAD, $roles);
    }
}

