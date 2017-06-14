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
     * Return the class of the entity managed by the repository.
     *
     * @return string
     */
    protected function getEntityClass()
    {
        return Person::class;
    }

    /**
     * Find by ID.
     *
     * @param $id
     * @return Person
     */
    public function find($id)
    {
        /** @var Person $entity */
        $entity = parent::find($id);

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
        /** @var Person[] $collection */
        $collection = parent::findAll();

        // Eager load skills and technologies.
        foreach($collection as $entity) {
            $entity->skills = $this->skillRepository->findAllByPerson($entity->id);
            $entity->technologies = $this->technologyRepository->findAllByPerson($entity->id);
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
}