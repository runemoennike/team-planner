<?php

namespace Core;

abstract class AbstractController
{
    private $layoutPagePath;
    private $rootPath;
    private $content;
    private $appRoot;

    /**
     * AbstractController constructor.
     * @param string $appRoot
     */
    function __construct($appRoot)
    {
        $this->appRoot = $appRoot;
    }

    /**
     * Sets the root path for this controller's component.
     *
     * @param string $path
     */
    public function setRootPath($path)
    {
        $this->rootPath = $path;
    }

    /**
     * Sets the page that wraps the content.
     *
     * @param string $layoutFilename
     */
    protected function setLayoutPage($layoutFilename)
    {
        $this->layoutPagePath = $this->appRoot.'/'.$layoutFilename;
    }

    /**
     * Performs the rendering of a given view file.
     *
     * @param string $viewFilename
     */
    protected function render($viewFilename)
    {
        $viewPath = $this->rootPath.'/'.$viewFilename;

        // Capture view.
        ob_start();
        require($viewPath);
        $this->content = ob_get_clean();

        // Invoke layout page.
        require($this->layoutPagePath);
    }

    protected function redirect($url)
    {
        header('Location: '.$url);

        exit();
    }
}