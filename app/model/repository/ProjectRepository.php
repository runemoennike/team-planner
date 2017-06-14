<?php

namespace App\Model\Repository;

use Core\AbstractRepository;
use App\Model\Entity\Project;

/**
 * Class ProjectRepository
 */
class ProjectRepository extends AbstractRepository
{
    /** @var SkillRepository */
    private $skillRepository;

    /** @var PersonRepository */
    private $personRepository;

    /**
     * PersonRepository constructor.
     */
    function __construct()
    {
        $this->skillRepository = new SkillRepository();
        $this->personRepository = new PersonRepository();
    }

    /**
     * Return the class of the entity managed by the repository.
     *
     * @return string
     */
    protected function getEntityClass()
    {
        return Project::class;
    }

    /**
     * Find by ID.
     *
     * @param int $id
     * @return Project
     */
    public function find($id)
    {
        /** @var Project $entity */
        $entity = parent::find($id);

        // Eager load skills and people.
        $entity->skills = $this->skillRepository->findAllByProject($entity->id);
        $entity->people = $this->personRepository->findAllByProject($entity->id);

        return $entity;
    }

    /**
     * Find all.
     *
     * @return Project[]
     */
    public function findAll()
    {
        /** @var Project[] $collection */
        $collection = parent::findAll();

        // Eager load skills and technologies.
        foreach($collection as $entity) {
            $entity->skills = $this->skillRepository->findAllByProject($entity->id);
            $entity->people = $this->personRepository->findAllByProject($entity->id);
        }

        return $collection;
    }

    /**
     * Persist a project.
     *
     * @param Project $project
     */
    public function save($project)
    {
        $dbConnection = $this->getConnection();
        $isNew = empty($project->id);

        // Save scalars first.
        if($isNew) {
            // Creating a new project.
            $query = $dbConnection->prepare('INSERT INTO '.Project::TABLE_NAME.' SET '.Project::PERSIST_STRING);
            $query->bind_param('ss', $project->name, $project->description);
            $query->execute();

            // Update ID.
            $project->id = $dbConnection->insert_id;
        } else {
            // Updating an existing project.
            $query = $dbConnection->prepare('UPDATE '.Project::TABLE_NAME.' SET '.Project::PERSIST_STRING.' WHERE `id`=?');
            $query->bind_param('ssi', $project->name, $project->description, $project->id);
            $query->execute();
        }

        // Erase all skills and resave.
        if(!$isNew) {
            $query = $dbConnection->prepare('DELETE FROM '.Project::SKILL_MAPPING_TABLE_NAME.' WHERE `project_id`=?');
            $query->bind_param('i', $project->id);
            $query->execute();
        }

        foreach($project->skills as $skill) {
            $query = $dbConnection->prepare('INSERT INTO '.Project::SKILL_MAPPING_TABLE_NAME.' SET project_id=?, skill_id=?');
            $query->bind_param('ii', $project->id, $skill->id);
            $query->execute();
        }

        // Erase all people and resave.
        if(!$isNew) {
            $query = $dbConnection->prepare('DELETE FROM '.Project::PERSON_MAPPING_TABLE_NAME.' WHERE `project_id`=?');
            $query->bind_param('i', $project->id);
            $query->execute();
        }

        foreach($project->people as $person) {
            $query = $dbConnection->prepare('INSERT INTO '.Project::PERSON_MAPPING_TABLE_NAME.' SET project_id=?, person_id=?');
            $query->bind_param('ii', $project->id, $person->id);
            $query->execute();
        }
    }
}