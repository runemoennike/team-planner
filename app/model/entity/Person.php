<?php

namespace App\Model\Entity;

use App\Model\Entity\Skill;
use App\Model\Entity\Technology;

/**
 * Class Person
 */
class Person
{
    const SELECT_STRING = 'p.`id`, p.`name`, p.`email`, p.`phone`, p.`education`, p.`hired_year`';
    const PERSIST_STRING = '`name`=?, `email`=?, `phone`=?, `education`=?, `hired_year`=?';
    const TABLE_NAME = 'person';
    const SKILL_MAPPING_TABLE_NAME = 'person_skill';
    const TECHNOLOGY_MAPPING_TABLE_NAME = 'person_technology';

    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $email;

    /** @var string */
    public $phone;

    /** @var string */
    public $education;

    /** @var int */
    public $hiredYear;

    /** @var Skill[] */
    public $skills;

    /** @var Technology[] */
    public $technologies;

    /**
     * Determines whether this person has a given skill.
     *
     * @param $skillId
     * @return bool
     */
    public function hasSkill($skillId)
    {
        if(!empty($this->skills)) {
            foreach($this->skills as $skill) {
                if($skill->id === $skillId) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determines whether this person has a given technology.
     *
     * @param $technologyId
     * @return bool
     */
    public function hasTechnology($technologyId)
    {
        if(!empty($this->technologies)) {
            foreach($this->technologies as $technology) {
                if($technology->id === $technologyId) {
                    return true;
                }
            }
        }

        return false;
    }
}