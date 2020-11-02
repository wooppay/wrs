<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\JobPosition;
use App\Service\JobPositionService;

class JobPositionFixtures extends Fixture
{
    private $jobPositionService;

    public const TEAM_LEAD = 'Team Lead';

    public const DEVELOPER = 'Developer';

    public const QUALITY_ASSURANCE = 'Quality Assurance';

    public const PRODUCT_OWNER = 'Product Owner';

    public const PROJECT_MANAGER = 'Project Manager';
    
    public function __construct(JobPositionService $jobPositionService)
    {
        $this->jobPositionService = $jobPositionService;
    }
    
    public function load(ObjectManager $manager)
    {
        $jobPositions = (new \ReflectionClass(self::class))->getConstants();

        foreach ($jobPositions as $position) {
            $entity = new JobPosition();
            $entity->setName($position);

            $this->jobPositionService->flush($entity);
        }
    }
}
