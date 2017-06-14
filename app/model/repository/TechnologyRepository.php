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
     * Return the class of the entity managed by the repository.
     *
     * @return string
     */
    protected function getEntityClass()
    {
        return Technology::class;
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
            FROM '.Technology::TABLE_NAME.' '.Technology::TABLE_ALIAS.'
            INNER JOIN '.Person::TECHNOLOGY_MAPPING_TABLE_NAME.' '.Person::TECHNOLOGY_MAPPING_TABLE_ALIAS.' 
                ON '.Person::TECHNOLOGY_MAPPING_TABLE_ALIAS.'.`technology_id` = '.Technology::TABLE_ALIAS.'.`id`
            WHERE '.Person::TECHNOLOGY_MAPPING_TABLE_ALIAS.'.`person_id` = ?
        ');
        $query->bind_param('i', $personId);

        // Fetch.
        return $this->fetchAllFromQuery($query);
    }
}