<?php

namespace InheritanceCalculator\Family;

use InheritanceCalculator\Family\Member\Member;
use InheritanceCalculator\Family\Member\MemberValidator;

class Family
{
    /** @var Member **/
    private $grandFather;

    /**
     * @var MemberValidator
     */
    private $memberValidator;

    public function __construct(Member $grandFather, MemberValidator $memberValidator)
    {
        $this->memberValidator = $memberValidator;
        $this->addGrandFather($grandFather);
    }

    private function addGrandFather(Member $member)
    {
        if ($this->validateMember($member, $member)) {
            $this->grandFather = $member;
        }
    }

    public function addMember(Member $member, Member $parent)
    {
        if ($this->validateMember($member, $parent)) {
            $this->assignMemberToParent($member, $parent);
        }
    }

    public function findMember($identifier)
    {
        $member = $this->findParentById($this->grandFather, $identifier);
        if ($member == null) {
            throw new FamilyFindParentException('Parent ID not found! '.$identifier, 2);
        }
        return $member;
    }

    private function validateMember(Member $member, Member $parent)
    {
        if (!$this->memberValidator->validateName($member->name())) {
            throw new FamilyAddMemberException('Family Member Name is not valid', 1);
        }

        if (!$this->memberValidator->validateBirthDate($member->birthDate())) {
            throw new FamilyAddMemberException('Family Member BirthDate is not valid', 2);
        }

        if ($member != $parent) {
            if (!$this->memberValidator->validateParentSonAges($parent->birthDate(), $member->birthDate())) {
                throw new FamilyAddMemberException('Family member is older than his parent or parent is too young!', 3);
            }
        }

        return true;
    }

    private function assignMemberToParent(Member $member, Member $parent)
    {
        if ($parentFound = $this->findParentById($this->grandFather, $parent->identifier())) {
            $parentFound->addSon($member);
        } else {
            throw new FamilyFindParentException('Parent ID not found! '.$parent->identifier(), 1);
        }
    }

    private function findParentById(Member $parentRoot, $searchId)
    {
        if ($parentRoot->identifier() == $searchId) {
            return $parentRoot;
        }

        /** @var Member $son */
        foreach ($parentRoot->sons() as $son) {
            if ($son->identifier() == $searchId) {
                return $son;
            }
            $nextParent = $this->findParentById($son, $searchId);
            if ($nextParent != null) {
                return $nextParent;
            }
        }
        return null;
    }

    /**
     * @return Member
     */
    public function grandFather()
    {
        return $this->grandFather;
    }
}
