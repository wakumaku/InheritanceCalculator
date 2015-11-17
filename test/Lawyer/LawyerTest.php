<?php

namespace Test\Lawyer\Lawyer;

use InheritanceCalculator\Family\FamilyFactory;
use InheritanceCalculator\Lawyer\Lawyer;

class LawyerTest extends \PHPUnit_Framework_TestCase
{
    /** @var Lawyer */
    private $sut;

    public function setup()
    {
        $familyFactory = new FamilyFactory();
        $this->sut = new Lawyer($familyFactory->build(), date('Y-m-d'));
    }

    public function testShouldBeInstantiated()
    {
        $this->assertInstanceOf(Lawyer::class, $this->sut);
    }

    public function testShouldApplyGoodsHeritance()
    {
        $this->sut->calculateInheritance();
    }
}
