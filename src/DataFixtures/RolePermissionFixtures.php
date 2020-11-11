<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Service\SecurityService;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Service\RoleService;
use App\Service\PermissionService;
use App\Enum\PermissionEnum;
use App\Enum\PermissionMarkEnum;

class RolePermissionFixtures extends Fixture implements DependentFixtureInterface
{
    private $securityService;
    
    private $roleService;
    
    private $permissionService;

    private $adminPermissions = [
        PermissionEnum::CAN_CREATE_SOFT_SKILL,
        PermissionEnum::CAN_UPDATE_SOFT_SKILL,
        PermissionEnum::CAN_DELETE_SOFT_SKILL,
        PermissionEnum::CAN_CREATE_TECHNICAL_SKILL,
        PermissionEnum::CAN_UPDATE_TECHNICAL_SKILL,
        PermissionEnum::CAN_UPDATE_ROLE,
        PermissionEnum::CAN_BE_ADMIN,
        PermissionEnum::CAN_CREATE_JOB_POSITION,
        PermissionEnum::CAN_UPDATE_JOB_POSITION,
        PermissionEnum::CAN_DELETE_JOB_POSITION,
        PermissionEnum::CAN_VIEW_MY_PROFILE,
        PermissionEnum::CAN_EDIT_MY_PROFILE,
        PermissionEnum::CAN_CREATE_COUNTRY,
        PermissionEnum::CAN_UPDATE_COUNTRY,
        PermissionEnum::CAN_DELETE_COUNTRY,
        PermissionEnum::CAN_CREATE_CITY,
        PermissionEnum::CAN_UPDATE_CITY,
        PermissionEnum::CAN_DELETE_CITY,
        PermissionEnum::CAN_CREATE_OWN_GOAL,
        PermissionEnum::CAN_UPDATE_OWN_GOAL,
        PermissionEnum::CAN_DELETE_OWN_GOAL,
    ];

    private $poPermissions = [
        PermissionEnum::CAN_CREATE_TEAM,
        PermissionEnum::CAN_ADD_MEMBER_TO_TEAM,
        PermissionEnum::CAN_DELETE_MEMBER_FROM_TEAM,
        PermissionEnum::CAN_CREATE_PROJECT,
        PermissionEnum::CAN_SEE_MANAGE_TASK,
        PermissionEnum::CAN_CREATE_TASK,
        PermissionEnum::CAN_SEE_MANAGE_TEAM,
        PermissionEnum::CAN_SEE_MANAGE_PROJECT,
        PermissionEnum::CAN_SEE_ALL_TASKS,
        PermissionEnum::CAN_BE_PRODUCT_OWNER,
        PermissionEnum::CAN_UPDATE_TASK,
        PermissionEnum::CAN_DELETE_TASK,
        PermissionEnum::CAN_SEE_DETAIL_TASK,
        PermissionEnum::CAN_SEE_MAY_CREATED_TASKS,
	    PermissionEnum::CAN_GENERATE_MARK_REPORT,
        PermissionMarkEnum::CAN_MARK_TEAM_LEAD,
        PermissionMarkEnum::CAN_MARK_DEVELOPER,
        PermissionMarkEnum::CAN_MARK_CUSTOMER,
        PermissionEnum::CAN_VIEW_MY_PROFILE,
        PermissionEnum::CAN_EDIT_MY_PROFILE,
        PermissionEnum::CAN_CREATE_OWN_GOAL,
        PermissionEnum::CAN_UPDATE_OWN_GOAL,
        PermissionEnum::CAN_DELETE_OWN_GOAL,
    ];

    private $customerPermissions = [
        PermissionEnum::CAN_SEE_ALL_MY_PROJECT_TASKS_EXCEPT_ME,
        PermissionEnum::CAN_SEE_ALL_MY_PROJECT_TASKS,
        PermissionEnum::CAN_BE_CUSTOMER,
        PermissionEnum::CAN_SEE_DETAIL_TASK,
        PermissionMarkEnum::CAN_MARK_TEAM_LEAD,
        PermissionMarkEnum::CAN_MARK_DEVELOPER,
        PermissionMarkEnum::CAN_MARK_PRODUCT_OWNER,
        PermissionEnum::CAN_VIEW_MY_PROFILE,
        PermissionEnum::CAN_EDIT_MY_PROFILE,
        PermissionEnum::CAN_CREATE_OWN_GOAL,
        PermissionEnum::CAN_UPDATE_OWN_GOAL,
        PermissionEnum::CAN_DELETE_OWN_GOAL,
    ];

