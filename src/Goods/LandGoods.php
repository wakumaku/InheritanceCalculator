<?php

namespace InheritanceCalculator\Goods;

class LandGoods implements GoodsInterface
{
    private $name;
    private $amount;
    private $unit;
    private $unitWorth;
    private $isDivisible;

    public function __construct($amount = 0)
    {
        $this->name = 'Lands';
        $this->unit = 'm2';
        $this->unitWorth = 300;
        $this->isDivisible = false;
        $this->setAmount($amount);
    }

    public function name()
    {
        return $this->name;
    }

    public function amount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function increaseAmount($amount)
    {
        $this->amount += $amount;
    }

    public function unit()
    {
        return $this->unit;
    }

    public function isDivisible()
    {
        return $this->isDivisible;
    }

    public function unitWorth()
    {
        return $this->unitWorth;
    }

    public function worths()
    {
        return $this->amount() * $this->unitWorth();
    }

    public function type()
    {
        return GoodsInterface::LAND;
    }
}
