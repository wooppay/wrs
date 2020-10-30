<?php

namespace App\DataFixtures;

class RoleTitleFixtures
{
    public const ROLE_TM = 'Team Lead';

    public const ROLE_CUSTOMER = 'Customer';

    public const ROLE_DEVELOPER = 'Developer';

    public const ROLE_PRODUCT_OWNER = 'Product Owner';

    public const ROLE_ADMIN = 'Administrator';

    public const ROLE_USER = 'User';

    public function getConstants()
    {
        return (new \ReflectionClass(self::class))->getConstants();
    }
}