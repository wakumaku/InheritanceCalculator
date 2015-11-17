<?php

namespace InheritanceCalculator\Lawyer;

use InheritanceCalculator\Family\Family;
use InheritanceCalculator\Family\Member\Member;
use InheritanceCalculator\Goods\EstateGoods;
use InheritanceCalculator\Goods\LandGoods;
use InheritanceCalculator\Goods\MoneyGoods;

class Lawyer
{
    /** @var Family */
    private $family;
    private $dateOfApplication;

    public function __construct(Family $family, $dateOfApplication)
    {
        $this->family = $family;
        $this->dateOfApplication = $dateOfApplication;
    }

    public function calculateInheritance()
    {
        $this->applyGoodsLaw($this->family->grandFather());
    }

    private function applyGoodsLaw(Member $parent)
    {
        $parent->setDateEpoch($this->dateOfApplication);
        /** @var Member $son */
        foreach ($parent->sons() as $son) {
            $this->transferGoods($parent, $son);
            $this->applyGoodsLaw($son);
        }
    }

    private function transferGoods(Member $parent, Member $son)
    {
        $this->transferMoneyGoods($parent, $son);
        $this->transferLandGoods($parent, $son);
        $this->transferEstateGoods($parent, $son);
    }

    /**
     * @param Member $parent
     * @param Member $son
     * @return int
     */
    private function transferMoneyGoods(Member $parent, Member $son)
    {
        $sonNumber = count($parent->sons());
        $moneyGoodAmount = $parent->getMoneyGoods()->amount();
        if ($parent->isDead()) {
            $moneyGoodAmountForSons = $moneyGoodAmount;
            $moneyGoodAmountForParent = 0;
        } else {
            $moneyGoodAmountForSons = floor($moneyGoodAmount / 2);
            $moneyGoodAmountForParent = ceil($moneyGoodAmount / 2);
        }

        $moneyForEachSon = $moneyGoodAmountForSons / $sonNumber;
        if ($son->hierarchyOrder() < $sonNumber) {
            $moneyToGive = ceil($moneyForEachSon);
        } else {
            $moneyToGive = floor($moneyForEachSon);
        }

        $son->addGood(new MoneyGoods($moneyToGive));

        if ($son->hierarchyOrder() == $sonNumber) {
            $parent->updateGood(new MoneyGoods($moneyGoodAmountForParent));
        }
    }

    /**
     * @param Member $parent
     * @param Member $son
     */
    private function transferLandGoods(Member $parent, Member $son)
    {
        $landGoodAmount = $parent->getLandGoods()->amount();
        if ($parent->isDead()) {
            if ($son->hierarchyOrder() == 1) {
                $son->addGood(new LandGoods($landGoodAmount));
                $parent->updateGood(new LandGoods(0));
            }
        }
    }

    /**
     * @param Member $parent
     * @param Member $son
     */
    private function transferEstateGoods(Member $parent, Member $son)
    {
        $sonNumber = count($parent->sons());
        $estateGoodAmount = $parent->getEstateGoods()->amount();
        if ($parent->isDead()) {
            $amountPerSon = array_fill_keys(range(1, $sonNumber), 0);

            while ($estateGoodAmount > 0) {
                for ($hierarchy = 1; $hierarchy <= $sonNumber; $hierarchy++) {
                    $amountPerSon[$hierarchy] += 1;
                    $estateGoodAmount--;
                }
            }
            $son->addGood(new EstateGoods($amountPerSon[$son->hierarchyOrder()]));
            $parent->updateGood(new EstateGoods(0));
        }
    }

    /**
     * @return Family
     */
    public function getFamily()
    {
        return $this->family;
    }
}
