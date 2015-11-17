<?php

namespace InheritanceCalculator\Family\Member\Validator;

use Respect\Validation\Validator;

class BirthDateValidator
{
    public function validate($birthDate)
    {
        $birthDateValidation = Validator::date('Y-m-d');

        return $birthDateValidation->validate($birthDate);
    }
}
