<?php

namespace Core;

use mysqli;

/**
 * Class AbstractRepository
 */
abstract class AbstractRepository
{
    private static $dbConnection = null;

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

}