<?php

namespace App\Components\Main;

use Core\AbstractController;

class MainController extends AbstractController
{
    /**
     * Home.
     */
    public function homeAction() {
        // Data.
        $this->pageTitle = 'Home';

        // Render
        $this->setLayoutPage('layout.html.php');
        $this->render('home.html.php');
    }
}