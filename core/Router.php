<?php

namespace Core;

/**
 * Class Router
 */
class Router
{
    const ROUTE_KEY_ROOT_PATH = '_rootPath';
    const ROUTE_KEY_NAMESPACE = 'namespace';
    const ROUTE_KEY_CONTROLLER = 'controller';
    const ROUTE_KEY_ACTION = 'action';

    /** @var array $routes contains all registered routes. */
    private $routes = [];

    /** @var string $appRoot */
    private $appRoot;

    /**
     * Router constructor.
     * @param string $appRoot
     */
    public function __construct($appRoot)
    {
        $this->appRoot = $appRoot;
    }

    /**
     * Load routes from all components in app.
     */
    public function loadComponentRoutes()
    {
        $componentConfigFiles = $this->findComponentConfigFiles();

        foreach($componentConfigFiles as $componentConfigFile) {
            /** @var array $routes defined in component config file. */
            require($componentConfigFile);

            foreach($routes as $route) {
                $route[self::ROUTE_KEY_ROOT_PATH] = dirname($componentConfigFile);

                $this->routes[] = $route;
            }
        }
    }

    /**
     * Dispatches to a component based on the incoming route.
     */
    public function run()
    {
        $route = $this->findMatchingRoute($_GET['route'] ?? '/');

        if($route != null) {
            $controller = $route[self::ROUTE_KEY_CONTROLLER];
            $namespace = $route[self::ROUTE_KEY_NAMESPACE];
            $action = $route[self::ROUTE_KEY_ACTION];

            require(sprintf('%s/%s.php', $route[self::ROUTE_KEY_ROOT_PATH], $controller));

            /** @var AbstractController $controllerInstance */
            $controllerFqn = $namespace .'\\'.$controller;
            $controllerInstance = new $controllerFqn($this->appRoot);
            $controllerInstance->setRootPath($route[self::ROUTE_KEY_ROOT_PATH]);

            $controllerInstance->$action();
        } else {
            http_response_code(404);
            echo('No such route.');
        }
    }

    /**
     * Finds a route object matching a given route string.
     *
     * @param string $requestedRoute
     * @return array|null
     */
    private function findMatchingRoute($requestedRoute)
    {
        foreach($this->routes as $definedRoute) {
            $routeRegex = sprintf('/^%s$/i', preg_quote($definedRoute['path'], '/'));

            if(preg_match($routeRegex, $requestedRoute)) {
                return $definedRoute;
            }
        }

        return null;
    }

    /**
     * Finds paths to all component config files.
     *
     * @return array
     */
    private function findComponentConfigFiles()
    {
        $configurableComponents = [];

        $componentsRootPath = $this->appRoot.'/components';
        $componentsPathContents = scandir($componentsRootPath);

        foreach($componentsPathContents as $path) {
            $componentConfigPath = sprintf('%s/%s/config.php', $componentsRootPath, $path);

            if('..' !== $path && is_file($componentConfigPath)) {
                $configurableComponents[] = $componentConfigPath;
            }
        }

        return $configurableComponents;
    }
}