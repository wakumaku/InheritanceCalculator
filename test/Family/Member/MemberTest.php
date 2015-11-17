<?php

namespace Test\Family\Member;

use InheritanceCalculator\Family\Member\Member;
use InheritanceCalculator\Goods\EstateGoods;
use InheritanceCalculator\Goods\GoodsInterface;
use InheritanceCalculator\Goods\LandGoods;
use InheritanceCalculator\Goods\MoneyGoods;
use Prophecy\Argument;

class MemberTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Member */
    private $sut;
    private $memberName = "Standard Member";
    private $birthDate = "2000-01-01";
    /** @var MoneyGoods $moneyGoodsMock */
    private $moneyGoodsMock;
    /** @var LandGoods $landGoodsMock */
    private $landGoodsMock;
    /** @var EstateGoods $estateGoodsMock */
    private $estateGoodsMock;

    public function setup()
    {
        $this->sut = $this->generateMember($this->memberName, $this->birthDate);
    }

    public function testShouldReturnCorrectIdentifier()
    {
        $hash = $this->sut->birthDate().$this->sut->name();

        $this->assertEquals($hash, $this->sut->identifier());
    }

    public function testShouldReturnName()
    {
        $this->assertEquals($this->memberName, $this->sut->name());
    }

    public function testShouldReturnBirthDate()
    {
        $this->assertEquals($this->birthDate, $this->sut->birthDate());
    }

    public function testShouldReturnCorrectAge()
    {
        $birthDate = date_create_from_format('Y-m-d', $this->birthDate);
        $today = date_create_from_format('Y-m-d', date('Y-m-d'));
        $yearsOld = $birthDate->diff($today)->y;

        $this->assertEquals($yearsOld, $this->sut->age());
    }

    public function testShouldReturnCorrectAgeWhenEpochIsModified()
    {
        $this->sut->setDateEpoch('2010-01-01');

        $birthDate = date_create_from_format('Y-m-d', $this->birthDate);
        $today = date_create_from_format('Y-m-d', date('2010-01-01'));
        $yearsOld = $birthDate->diff($today)->y;

        $this->assertEquals($yearsOld, $this->sut->age());
    }

    public function testShouldBeInstantiated()
    {
        $this->assertInstanceOf(Member::class, $this->sut);
    }

    public function testShouldBeDeadIfAgeIsGreaterThanMaximum()
    {
        $sut = $this->generateMemberOlderThanMaximum();
        $this->assertTrue($sut->isDead());
    }

    public function testShouldBeAliveIfAgeIsLessThanMaximum()
    {
        $sut = $this->generateMemberYounger();
        $this->assertFalse($sut->isDead());
    }

    public function testShouldAddSonsInAnyOrderAndGetThemOrderedAndHierarchied()
    {
        $this->sut->addSon($this->generateMember('X', '2013-01-01'));
        $this->sut->addSon($this->generateMember('Y', '2007-01-01'));
        $this->sut->addSon($this->generateMember('Z', '1990-01-01'));

        // Assert number of sons added
        $this->assertEquals(3, count($this->sut->sons()));

        /** @var Member $olderSon */
        $olderSon = array_values(array_slice($this->sut->sons(), 0, 1))[0];
        /** @var Member $midSon */
        $midSon = array_values(array_slice($this->sut->sons(), 1, 1))[0];
        /** @var Member $youngerSon */
        $youngerSon = array_values(array_slice($this->sut->sons(), 2, 1))[0];

        // Assert that there were ordered by birthdate
        $this->assertEquals('1990-01-01Z', $olderSon->identifier());
        $this->assertEquals('2007-01-01Y', $midSon->identifier());
        $this->assertEquals('2013-01-01X', $youngerSon->identifier());

        // Assert Correct Hierarchy Order
        $this->assertEquals(1, $olderSon->hierarchyOrder());
        $this->assertEquals(2, $midSon->hierarchyOrder());
        $this->assertEquals(3, $youngerSon->hierarchyOrder());

    }

    public function testShouldAddSomeGoods()
    {
        $moneyGoodsMock = $this->prophesize(MoneyGoods::class);
        $moneyGoodsMock->amount()->willReturn(4);
        $moneyGoodsMock->type()->willReturn(GoodsInterface::MONEY);

        $this->sut->updateGood($moneyGoodsMock->reveal());

        $this->assertEquals(4, $this->sut->getMoneyGoods()->amount());

        $landGoodsMock = $this->prophesize(LandGoods::class);
        $landGoodsMock->amount()->willReturn(8);
        $landGoodsMock->type()->willReturn(GoodsInterface::LAND);

        $this->sut->updateGood($landGoodsMock->reveal());

        $this->assertEquals(8, $this->sut->getLandGoods()->amount());

        $estateGoodsMock = $this->prophesize(EstateGoods::class);
        $estateGoodsMock->amount()->willReturn(12);
        $estateGoodsMock->type()->willReturn(GoodsInterface::ESTATE);

        $this->sut->updateGood($estateGoodsMock->reveal());

        $this->assertEquals(12, $this->sut->getEstateGoods()->amount());
    }

    private function generateMemberOlderThanMaximum()
    {
        $birthYear = date('Y') - (Member::AGE_WHEN_DIES + 1);
        return $this->generateMember("DeadMember", date("$birthYear-m-d"));
    }

    private function generateMember($name, $birthDate)
    {
        $this->moneyGoodsMock = $this->prophesize(MoneyGoods::class);
        $this->moneyGoodsMock->amount()->willReturn(3);
        $this->moneyGoodsMock->increaseAmount(Argument::any());
        $this->moneyGoodsMock->type()->willReturn(GoodsInterface::MONEY);

        $this->landGoodsMock = $this->prophesize(LandGoods::class);
        $this->landGoodsMock->amount()->willReturn(6);
        $this->landGoodsMock->increaseAmount(Argument::any());
        $this->landGoodsMock->type()->willReturn(GoodsInterface::LAND);

        $this->estateGoodsMock = $this->prophesize(EstateGoods::class);
        $this->estateGoodsMock->amount()->willReturn(9);
        $this->estateGoodsMock->increaseAmount(Argument::any());
        $this->estateGoodsMock->type()->willReturn(GoodsInterface::ESTATE);

        $member = new Member(
            $name,
            $birthDate,
            $this->moneyGoodsMock->reveal(),
            $this->landGoodsMock->reveal(),
            $this->estateGoodsMock->reveal()
        );

        $member->setDateEpoch(date('Y-m-d'));

        return $member;
    }

    private function generateMemberYounger()
    {
        $birthYear = date('Y') - 30;
        return $this->generateMember("AliveMember", date("$birthYear-m-d"));
    }
}
