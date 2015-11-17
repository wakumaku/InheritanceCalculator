<?php

namespace InheritanceCalculator\Family\Member;

use Carbon\Carbon;
use InheritanceCalculator\Goods\EstateGoods;
use InheritanceCalculator\Goods\GoodsInterface;
use InheritanceCalculator\Goods\LandGoods;
use InheritanceCalculator\Goods\MoneyGoods;

class Member implements MemberInterface
{
    const AGE_WHEN_DIES = 70;

    private $name;
    private $birthDate;
    private $sons = [];
    private $dateEpoch;

    /** @var LandGoods */
    private $landGoods;
    /** @var MoneyGoods  */
    private $moneyGoods;
    /** @var EstateGoods */
    private $estateGoods;

    /** @var int */
    private $hierachyOrder;

    public function __construct(
        $name,
        $birthDate,
        MoneyGoods $moneyGoods,
        LandGoods $landGoods,
        EstateGoods $estateGoods
    ) {
        $this->name = $name;
        $this->birthDate = $birthDate;
        $this->moneyGoods = $moneyGoods;
        $this->landGoods = $landGoods;
        $this->estateGoods = $estateGoods;
    }

    public function identifier()
    {
        return $this->birthDate().$this->name();
    }

    public function name()
    {
        return $this->name;
    }

    public function birthDate()
    {
        return $this->birthDate;
    }

    public function isDead()
    {
        return ($this->age() >= self::AGE_WHEN_DIES);
    }

    public function isUnBorn()
    {
        return ($this->age() <= 0);
    }

    public function age()
    {
        if ($this->dateEpoch == null) {
            return 0;
        } else {
            $baseDate = $this->dateEpoch;
        }

        $age = Carbon::createFromFormat('Y-m-d', $this->birthDate)
            ->diffInYears(Carbon::createFromFormat('Y-m-d', $baseDate), false);

        return ($age >= 0)? $age: 0;
    }

    public function addSon(Member $member)
    {
        $this->sons[$member->identifier()] = $member;
        $this->resetHierarchyOrder();
    }

    public function sons()
    {
        return $this->sons;
    }

    public function addGood(GoodsInterface $good)
    {
        if ($good->type() == GoodsInterface::LAND) {
            $this->landGoods->increaseAmount($good->amount());
        } elseif ($good->type() == GoodsInterface::ESTATE) {
            $this->estateGoods->increaseAmount($good->amount());
        } elseif ($good->type() == GoodsInterface::MONEY) {
            $this->moneyGoods->increaseAmount($good->amount());
        }
    }

    public function updateGood(GoodsInterface $good)
    {
        if ($good->type() == GoodsInterface::LAND) {
            $this->landGoods = $good;
        } elseif ($good->type() == GoodsInterface::ESTATE) {
            $this->estateGoods = $good;
        } elseif ($good->type() == GoodsInterface::MONEY) {
            $this->moneyGoods = $good;
        }
    }

    /**
     * @param mixed $dateEpoch
     */
    public function setDateEpoch($dateEpoch)
    {
        $this->dateEpoch = $dateEpoch;
    }

    /**
     * @return LandGoods
     */
    public function getLandGoods()
    {
        return $this->landGoods;
    }

    /**
     * @return MoneyGoods
     */
    public function getMoneyGoods()
    {
        return $this->moneyGoods;
    }

    /**
     * @return EstateGoods
     */
    public function getEstateGoods()
    {
        return $this->estateGoods;
    }

    private function resetHierarchyOrder()
    {
        ksort($this->sons);
        //$this->sons = array_reverse($this->sons, true);

        /** @var Member $son */
        $hierachyOrder = 1;
        foreach ($this->sons as $son) {
            $son->setHierarchyOrder($hierachyOrder);
            $hierachyOrder++;
        }
    }

    private function setHierarchyOrder($hierachyOrder)
    {
        $this->hierachyOrder = $hierachyOrder;
    }

    /**
     * @return int
     */
    public function hierarchyOrder()
    {
        return $this->hierachyOrder;
    }
}
