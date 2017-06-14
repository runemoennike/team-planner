<?php

namespace App\Components\Main;

use App\Model\Repository\PersonRepository;
use App\Model\Repository\SkillRepository;
use App\Model\Repository\TechnologyRepository;
use Core\AbstractController;
use Core\InputParsing;

class MainController extends AbstractController
{
    protected $hasSubmitted = false;

    /**
     * List people.
     */
    public function homeAction() {
        // Data.
        $this->pageTitle = 'Home';

        // Render
        $this->setLayoutPage('layout.html.php');
        $this->render('home.html.php');
    }
}