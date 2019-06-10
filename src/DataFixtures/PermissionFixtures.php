<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Service\PermissionService;
use App\Entity\Permission;
use App\Enum\PermissionEnum;
use App\Enum\PermissionMarkEnum;

class PermissionFixtures extends Fixture
{
    private $permissionService;
    
    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }
    
    public function load(ObjectManager $manager)
    {
        $permissions = (new \ReflectionClass(PermissionEnum::class))->getConstants();
        $permissions = array_merge($permissions, (new \ReflectionClass(PermissionMarkEnum::class))->getConstants());
        
        foreach ($permissions as $permission) {
            $entity = (new Permission())->setName($permission);
            $this->permissionService->create($entity);
        }
    }
}
