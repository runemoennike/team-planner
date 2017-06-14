<?php

namespace App\Model\Entity;

use mysqli_stmt;

/**
 * Class Technology
 */
class Technology
{
    const SELECT_STRING = 't.`id`, t.`name`';
    const TABLE_NAME = 'technology';
    const TABLE_ALIAS = 't';

    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /**
     * @param mysqli_stmt $query
     * @return static
     */
    public static function instantiateWithBindings($query)
    {
        $entity = new static();
        $query->bind_result($entity->id, $entity->name);

        return $entity;
    }
}