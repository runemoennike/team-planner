<?php

namespace App\Model\Entity;

use App\Model\Entity\Skill;
use App\Model\Entity\Technology;
use mysqli_stmt;

/**
 * Class Person
 */
class Person
{
    const SELECT_STRING = 'p.`id`, p.`name`, p.`email`, p.`phone`, p.`education`, p.`hired_year`';
    const PERSIST_STRING = '`name`=?, `email`=?, `phone`=?, `education`=?, `hired_year`=?';

    const TABLE_NAME = 'person';
    const TABLE_ALIAS = 'p';

    const SKILL_MAPPING_TABLE_NAME = 'person_skill';
    const SKILL_MAPPING_TABLE_ALIAS = 'ps';

    const TECHNOLOGY_MAPPING_TABLE_NAME = 'person_technology';
    const TECHNOLOGY_MAPPING_TABLE_ALIAS = 'pt';

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
     * @param mysqli_stmt $query
     * @return static
     */
    public static function instantiateWithBindings($query)
    {
        $entity = new static();
        $query->bind_result($entity->id, $entity->name, $entity->email, $entity->phone, $entity->education, $entity->hiredYear);

        return $entity;
    }

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