<?php

namespace Test\Family\Member\Validator;

use InheritanceCalculator\Family\Member\Validator\AgeDifferenceValidator;

class AgeDifferenceValidatorTest extends \PHPUnit_Framework_TestCase
{

    /** @var AgeDifferenceValidator */
    private $sut;

    public function setup()
    {
        $this->sut = new AgeDifferenceValidator();
    }

    public function testShouldBeInstantiated()
    {
        $this->assertInstanceOf(AgeDifferenceValidator::class, $this->sut);
    }

    public function testShouldReturnTrueIfParentIsGreaterThanSon()
    {
        $result = $this->sut->validate('2015-01-01', '1990-01-01');
        $this->assertTrue($result);
    }

    public function testShouldFailIfParentIsNotGreaterThanSon()
    {
        $result = $this->sut->validate('2000-01-01', '2015-01-01');
        $this->assertFalse($result);
    }

    public function testShouldFailIfParentIsGreaterThanSonLess20Yrs()
    {
        $result = $this->sut->validate('2000-01-01', '2015-01-01');
        $this->assertFalse($result);
    }
}
