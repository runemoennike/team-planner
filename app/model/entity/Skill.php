<?php

namespace App\Model\Entity;

/**
 * Class Skill
 */
class Skill
{
    const SELECT_STRING = 's.`id`, s.`name`';
    const TABLE_NAME = 'skill';

    /** @var int */
    public $id;

    /** @var string */
    public $name;
}