<?php

namespace App\Model\Repository;

use Core\AbstractRepository;
use App\Model\Entity\Project;
use App\Model\Entity\Person;

/**
 * Class PersonRepository
 */
class PersonRepository extends AbstractRepository
{
    /** @var SkillRepository */
    private $skillRepository;

    /** @var TechnologyRepository */
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
     * @param int $id
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
     * @return Person[]
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
     * Find all by project ID.
     *
     * @param int $projectId
     * @return array
     */
    public function findAllByProject($projectId)
    {
        $dbConnection = $this->getConnection();

        // Query.
        $query = $dbConnection->prepare('
            SELECT '.Person::SELECT_STRING.' 
            FROM '.Person::TABLE_NAME.' '.Person::TABLE_ALIAS.'
            INNER JOIN '.Project::PERSON_MAPPING_TABLE_NAME.' '.Project::PERSON_MAPPING_TABLE_ALIAS.' 
                ON '.Project::PERSON_MAPPING_TABLE_ALIAS.'.`person_id` = '.Person::TABLE_ALIAS.'.`id`
            WHERE '.Project::PERSON_MAPPING_TABLE_ALIAS.'.`project_id` = ?
        ');
        $query->bind_param('i', $projectId);

        // Fetch.
        $collection = $this->fetchAllFromQuery($query);

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