<?php

namespace App\Model\Repository;

use mysqli_stmt;
use Core\AbstractRepository;
use App\Model\Entity\Person;
use App\Model\Entity\Technology;

/**
 * Class TechnologyRepository
 */
class TechnologyRepository extends AbstractRepository
{
    /**
     * Find by ID.
     *
     * @param $id
     * @return Technology
     */
    public function find($id)
    {
        $dbConnection = $this->getConnection();

        // Query.
        $query = $dbConnection->prepare('
            SELECT '.Technology::SELECT_STRING.'
            FROM '.Technology::TABLE_NAME.' t
            WHERE t.`id` = ?
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
            SELECT '.Technology::SELECT_STRING.' 
            FROM '.Technology::TABLE_NAME.' t
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
            SELECT '.Technology::SELECT_STRING.' 
            FROM '.Technology::TABLE_NAME.' t
            INNER JOIN '.Person::TECHNOLOGY_MAPPING_TABLE_NAME.' ts ON ts.`technology_id` = t.`id`
            WHERE ts.`person_id` = ?
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
     * @return Technology
     */
    private function bindResult($query): Technology
    {
        $entity = new Technology();
        $query->bind_result($entity->id, $entity->name);

        return $entity;
    }
}