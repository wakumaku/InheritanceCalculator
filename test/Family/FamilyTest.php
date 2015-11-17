<?php

namespace Test\Family\Family;

use InheritanceCalculator\Family\Family;
use InheritanceCalculator\Family\FamilyFactory;
use InheritanceCalculator\Family\Member\Member;
use InheritanceCalculator\Goods\EstateGoods;
use InheritanceCalculator\Goods\GoodsInterface;
use InheritanceCalculator\Goods\LandGoods;
use InheritanceCalculator\Goods\MoneyGoods;

class FamilyTest extends \PHPUnit_Framework_TestCase
{
    /** @var Family */
    private $sut;

    /** @var Member */
    private $grandFather;

    public function setup()
    {
        $familyBuilder = new FamilyFactory();
        $this->sut = $familyBuilder->build();
        $this->grandFather = $this->sut->grandFather();
    }

    public function testShouldReturnCorrectNumberOfSons()
    {
        /* Structure
            GrandFather 1890
                A 1916
                |\_ B 1937
                |   |\_ C 1957
                |   |    \ _ D 1978
                |   |      _ E 1978
                |   |      _ F 1980
                | _ G 1939
                H 1918
                 \_ I 1940
                  _ J 1943
                     \_ K 1975
                      _ L 1977
         */
        $sonA = $this->generateMember('Son A', '1916-01-09');
        $sonB = $this->generateMember('Son B', '1937-02-18');
        $sonC = $this->generateMember('Son C', '1957-03-27');
        $sonD = $this->generateMember('Son D', '1978-04-30');
        $sonE = $this->generateMember('Son E', '1978-05-06');
        $sonF = $this->generateMember('Son F', '1980-06-15');
        $sonG = $this->generateMember('Son G', '1939-07-24');
        $sonH = $this->generateMember('Son H', '1918-08-30');
        $sonI = $this->generateMember('Son I', '1940-09-03');
        $sonJ = $this->generateMember('Son J', '1943-10-12');
        $sonK = $this->generateMember('Son K', '1975-11-21');
        $sonL = $this->generateMember('Son L', '1977-12-30');

        $this->sut->addMember($sonA, $this->grandFather);
        $this->sut->addMember($sonB, $sonA);
        $this->sut->addMember($sonC, $sonB);
        $this->sut->addMember($sonD, $sonC);
        $this->sut->addMember($sonE, $sonC);
        $this->sut->addMember($sonF, $sonC);
        $this->sut->addMember($sonG, $sonA);
        $this->sut->addMember($sonH, $this->grandFather);
        $this->sut->addMember($sonI, $sonH);
        $this->sut->addMember($sonJ, $sonH);
        $this->sut->addMember($sonK, $sonJ);
        $this->sut->addMember($sonL, $sonJ);

        $this->assertEquals(2, count($this->sut->findMember($this->grandFather->identifier())->sons()));
        $this->assertEquals(2, count($this->sut->findMember($sonA->identifier())->sons()));
        $this->assertEquals(1, count($this->sut->findMember($sonB->identifier())->sons()));
        $this->assertEquals(3, count($this->sut->findMember($sonC->identifier())->sons()));
        $this->assertEquals(0, count($this->sut->findMember($sonD->identifier())->sons()));
        $this->assertEquals(0, count($this->sut->findMember($sonE->identifier())->sons()));
        $this->assertEquals(0, count($this->sut->findMember($sonF->identifier())->sons()));
        $this->assertEquals(0, count($this->sut->findMember($sonG->identifier())->sons()));
        $this->assertEquals(2, count($this->sut->findMember($sonH->identifier())->sons()));
        $this->assertEquals(0, count($this->sut->findMember($sonI->identifier())->sons()));
        $this->assertEquals(2, count($this->sut->findMember($sonJ->identifier())->sons()));
        $this->assertEquals(0, count($this->sut->findMember($sonK->identifier())->sons()));
        $this->assertEquals(0, count($this->sut->findMember($sonL->identifier())->sons()));
    }

    /**
     * @expectedException InheritanceCalculator\Family\FamilyAddMemberException
     * @expectedExceptionCode 1
     */
    public function testShouldFailIfInvalidMemberNamesAdded()
    {
        $member = $this->generateMember('A', '1900-01-01');
        $this->sut->addMember($member, $this->grandFather);
    }

    /**
     * @expectedException InheritanceCalculator\Family\FamilyAddMemberException
     * @expectedExceptionCode 2
     */
    public function testShouldFailIfInvalidMemberBirthDateIsAdded()
    {
        $member = $this->generateMember('A Member', '1976-99-06');
        $this->sut->addMember($member, $this->grandFather);
    }

    /**
     * @expectedException InheritanceCalculator\Family\FamilyAddMemberException
     * @expectedExceptionCode 3
     */
    public function testShouldRaiseErrorWhenAddsASonToANonExistentMemberInFamily()
    {
        $memberA = $this->generateMember('A Member', '1976-01-06');
        $memberB = $this->generateMember('B Member', '1976-02-06');
        $this->sut->addMember($memberA, $memberB);
    }

    /**
     * @expectedException InheritanceCalculator\Family\FamilyFindParentException
     * @expectedExceptionCode 2
     */
    public function testShouldFailWhenAMemberIsNotFound()
    {
        $this->sut->findMember('foo_identifier');
    }

    private function generateMember($name, $birthDate)
    {

        $moneyGoodsMock = $this->prophesize(MoneyGoods::class);
        $moneyGoodsMock->amount()->willReturn(3);
        $moneyGoodsMock->type()->willReturn(GoodsInterface::MONEY);

        $landGoodsMock = $this->prophesize(LandGoods::class);
        $landGoodsMock->amount()->willReturn(6);
        $landGoodsMock->type()->willReturn(GoodsInterface::LAND);

        $estateGoodsMock = $this->prophesize(EstateGoods::class);
        $estateGoodsMock->amount()->willReturn(9);
        $estateGoodsMock->type()->willReturn(GoodsInterface::ESTATE);

        $memberMock = $this->prophesize(Member::class);
        $memberMock->identifier()->willReturn($birthDate.$name);
        $memberMock->name()->willReturn($name);
        $memberMock->birthDate()->willReturn($birthDate);

        return new Member(
            $name,
            $birthDate,
            $moneyGoodsMock->reveal(),
            $landGoodsMock->reveal(),
            $estateGoodsMock->reveal()
        );
    }
}