    private $tmPermissions = [
        PermissionEnum::CAN_SEE_TASKS_ASSIGNED_TO_ME,
        PermissionEnum::CAN_SEE_MY_TEAM_TASKS,
        PermissionEnum::CAN_BE_TEAMLEAD,
        PermissionEnum::CAN_SEE_ALL_MEMBERS_TASKS_FROM_TEAMS_WHERE_I_PARTICIPATED,
	    PermissionEnum::CAN_GENERATE_MARK_REPORT,
        PermissionMarkEnum::CAN_MARK_DEVELOPER,
        PermissionMarkEnum::CAN_MARK_CUSTOMER,
        PermissionMarkEnum::CAN_MARK_PRODUCT_OWNER,
        PermissionEnum::CAN_SEE_DETAIL_TASK,
        PermissionEnum::CAN_VIEW_MY_PROFILE,
        PermissionEnum::CAN_EDIT_MY_PROFILE,
        PermissionEnum::CAN_CREATE_OWN_GOAL,
        PermissionEnum::CAN_UPDATE_OWN_GOAL,
        PermissionEnum::CAN_DELETE_OWN_GOAL,
    ];

    private $devPermissions = [
        PermissionEnum::CAN_SEE_TASKS_ASSIGNED_TO_ME,
        PermissionEnum::CAN_BE_DEVELOPER,
        PermissionMarkEnum::CAN_MARK_TEAM_LEAD,
        PermissionMarkEnum::CAN_MARK_CUSTOMER,
        PermissionMarkEnum::CAN_MARK_PRODUCT_OWNER,
        PermissionEnum::CAN_SEE_DETAIL_TASK,
        PermissionEnum::CAN_VIEW_MY_PROFILE,
        PermissionEnum::CAN_EDIT_MY_PROFILE,
        PermissionEnum::CAN_CREATE_OWN_GOAL,
        PermissionEnum::CAN_UPDATE_OWN_GOAL,
        PermissionEnum::CAN_DELETE_OWN_GOAL,
    ];
    
    public function __construct(SecurityService $securityService, RoleService $roleService, PermissionService $permissionService)
    { 
        $this->securityService = $securityService;
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
    }
    
    public function load(ObjectManager $manager)
    {
        $this->loadAdmin();
        $this->loadProductOwner();
        $this->loadCustomer();
        $this->loadTeamlead();
        $this->loadDeveloper();
    }
    
    private function loadAdmin()
    {
        $role = $this->roleService->byName(RoleFixtures::ROLE_ADMIN);

        foreach ($this->adminPermissions as $name) {
            $permission = $this->permissionService->byName($name);
            $this->securityService->setPermissionToRole($role, $permission);
        }
    }
    
    private function loadProductOwner()
    {
        $role = $this->roleService->byName(RoleFixtures::ROLE_PRODUCT_OWNER);
        
        foreach ($this->poPermissions as $name) {
            $permission = $this->permissionService->byName($name);
            $this->securityService->setPermissionToRole($role, $permission);
        }
    }
    
    private function loadCustomer()
    {
        $role = $this->roleService->byName(RoleFixtures::ROLE_CUSTOMER);

        foreach ($this->customerPermissions as $name) {
            $permission = $this->permissionService->byName($name);
            $this->securityService->setPermissionToRole($role, $permission);
        }
    }
    
    private function loadTeamlead()
    {
        $role = $this->roleService->byName(RoleFixtures::ROLE_TM);

        foreach ($this->tmPermissions as $name) {
            $permission = $this->permissionService->byName($name);
            $this->securityService->setPermissionToRole($role, $permission);
        }
    }
    
    private function loadDeveloper()
    {
        $role = $this->roleService->byName(RoleFixtures::ROLE_DEVELOPER);

        foreach ($this->devPermissions as $name) {
            $permission = $this->permissionService->byName($name);
            $this->securityService->setPermissionToRole($role, $permission);
        }
    }
    
    public function getDependencies()
    {
        return [
            RoleFixtures::class,
            PermissionFixtures::class,
        ];
    }
}
