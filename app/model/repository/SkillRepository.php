<?php

namespace App\Model\Repository;

use mysqli_stmt;
use Core\AbstractRepository;
use App\Model\Entity\Skill;
use App\Model\Entity\Person;

/**
 * Class SkillRepository
 */
class SkillRepository extends AbstractRepository
{
    /**
     * Find by ID.
     *
     * @param $id
     * @return Skill
     */
    public function find($id)
    {
        $dbConnection = $this->getConnection();

        // Query.
        $query = $dbConnection->prepare('
            SELECT '.Skill::SELECT_STRING.'
            FROM '.Skill::TABLE_NAME.' s
            WHERE s.`id` = ?
        ');
        $query->bind_param('i', $id);
        $query->execute();
        $query->store_result();

        // Fetch.
        $entity = $this->bindResult($query);
        $query->fetch();

        return $entity;
    }

    /**
     * Find all.
     *
     * @return array
     */
    public function findAll()
    {
        $dbConnection = $this->getConnection();

        // Query.
        $query = $dbConnection->prepare('
            SELECT '.Skill::SELECT_STRING.' 
            FROM '.Skill::TABLE_NAME.' s
        ');
        $query->execute();
        $query->store_result();

        // Fetch.
        $collection = [];
        $reading = true;

        while($reading) {
            $entity = $this->bindResult($query);

            if($reading = $query->fetch())
            {
                $collection[] = $entity;
            }
        }

        return $collection;
    }

    /**
     * Find all by person ID.
     *
     * @param $personId
     * @return array
     */
    public function findAllByPerson($personId)
    {
        $dbConnection = $this->getConnection();

        // Query.
        $query = $dbConnection->prepare('
            SELECT '.Skill::SELECT_STRING.' 
            FROM '.Skill::TABLE_NAME.' s
            INNER JOIN '.Person::SKILL_MAPPING_TABLE_NAME.' ps ON ps.`skill_id` = s.`id`
            WHERE ps.`person_id` = ?
        ');
        $query->bind_param('i', $personId);
        $query->execute();
        $query->store_result();

        // Fetch.
        $collection = [];
        $reading = true;

        while($reading) {
            $entity = $this->bindResult($query);

            if($reading = $query->fetch())
            {
                $collection[] = $entity;
            }
        }

        return $collection;
    }

    /**
     * @param mysqli_stmt $query
     * @return Skill
     */
    private function bindResult($query): Skill
    {
        $entity = new Skill();
        $query->bind_result($entity->id, $entity->name);

        return $entity;
    }
}