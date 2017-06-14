<?php

namespace App\Components\People;

use App\Model\Repository\PersonRepository;
use App\Model\Repository\SkillRepository;
use App\Model\Repository\TechnologyRepository;
use Core\AbstractController;
use Core\InputParsing;

class PeopleController extends AbstractController
{
    protected $hasSubmitted = false;

    /**
     * List people.
     */
    public function listAction() {
        // Data.
        $personRepository = new PersonRepository();

        $this->pageTitle = 'People';
        $this->people = $personRepository->findAll();

        // Render
        $this->setLayoutPage('layout.html.php');
        $this->render('list.html.php');
    }

    /**
     * Edit a person.
     */
    public function editAction() {
        // Input parameters.
        $personId = is_numeric($_GET['personId'] ?? null) ? $_GET['personId'] : null;

        // Data.
        $personRepository = new PersonRepository();
        $skillRepository = new SkillRepository();
        $technologyRepository = new TechnologyRepository();

        $person = $personRepository->find($personId);

        if('POST' === $_SERVER['REQUEST_METHOD']) {
            // Handle submitted form data.
            $person->name = InputParsing::cleanText($_POST['name']);
            $person->email = InputParsing::cleanText($_POST['email']);
            $person->phone = InputParsing::cleanText($_POST['phone']);
            $person->education = InputParsing::cleanText($_POST['education']);
            $person->hiredYear = InputParsing::cleanText($_POST['hiredYear']);

            // Handle skills.
            $person->skills = [];
            if(!empty($_POST['skills'])) {
                foreach($_POST['skills'] as $skillId) {
                    if(is_numeric($skillId)) {
                        $person->skills[] = $skillRepository->find($skillId);
                    }
                }
            }

            // Handle technologies.
            $person->technologies = [];
            if(!empty($_POST['technologies'])) {
                foreach($_POST['technologies'] as $technologyId) {
                    if(is_numeric($technologyId)) {
                        $person->technologies[] = $technologyRepository->find($technologyId);
                    }
                }
            }

            // Persist.
            $personRepository->save($person);

            // Handle profile picture upload.
            $uploadedFileTmpName = $_FILES['image']['tmp_name'];

            if(is_uploaded_file($uploadedFileTmpName)) {
                move_uploaded_file($uploadedFileTmpName, sprintf('%s/%d.jpeg', UPLOAD_PATH, $person->id));
            }

            // Back to list.
            $this->redirect(URL_BASE.'people/list');
        }

        // Show form.
        $this->pageTitle = 'Edit person';
        $this->person = $person;
        $this->skills = $skillRepository->findAll();
        $this->technologies = $technologyRepository->findAll();

        // Render.
        $this->setLayoutPage('layout.html.php');
        $this->render('edit.html.php');
    }
}