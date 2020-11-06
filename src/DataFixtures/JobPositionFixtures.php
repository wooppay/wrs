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
        $entity = new JobPosition();
        $entity->setName(self::TEAM_LEAD);

        $jobPosition = $this->jobPositionService->flush($entity);
        $this->addReference(self::TEAM_LEAD, $jobPosition);

        $entity = new JobPosition();
        $entity->setName(self::DEVELOPER);

        $jobPosition = $this->jobPositionService->flush($entity);
        $this->addReference(self::DEVELOPER, $jobPosition);

        $entity = new JobPosition();
        $entity->setName(self::QUALITY_ASSURANCE);

        $jobPosition = $this->jobPositionService->flush($entity);
        $this->addReference(self::QUALITY_ASSURANCE, $jobPosition);

        $entity = new JobPosition();
        $entity->setName(self::PRODUCT_OWNER);

        $jobPosition = $this->jobPositionService->flush($entity);
        $this->addReference(self::PRODUCT_OWNER, $jobPosition);

        $entity = new JobPosition();
        $entity->setName(self::PROJECT_MANAGER);

        $jobPosition = $this->jobPositionService->flush($entity);
        $this->addReference(self::PROJECT_MANAGER, $jobPosition);
    }
}
