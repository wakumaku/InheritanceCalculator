<?php

namespace InheritanceCalculator\Family\Member;

use InheritanceCalculator\Goods\GoodsInterface;

interface MemberInterface
{
    public function identifier();
    public function name();
    public function birthDate();
    public function age();
    public function isDead();
    public function addGood(GoodsInterface $good);
}
