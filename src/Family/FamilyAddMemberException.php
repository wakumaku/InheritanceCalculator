<?php

namespace InheritanceCalculator\Family;

class FamilyAddMemberException extends \Exception
{
    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);
    }
}
