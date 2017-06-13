<?php

namespace App\Model\Entity;

/**
 * Class Technology
 */
class Technology
{
    const SELECT_STRING = 't.`id`, t.`name`';
    const TABLE_NAME = 'technology';

    /** @var int */
    public $id;

    /** @var string */
    public $name;
}