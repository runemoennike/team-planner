<?php

namespace App\Model\Repository;

use Core\AbstractRepository;
use App\Model\Entity\Person;
use App\Model\Entity\Project;
use App\Model\Entity\Skill;

/**
 * Class SkillRepository
 */
class SkillRepository extends AbstractRepository
{
    /**
     * Return the class of the entity managed by the repository.
     *
     * @return string
     */
    protected function getEntityClass()
    {
        return Skill::class;
    }

    /**
     * Find all by person ID.
     *
     * @param $personId
     * @return Skill[]
     */
    public function findAllByPerson($personId)
    {
        $dbConnection = $this->getConnection();

        // Query.
        $query = $dbConnection->prepare('
            SELECT '.Skill::SELECT_STRING.' 
            FROM '.Skill::TABLE_NAME.' '.Skill::TABLE_ALIAS.'
            INNER JOIN '.Person::SKILL_MAPPING_TABLE_NAME.' '.Person::SKILL_MAPPING_TABLE_ALIAS.' 
                ON '.Person::SKILL_MAPPING_TABLE_ALIAS.'.`skill_id` = '.Skill::TABLE_ALIAS.'.`id`
            WHERE '.Person::SKILL_MAPPING_TABLE_ALIAS.'.`person_id` = ?
        ');
        $query->bind_param('i', $personId);

        // Fetch.
        return $this->fetchAllFromQuery($query);
    }

    /**
     * Find all by project ID.
     *
     * @param $projectId
     * @return Skill[]
     */
    public function findAllByProject($projectId)
    {
        $dbConnection = $this->getConnection();

        // Query.
        $query = $dbConnection->prepare('
            SELECT '.Skill::SELECT_STRING.' 
            FROM '.Skill::TABLE_NAME.' '.Skill::TABLE_ALIAS.'
            INNER JOIN '.Project::SKILL_MAPPING_TABLE_NAME.' '.Project::SKILL_MAPPING_TABLE_ALIAS.' 
                ON '.Project::SKILL_MAPPING_TABLE_ALIAS.'.`skill_id` = '.Skill::TABLE_ALIAS.'.`id`
            WHERE '.Project::SKILL_MAPPING_TABLE_ALIAS.'.`project_id` = ?
        ');
        $query->bind_param('i', $projectId);

        // Fetch.
        return $this->fetchAllFromQuery($query);
    }
}