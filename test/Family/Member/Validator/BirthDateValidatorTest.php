<?php

namespace Test\Family\Member\Validator;

use InheritanceCalculator\Family\Member\Validator\BirthDateValidator;

class BirthDateValidatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var BirthDateValidator */
    private $sut;

    public function setup()
    {
        $this->sut = new BirthDateValidator();
    }

    public function testShouldBeInstantiated()
    {
        $this->assertInstanceOf(BirthDateValidator::class, $this->sut);
    }

    public function testShouldValidateIfDateIsCorrect()
    {
        $result = $this->sut->validate('2010-01-01');
        $this->assertTrue($result);
    }

    public function testShouldNotValidateIfDateIsNotCorrect()
    {
        $result = $this->sut->validate('2010-30-01');
        $this->assertFalse($result);
    }
}
