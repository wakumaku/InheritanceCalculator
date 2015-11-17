<?php

namespace InheritanceCalculator\Family\Member;

use InheritanceCalculator\Family\Member\Validator\AgeDifferenceValidator;
use InheritanceCalculator\Family\Member\Validator\BirthDateValidator;
use InheritanceCalculator\Family\Member\Validator\NameValidator;

class MemberValidator
{
    /** @var NameValidator */
    private $nameValidator;
    /** @var BirthDateValidator */
    private $birthDateValidator;
    /** @var AgeDifferenceValidator */
    private $ageDifferenceValidator;

    public function __construct(
        NameValidator $nameValidator,
        BirthDateValidator $birthDateValidator,
        AgeDifferenceValidator $ageDifferenceValidator
    ) {
        $this->nameValidator = $nameValidator;
        $this->birthDateValidator = $birthDateValidator;
        $this->ageDifferenceValidator = $ageDifferenceValidator;
    }

    public function validateName($name)
    {
        return $this->nameValidator->validate($name);
    }

    public function validateBirthDate($birthDate)
    {
        return $this->birthDateValidator->validate($birthDate);
    }

    public function validateParentSonAges($parentBirth, $sonBirth)
    {
        return $this->ageDifferenceValidator->validate($parentBirth, $sonBirth);
    }
}
