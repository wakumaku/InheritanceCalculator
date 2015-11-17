<?php

namespace InheritanceCalculator\Family;

use InheritanceCalculator\Family\Member\Member;
use InheritanceCalculator\Family\Member\MemberValidator;
use InheritanceCalculator\Family\Member\Validator\AgeDifferenceValidator;
use InheritanceCalculator\Family\Member\Validator\BirthDateValidator;
use InheritanceCalculator\Family\Member\Validator\NameValidator;
use InheritanceCalculator\Goods\EstateGoods;
use InheritanceCalculator\Goods\LandGoods;
use InheritanceCalculator\Goods\MoneyGoods;

class FamilyFactory
{
    public function build()
    {
        $grandFather = $this->generateMember('TheGrandFather', '1890-01-01', 100);
        $family = new Family(
            $grandFather,
            new MemberValidator(
                new NameValidator(),
                new BirthDateValidator(),
                new AgeDifferenceValidator()
            )
        );

        $sonA = $this->generateMember('Son A', '1916-01-09', rand(0, 100));
        $sonB = $this->generateMember('Son B', '1937-02-18', rand(0, 100));
        $sonC = $this->generateMember('Son C', '1957-03-27', rand(0, 100));
        $sonD = $this->generateMember('Son D', '1978-04-30', rand(0, 100));
        $sonE = $this->generateMember('Son E', '1978-05-06', rand(0, 100));
        $sonF = $this->generateMember('Son F', '1980-06-15', rand(0, 100));
        $sonG = $this->generateMember('Son G', '1939-07-24', rand(0, 100));
        $sonH = $this->generateMember('Son H', '1918-08-30', rand(0, 100));
        $sonI = $this->generateMember('Son I', '1940-09-03', rand(0, 100));
        $sonJ = $this->generateMember('Son J', '1943-10-12', rand(0, 100));
        $sonK = $this->generateMember('Son K', '1975-11-21', rand(0, 100));
        $sonL = $this->generateMember('Son L', '1977-12-30', rand(0, 100));

        /* Prefixed Structure
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

        $family->addMember($sonA, $grandFather);
        $family->addMember($sonB, $sonA);
        $family->addMember($sonC, $sonB);
        $family->addMember($sonD, $sonC);
        $family->addMember($sonE, $sonC);
        $family->addMember($sonF, $sonC);
        $family->addMember($sonG, $sonA);
        $family->addMember($sonH, $grandFather);
        $family->addMember($sonI, $sonH);
        $family->addMember($sonJ, $sonH);
        $family->addMember($sonK, $sonJ);
        $family->addMember($sonL, $sonJ);

        return $family;
    }

    private function generateMember($name, $birthDate, $percentChanceToHaveGoods)
    {
        $money = new MoneyGoods(0);
        $land = new LandGoods(0);
        $estate = new EstateGoods(0);

        if (rand(0, 100) < $percentChanceToHaveGoods) {
            $money->increaseAmount(rand(0, 1000000));
        }
        if (rand(0, 100) < $percentChanceToHaveGoods) {
            $land->increaseAmount(rand(0, 500));
        }
        if (rand(0, 100) < $percentChanceToHaveGoods) {
            $estate->increaseAmount(rand(0, 10));
        }

        return new Member(
            $name,
            $birthDate,
            $money,
            $land,
            $estate
        );
    }
}
