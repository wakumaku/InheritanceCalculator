<?php

namespace InheritanceCalculator\Family\Member\Validator;

use Respect\Validation\Validator;

class NameValidator
{
    public function validate($name)
    {
        $nameValidation = Validator::alnum()
            ->length(3, 15);

        return $nameValidation->validate($name);
    }
}
