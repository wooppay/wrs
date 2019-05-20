<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Entity\User;
use App\Enum\PermissionMarkEnum;
use Symfony\Component\Security\Core\Security;

class MarkExtension extends AbstractExtension
{
    private $security;
    
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    public function getFunctions()
    {
        return [
            new TwigFunction('is_granted_mark', [$this, 'isMarkGranted']),
        ];
    }
    
    public function isMarkGranted(User $user)
    {
        $permissions = (new \ReflectionClass(PermissionMarkEnum::class))->getConstants();
        
        return $this->security->isGranted($permissions, $user);
    }
}

