<?php

namespace InheritanceCalculator\Output;

use InheritanceCalculator\Family\Family;
use InheritanceCalculator\Family\Member\Member;
use League\CLImate\CLImate;

class FamilyPrinter
{

    /**
     * @var Family
     */
    private $family;
    /**
     * @var CLImate
     */
    private $cli;

    public function __construct(Family $family, CLImate $cli)
    {
        $this->family = $family;
        $this->cli = $cli;
    }

    public function printTree()
    {
        $this->cli->backgroundYellow()->black()->out($this->headline($this->family->grandFather()));
        $this->cli->backgroundYellow()->black()->out($this->goodsInfo($this->family->grandFather()));
        $this->printMemberDescendants($this->family->grandFather(), 1);
    }

    private function printMemberDescendants(Member $member, $tabs)
    {
        /** @var Member $son */
        foreach ($member->sons() as $son) {
            $color = 'green';
            if ($son->isDead()) {
                $color = 'red';
            }
            $this->cli->{$color}(str_repeat("\t", $tabs) . $this->headline($son));
            $this->cli->{$color}($this->goodsInfo($son));
            $this->printMemberDescendants($son, $tabs + 1);
        }
    }

    public function setFamily(Family $family)
    {
        $this->family = $family;
    }

    private function goodsInfo(Member $son)
    {
        $totalWorths = $son->getMoneyGoods()->worths() +
            $son->getLandGoods()->worths() +
            $son->getEstateGoods()->worths();

        $goodsStatus = "\t\t\t\t\t\t\t";
        $goodsStatus .= "€: ".$this->formatNumberPad($son->getMoneyGoods()->amount(), 10);
        $goodsStatus .= "\tm2: ".str_pad($son->getLandGoods()->amount(), 6, ' ', STR_PAD_LEFT);
        $goodsStatus .= "\tu: ".str_pad($son->getEstateGoods()->amount(), 3, ' ', STR_PAD_LEFT);
        $goodsStatus .= "\tTOTAL €: ". $this->formatNumberPad($totalWorths, 10);

        return $goodsStatus;
    }

    private function headline(Member $member)
    {
        $headLine = $member->name() . " - " . $member->birthDate();
        return $headLine;
    }

    private function formatNumberPad($number, $pad)
    {
        return str_pad(number_format($number, 0, ',', '.'), $pad, ' ', STR_PAD_LEFT);
    }
}
