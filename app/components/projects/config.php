<?php

$routes = [
    [
        'path' => 'projects/list',
        'namespace' => 'App\\Components\\Projects',
        'controller' => 'ProjectsController',
        'action' => 'listAction'
    ],
    [
        'path' => 'projects/add',
        'namespace' => 'App\\Components\\Projects',
        'controller' => 'ProjectsController',
        'action' => 'editAction'
    ],
    [
        'path' => 'projects/edit',
        'namespace' => 'App\\Components\\Projects',
        'controller' => 'ProjectsController',
        'action' => 'editAction'
    ],
    [
        'path' => 'projects/man',
        'namespace' => 'App\\Components\\Projects',
        'controller' => 'ManningController',
        'action' => 'manAction'
    ],
    [
        'path' => 'projects/report',
        'namespace' => 'App\\Components\\Projects',
        'controller' => 'ManningController',
        'action' => 'reportAction'
    ]
];