<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Role;
use App\Service\RoleService;

class RoleFixtures extends Fixture
{

    public const ROLE_TM = 'ROLE_TM';

    public const ROLE_CUSTOMER = 'ROLE_CUSTOMER';

    public const ROLE_DEVELOPER = 'ROLE_DEVELOPER';

    public const ROLE_PRODUCT_OWNER = 'ROLE_PRODUCT_OWNER';

    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public const ROLE_USER = 'ROLE_USER';
    
    private $roleService;
    
    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function load(ObjectManager $manager)
    {
        $roles = (new \ReflectionClass(self::class))->getConstants();
        $titles = (new RoleTitleFixtures())->getConstants();
        
        foreach ($roles as $role) {
            $entity = (new Role())->setName($role);
            $entity->setTitle($titles[$role]);
            $this->roleService->create($entity);
        }
    }
}
