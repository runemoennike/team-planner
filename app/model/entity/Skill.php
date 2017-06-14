<?php

namespace App\Model\Entity;

use mysqli_stmt;

/**
 * Class Skill
 */
class Skill
{
    const SELECT_STRING = 's.`id`, s.`name`';
    const TABLE_NAME = 'skill';
    const TABLE_ALIAS = 's';

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