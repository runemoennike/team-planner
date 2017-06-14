<?php

$routes = [
    [
        'path' => 'people/list',
        'namespace' => 'App\\Components\\People',
        'controller' => 'PeopleController',
        'action' => 'listAction'
    ],
    [
        'path' => 'people/add',
        'namespace' => 'App\\Components\\People',
        'controller' => 'PeopleController',
        'action' => 'editAction'
    ],
    [
        'path' => 'people/edit',
        'namespace' => 'App\\Components\\People',
        'controller' => 'PeopleController',
        'action' => 'editAction'
    ]
];