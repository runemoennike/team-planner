<?php

namespace App\Components\Projects;

use Core\AbstractController;
use App\Model\Repository\ProjectRepository;
use App\Model\Repository\PersonRepository;

class ManningController extends AbstractController
{
    /**
     * Man a project.
     */
    public function manAction() {
        // Input parameters.
        $projectId = is_numeric($_GET['projectId'] ?? null) ? $_GET['projectId'] : null;

        // Data.
        $projectRepository = new ProjectRepository();
        $personRepository = new PersonRepository();

        $project = $projectRepository->find($projectId);

        if('POST' === $_SERVER['REQUEST_METHOD']) {
            // Handle people.
            $project->people = [];
            if(!empty($_POST['people'])) {
                foreach($_POST['people'] as $personId) {
                    if(is_numeric($personId)) {
                        $project->people[] = $personRepository->find($personId);
                    }
                }
            }

            // Persist.
            $projectRepository->save($project);

            // Refresh self.
            $this->redirect(URL_BASE.'projects/man'.URL_PARAM_START.'projectId='.$projectId);
        }

        // Show form.
        $this->pageTitle = 'Man project';
        $this->project = $project;
        $this->people = $personRepository->findAll();

        // Render.
        $this->setLayoutPage('layout.html.php');
        $this->render('man.html.php');
    }

    /**
     * Report on manning of a project.
     */
    public function reportAction() {
        // Input parameters.
        $projectId = is_numeric($_GET['projectId'] ?? null) ? $_GET['projectId'] : null;

        // Data.
        $projectRepository = new ProjectRepository();
        $project = $projectRepository->find($projectId);

        $this->pageTitle = 'Manning report';
        $this->project = $project;

        // Render.
        $this->setLayoutPage('layout.html.php');
        $this->render('report.html.php');
    }
}