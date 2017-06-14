<?php

namespace App\Components\Projects;

use Core\AbstractController;
use Core\InputParsing;
use App\Model\Repository\ProjectRepository;
use App\Model\Repository\SkillRepository;

class ProjectsController extends AbstractController
{
    /**
     * List projects.
     */
    public function listAction() {
        // Data.
        $projectRepository = new ProjectRepository();

        $this->pageTitle = 'Projects';
        $this->projects = $projectRepository->findAll();

        // Render
        $this->setLayoutPage('layout.html.php');
        $this->render('list.html.php');
    }

    /**
     * Edit/add a project.
     */
    public function editAction() {
        // Input parameters.
        $projectId = is_numeric($_GET['projectId'] ?? null) ? $_GET['projectId'] : null;

        // Data.
        $projectRepository = new ProjectRepository();
        $skillRepository = new SkillRepository();

        $project = $projectRepository->find($projectId);

        if('POST' === $_SERVER['REQUEST_METHOD']) {
            // Handle submitted form data.
            $project->name = InputParsing::cleanText($_POST['name']);
            $project->description = InputParsing::cleanText($_POST['description']);

            // Handle skills.
            $project->skills = [];
            if(!empty($_POST['skills'])) {
                foreach($_POST['skills'] as $skillId) {
                    if(is_numeric($skillId)) {
                        $project->skills[] = $skillRepository->find($skillId);
                    }
                }
            }

            // Persist.
            $projectRepository->save($project);

            // Back to list.
            $this->redirect(URL_BASE.'projects/list');
        }

        // Show form.
        $this->pageTitle = empty($projectId) ? 'Add project' : 'Edit project';
        $this->project = $project;
        $this->skills = $skillRepository->findAll();

        // Render.
        $this->setLayoutPage('layout.html.php');
        $this->render('edit.html.php');
    }
}