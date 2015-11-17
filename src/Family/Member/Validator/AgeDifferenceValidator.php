<?php

namespace InheritanceCalculator\Family\Member\Validator;

use Carbon\Carbon;

class AgeDifferenceValidator
{
    public function validate($parentBirthDate, $sonBirthDate)
    {
        $parentBirth = Carbon::createFromFormat('Y-m-d', $parentBirthDate);

        $difference = $parentBirth->diffInYears(Carbon::createFromFormat('Y-m-d', $sonBirthDate));

        return ($difference >= 20);
    }
}