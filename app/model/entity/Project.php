<?php

namespace App\Model\Entity;

use App\Model\Entity\Skill;
use App\Model\Entity\Person;
use mysqli_stmt;

/**
 * Class Project
 */
class Project
{
    const SELECT_STRING = 'pj.`id`, pj.`name`, pj.`description`';
    const PERSIST_STRING = '`name`=?, `description`=?';

    const TABLE_NAME = 'project';
    const TABLE_ALIAS = 'pj';

    const PERSON_MAPPING_TABLE_NAME = 'project_person';
    const PERSON_MAPPING_TABLE_ALIAS = 'pjp';

    const SKILL_MAPPING_TABLE_NAME = 'project_skill';
    const SKILL_MAPPING_TABLE_ALIAS = 'pjs';

    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /** @var Skill[] */
    public $skills;

    /** @var Person[] */
    public $people;

    /**
     * @param mysqli_stmt $query
     * @return static
     */
    public static function instantiateWithBindings($query)
    {
        $entity = new static();
        $query->bind_result($entity->id, $entity->name, $entity->description);

        return $entity;
    }

    /**
     * Determines whether this project has a given skill.
     *
     * @param $skillId
     * @return bool
     */
    public function hasSkill($skillId)
    {
        if (!empty($this->skills)) {
            foreach ($this->skills as $skill) {
                if ($skill->id === $skillId) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determines whether this project has a given person.
     *
     * @param $personId
     * @return bool
     */
    public function hasPerson($personId)
    {
        if (!empty($this->people)) {
            foreach ($this->people as $person) {
                if ($person->id === $personId) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determines whether this a skill is covered by the manning of this project.
     *
     * @param $skillId
     * @return bool
     */
    public function isSkillCovered($skillId)
    {
        if (!empty($this->people)) {
            foreach ($this->people as $person) {
                if ($person->hasSkill($skillId)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Count how many times a given skill is covered by the current manning.
     *
     * @param $skillId
     * @return int
     */
    public function countSkillCoverage($skillId)
    {
        $factor = 0;

        if (!empty($this->people)) {
            foreach ($this->people as $person) {
                if ($person->hasSkill($skillId)) {
                    $factor += 1;
                }
            }
        }

        return $factor;
    }

    /**
     * Calculates a number that indicates how well this project is manned.
     * Optimal is 100.
     *
     * @return int;
     */
    public function calculateManningScore()
    {
        // No skills required means all is covered.
        if(empty($this->skills)) {
            return 100;
        }

        // No people, no points.
        if(empty($this->people)) {
            return 0;
        }

        // Start at optimal score.
        $score = 100.0;

        // Count how covered each required skill is.
        $coverageCounts = array_map(
            function(Skill $skill)
            {
                return $this->countSkillCoverage($skill->id);
            },
            $this->skills
        );

        // Subtract for every skill that isn't covered.
        foreach($coverageCounts as $coverageCount) {
            if(0 === $coverageCount) {
                $score -= (100.0 / count($this->skills));
            }
        }

        // If all skills are covered, boost the score for excessive coverage.
        if(100.0 === $score) {
            foreach($coverageCounts as $coverageCount) {
                if($coverageCount > 1) {
                    $score += (100.0 / count($this->skills)) * ($coverageCount - 1);
                }
            }
        }

        return round($score);
    }
}