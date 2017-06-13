<?php

namespace Core;

abstract class AbstractController
{
    private $masterPagePath;
    private $rootPath;
    private $content;
    private $appRoot;

    /**
     * AbstractController constructor.
     * @param $appRoot
     */
    function __construct($appRoot)
    {
        $this->appRoot = $appRoot;
    }

    /**
     * Sets the root path for this controller's component.
     *
     * @param $path
     */
    public function setRootPath($path)
    {
        $this->rootPath = $path;
    }

    /**
     * Sets the page that wraps the content.
     *
     * @param $path
     */
    protected function setLayoutPage($path)
    {
        $this->masterPagePath = $this->appRoot.'/'.$path;
    }

    /**
     * Performs the rendering of a given view file.
     *
     * @param $view
     */
    protected function render($view)
    {
        $viewPath = $this->rootPath.'/'.$view;

        // Capture view.
        ob_start();
        require($viewPath);
        $this->content = ob_get_clean();

        // Invoke master page.
        require($this->masterPagePath);
    }

    protected function redirect($url)
    {
        header('Location: '.$url);

        exit();
    }
}