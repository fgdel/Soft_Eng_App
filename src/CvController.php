<?php

namespace Phizzle;

/**
 * Class CvController
 * @package Phizzle
 */
class CvController
{
    public function __construct(\Twig_Environment $twig, $parameters = array())
    {

        if (0 < count($parameters)) {
            $action = array_shift($parameters);
            switch (strtolower( filter_var($action, FILTER_SANITIZE_STRING) )) {
                case 'create' :
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Process POST : Create new CV
                        $this->createAction($twig);
                    } else {
                        // Render Create new CV form
                        $cv = new \Phizzle\Cv;
                        $this->showFormAction($twig, $cv);
                    }
                    break;
                case 'update' :
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Process POST : Update CV
                        $this->updateAction($twig);
                    } else {
                        $video_id = filter_var( array_shift($parameters), FILTER_SANITIZE_NUMBER_INT );
                        $db = new \Phizzle\CvRepository;
                        if (! $cv = $db->getOneById($cv_id) ) {
                            // Delete the CV from the database
                            $cv = new \Phizzle\Cv;
                        }
                        // Render Update Video form
                        $this->showFormAction($twig, $cv);
                    }
                    break;
                case 'delete' :
                    // Delete the CV
                    $this->deleteAction($twig, $parameters);
                    break;
                default:
                    // Display the CV
                    $this->showAction($twig, $parameters);
                    break;
            }
        } else {
            $this->indexAction($twig);
        }
    }

    public function indexAction(\Twig_Environment $twig)
    {
        $db = new \Phizzle\CvRepository;
        $data = array(
            'active_page' => 'cv',
            'username' => Utility::usernameFromSession(),
            'cv_list' => $db->getAll()
        );


        print $twig->render('cv-list.html.twig', $data);
    }

    public function createAction(\Twig_Environment $twig)
    {
        if ( ! Utility::checkUserIsAuthorised() ) {
            Utility::doLoginRedirect();
        } else {

            $cv_id = null;
            $cv = new \Phizzle\Cv;
            $db = new \Phizzle\CvRepository;

            $cv->setTitle( filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING) );
            $cv->setScreen( filter_input(INPUT_POST, 'screen', FILTER_SANITIZE_STRING) );
            $cv->setUrl( filter_input(INPUT_POST, 'url', FILTER_SANITIZE_STRING) );
            $cv->setDescription( filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING) );
            $cv_id = $db->create($video);

            if (!$cv_id) {
                $this->showFormAction($twig, $cv);
            } else {
                header("Location: /?cv");
                exit();
            }

        }
    }

    public function updateAction(\Twig_Environment $twig)
    {
        if ( ! Utility::checkUserIsAuthorised() ) {
            Utility::doLoginRedirect();
        } else {
            $data = array( 'username' => Utility::usernameFromSession() );

            $cv_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

            $db = new \Phizzle\CvRepository;

            // get the CV object from the database
            $cv = $db->getOneById($cv_id);

            $cv->setTitle( filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING) );
            $cv->setScreen( filter_input(INPUT_POST, 'screen', FILTER_SANITIZE_STRING) );
            $cv->setUrl( filter_input(INPUT_POST, 'url', FILTER_SANITIZE_STRING) );
            $cv->setDescription( filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING) );

            $result = $db->update($cv, $cv_id);

            if (! $result) {
                $this->showFormAction($twig, $cv);
            } else {
                header("Location: /?cv");
                exit();
            }

        }
    }

    public function deleteAction(\Twig_Environment $twig, $param = array())
    {
        if (! Utility::checkUserIsAuthorised() ) {
            Utility::doLoginRedirect();
        } else {
            // Get the 'id' of the Cv object to be deleted
            $video_id = filter_var( array_shift($param), FILTER_SANITIZE_NUMBER_INT );
            // Check that a Cv with that 'id' exists
            $db = new \Phizzle\CvRepository;
            if ( $cv = $db->getOneById($cv_id) ) {
                // Delete the CV from the database
                $db->delete( $cv->getId() );
                // Log the action
            }
            // Send User to CV listings
            header("Location: /?cv");
            exit();
        }
    }

    public function showFormAction(\Twig_Environment $twig, \Phizzle\Video $video)
    {
        $data = array(
            'active_page' => 'cv',
            'username' => Utility::usernameFromSession(),
            'cv' => $cv
        );
        print $twig->render('cv-form.html.twig', $data);
    }

    public function showAction(\Twig_Environment $twig, $param = array())
    {
        $cv_id = filter_var( array_shift($param), FILTER_SANITIZE_NUMBER_INT );
        $db = new \Phizzle\CvRepository;

        $data = array(

            'username' => Utility::usernameFromSession(),
            'cv' => $db->getOneById($cv_id)
        );

        // Show Cv
        print $twig->render('cv.html.twig', $data);
    }

}