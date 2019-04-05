<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;
use App\Entity\Team;

class ContainsLeaderInTeam extends Constraint
{
    public $message = 'Leader has already exist in current team';
    
    protected $team;
    
    protected $is_checked;
    
    public function __construct($options)
    {
        if (!array_key_exists('team', $options)) {
            throw new MissingOptionsException('team option was missed', $options);
        }
        
        if (!$options['team'] instanceof Team) {
            throw new MissingOptionsException('team option is not instance of team entity', $options);
        }
        
        if (!array_key_exists('is_checked', $options)) {
            throw new MissingOptionsException('is_checked option was missed', $options);
        }
        
        $this->team = $options['team'];
        $this->is_checked = $options['is_checked'];
    }
    
    public function getTeam() : Team
    {
        return $this->team;
    }
    
    public function isChecked() : bool
    {
        return $this->is_checked;
    }
}

