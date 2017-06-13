<?php

$routes = [
    [
        'path' => 'people/list',
        'controller' => 'PeopleController',
        'action' => 'listAction'
    ],
    [
        'path' => 'people/add',
        'controller' => 'PeopleController',
        'action' => 'editAction'
    ],
    [
        'path' => 'people/edit',
        'controller' => 'PeopleController',
        'action' => 'editAction'
    ]
];