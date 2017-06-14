<?php

namespace Core;

use mysqli;
use mysqli_stmt;

/**
 * Class AbstractRepository
 */
abstract class AbstractRepository
{
    private static $dbConnection = null;

    /**
     * Return the class of the entity managed by the repository.
     *
     * @return string
     */
    protected abstract function getEntityClass();

    /**
     * Returns a connection to the database, creating it if needed.
     *
     * @return mysqli
     */
    protected function getConnection()
    {
        if(null === self::$dbConnection) {
            self::$dbConnection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_SCHEMA);
        }

        return self::$dbConnection;
    }


    /**
     * Find by ID.
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $dbConnection = $this->getConnection();

        // Query.
        $tableAlias = constant($this->getEntityClass() . '::TABLE_ALIAS');
        $selectString = constant($this->getEntityClass() . '::SELECT_STRING');
        $tableName = constant($this->getEntityClass() . '::TABLE_NAME');

        $query = $dbConnection->prepare('
            SELECT '. $selectString .'
            FROM '. $tableName .' '. $tableAlias .'
            WHERE '. $tableAlias .'.`id` = ?
        ');
        $query->bind_param('i', $id);
        $query->execute();
        $query->store_result();

        // Fetch.
        $entity = call_user_func([$this->getEntityClass(), 'instantiateWithBindings'], $query);
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
        $tableAlias = constant($this->getEntityClass() . '::TABLE_ALIAS');
        $selectString = constant($this->getEntityClass() . '::SELECT_STRING');
        $tableName = constant($this->getEntityClass() . '::TABLE_NAME');

        $query = $dbConnection->prepare('
            SELECT '. $selectString .'
            FROM '. $tableName .' '. $tableAlias .'
        ');

        // Fetch.
        return $this->fetchAllFromQuery($query);
    }

    /**
     * @param mysqli_stmt $query
     * @return array
     */
    protected function fetchAllFromQuery($query): array
    {
        // Execute.
        $query->execute();
        $query->store_result();

        // Fetch.
        $collection = [];
        $reading = true;

        while ($reading) {
            $entity = call_user_func([$this->getEntityClass(), 'instantiateWithBindings'], $query);

            if ($reading = $query->fetch()) {
                $collection[] = $entity;
            }
        }

        return $collection;
    }

}