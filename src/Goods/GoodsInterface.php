<?php

namespace InheritanceCalculator\Goods;

interface GoodsInterface
{
    const LAND = 'LAND';
    const MONEY = 'MONEY';
    const ESTATE = 'ESTATE';

    public function type();
    public function name();
    public function amount();
    public function setAmount($amount);
    public function unit();
    public function unitWorth();
    public function worths();
    public function isDivisible();
}
