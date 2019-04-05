<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use App\Service\TeamService;
use Doctrine\ORM\EntityManagerInterface;

class ContainsLeaderInTeamValidator extends ConstraintValidator
{
    private $teamService;
    
    private $entityManager;
    
    public function __construct(EntityManagerInterface $manager, TeamService $teamService)
    {
        $this->teamService = $teamService;
        $this->entityManager = $manager;
    }
    
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ContainsLeaderInTeam) {
            throw new UnexpectedTypeException($constraint, ContainsLeaderInTeam::class);
        }
        
        if ($constraint->isChecked() && $this->teamService->hasLeadearInTeam($constraint->getTeam())) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
        
    }
}
