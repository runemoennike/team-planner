<?php

namespace App\Model\Repository;

use mysqli_stmt;
use Core\AbstractRepository;
use App\Model\Entity\Person;

/**
 * Class PersonRepository
 */
class PersonRepository extends AbstractRepository
{
    private $skillRepository;
    private $technologyRepository;

    /**
     * PersonRepository constructor.
     */
    function __construct()
    {
        $this->skillRepository = new SkillRepository();
        $this->technologyRepository = new TechnologyRepository();
    }

    /**
     * Find by ID.
     *
     * @param $id
     * @return Person
     */
    public function find($id)
    {
        $dbConnection = $this->getConnection();

        // Query.
        $query = $dbConnection->prepare('
            SELECT '.Person::SELECT_STRING.'
            FROM '.Person::TABLE_NAME.' p
            WHERE p.`id` = ?
        ');
        $query->bind_param('i', $id);
        $query->execute();
        $query->store_result();

        // Fetch.
        $entity = $this->bindResult($query);
        $query->fetch();

        // Eager load skills and technologies.
        $entity->skills = $this->skillRepository->findAllByPerson($entity->id);
        $entity->technologies = $this->technologyRepository->findAllByPerson($entity->id);

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
            SELECT '.Person::SELECT_STRING.' 
            FROM '.Person::TABLE_NAME.' p
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
                // Eager load skills and technologies.
                $entity->skills = $this->skillRepository->findAllByPerson($entity->id);
                $entity->technologies = $this->technologyRepository->findAllByPerson($entity->id);

                $collection[] = $entity;
            }
        }

        return $collection;
    }

    /**
     * Persist a person.
     *
     * @param Person $person
     */
    public function save($person)
    {
        $dbConnection = $this->getConnection();
        $isNew = empty($person->id);

        // Save scalars first.
        if($isNew) {
            // Creating a new person.
            $query = $dbConnection->prepare('INSERT INTO '.Person::TABLE_NAME.' SET '.Person::PERSIST_STRING);
            $query->bind_param('sssss', $person->name, $person->email, $person->phone, $person->education, $person->hiredYear);
            $query->execute();

            // Update ID.
            $person->id = $dbConnection->insert_id;
        } else {
            // Updating an existing person.
            $query = $dbConnection->prepare('UPDATE '.Person::TABLE_NAME.' SET '.Person::PERSIST_STRING.' WHERE `id`=?');
            $query->bind_param('sssssi', $person->name, $person->email, $person->phone, $person->education, $person->hiredYear, $person->id);
            $query->execute();
        }

        // Erase all skills and resave.
        if(!$isNew) {
            $query = $dbConnection->prepare('DELETE FROM '.Person::SKILL_MAPPING_TABLE_NAME.' WHERE `person_id`=?');
            $query->bind_param('i', $person->id);
            $query->execute();
        }

        foreach($person->skills as $skill) {
            $query = $dbConnection->prepare('INSERT INTO '.Person::SKILL_MAPPING_TABLE_NAME.' SET person_id=?, skill_id=?');
            $query->bind_param('ii', $person->id, $skill->id);
            $query->execute();
        }

        // Erase all technologies and resave.
        if(!$isNew) {
            $query = $dbConnection->prepare('DELETE FROM '.Person::TECHNOLOGY_MAPPING_TABLE_NAME.' WHERE `person_id`=?');
            $query->bind_param('i', $person->id);
            $query->execute();
        }

        foreach($person->technologies as $technology) {
            $query = $dbConnection->prepare('INSERT INTO '.Person::TECHNOLOGY_MAPPING_TABLE_NAME.' SET person_id=?, technology_id=?');
            $query->bind_param('ii', $person->id, $technology->id);
            $query->execute();
        }
    }

    /**
     * @param mysqli_stmt $query
     * @return Person
     */
    private function bindResult($query): Person
    {
        $entity = new Person();
        $query->bind_result($entity->id, $entity->name, $entity->email, $entity->phone, $entity->education, $entity->hiredYear);

        return $entity;
    }

    /**
     * @param mysqli_stmt $query
     * @param $entity Person
     */
    private function bindParam($query, $entity)
    {
    }
}