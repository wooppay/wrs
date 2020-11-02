<?php
namespace App\Enum;

class TaskEnum
{
    public const NEW = 1;
    
    public const DONE = 2;
    
    public const DELETED = 0;

    public const STATUS_NAME_BY_DIGIT = [0 => 'DELETED', 1 => 'NEW', 2 => 'DONE'];
}

