<?php
/**
 * PlacementController.php
 */

namespace Phizzle;

/**
 * Class PlacementController
 * @package Phizzle
 */
class PlacementController
{
    /**
     * PlacementController constructor.
     * 
     * @param \Twig_Environment $twig
     * @param array $parameters
     */
    public function __construct(\Twig_Environment $twig, $parameters = array())
    {

        if (0 < count($parameters)) {

            $action = array_shift($parameters);

            switch (strtolower( filter_var($action, FILTER_SANITIZE_STRING) )) {
                case 'create' :
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Process POST : Create new Placement
                        $this->createAction($twig);
                    } else {
                        // Render Create new Placement form
                        $placement = new Placement;
                        $this->showFormAction($twig, $placement);
                    }
                    break;
                case 'update' :
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Process POST : Update Placement
                        $this->updateAction($twig);
                    } else {
                        $placement_id = filter_var( array_shift($parameters), FILTER_SANITIZE_NUMBER_INT );
                        $db = new PlacementRepository;
                        if (! $placement = $db->getOneById($placement_id) ) {
                            // Delete the Placement from the database
                            $placement = new Placement;
                            // Log the action
                        }
                        // Render Update Placement form
                        $this->showFormAction($twig, $placement);
                    }
                    break;
                case 'delete' :
                    // Delete the Placement
                    $this->deleteAction($twig, $parameters);
                    break;
                default:
                    // Display the Placement
                    $this->showAction($twig, $parameters);
                    break;
            }
        } else {
            $this->indexAction($twig);
        }
    }

    /**
     * Display the list of Placements using a twig template
     *
     * @param \Twig_Environment $twig
     */
    public function indexAction(\Twig_Environment $twig)
    {
    	$db = new PlacementRepository;
        $data = array( 'username' => Utility::usernameFromSession(), 
                	   'active_page' => 'admin/placement',
                	   'placement_list' => $db->getAll()
                	   );


        print $twig->render('admin/placement-list.html.twig', $data);
    }

    /**
     * @param \Twig_Environment $twig
     */
    public function createAction(\Twig_Environment $twig)
    {
        if (! Utility::checkUserIsAuthorised()) {
            Utility::doLoginRedirect();
        } else {

            // Create a new Placement object

            $placement_id = null;
            $placement = new Placement;
            $db = new PlacementRepository;

            $placement->setDescription( filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING) );
            $placement->setDeadline( filter_input(INPUT_POST, 'deadline', FILTER_SANITIZE_STRING) );
            $placement->setCompany_url( filter_input(INPUT_POST, 'company_url', FILTER_SANITIZE_STRING) );
            $placement->setCompany( filter_input(INPUT_POST, 'company', FILTER_SANITIZE_STRING) );
            $placement->setRole( filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING) );
            $placement->setName( filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING) );

            $placement_id = $db->create($placement);

            if (!$placement_id) {
                $this->showFormAction($twig, $placement);
            } else {
                // Redirect to index
                header("Location: /?admin/placement");
                exit();
            }

        }
    }

    /**
     * @param \Twig_Environment $twig
     */
    public function updateAction(\Twig_Environment $twig)
    {
        if (! Utility::checkUserIsAuthorised()) {
            Utility::doLoginRedirect();
        } else {

            $placement_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

            $db = new placementRepository;

            // get the Placement object from the database
            $placement = $db->getOneById($placement_id);

            $placement->setDescription( filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING) );
            $placement->setDeadline( filter_input(INPUT_POST, 'deadline', FILTER_SANITIZE_STRING) );
            $placement->setCompany_url( filter_input(INPUT_POST, 'company_url', FILTER_SANITIZE_STRING) );
            $placement->setCompany( filter_input(INPUT_POST, 'company', FILTER_SANITIZE_STRING) );
            $placement->setRole( filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING) );
            $placement->setName( filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING) );

            $result = $db->update($placement, $placement_id);

            if (! $result) {
                $this->showFormAction($twig, $placement);
            } else {
                // Redirect to index
                header("Location: /?admin/placement");
                exit();
            }

        }
    }

    /**
     * Deletes a Placement
     *
     * @param \Twig_Environment $twig
     * @param array             $param
     */
    public function deleteAction(\Twig_Environment $twig, $param = array())
    {
        if ( ! Utility::checkUserIsAuthorised() ) {
            Utility::doLoginRedirect();
        } else {
            // Get the 'id' of the Placement object to be deleted
            $placement_id = filter_var( array_shift($param), FILTER_SANITIZE_NUMBER_INT );
            // Check that a Placement with that 'id' exists
            $db = new PlacementRepository;
            if ( $placement = $db->getOneById($placement_id) ) {
                // Delete the Placement from the database
                $db->delete( $placement->getId() );
                // Log the action
            }
            // Send User to Placement listings
            header("Location: /?admin/placement");
            print $twig->render('admin/placement-list.html.twig');
            exit();
        }
    }

    /**
     * Displays the form to create/update a Placement
     *
     * @param \Twig_Environment $twig
     * @param Placement         $placement
     */
    public function showFormAction(\Twig_Environment $twig, Placement $placement)
    {
        $data = array(
            'active_page' => 'admin/placement',
            'username' => Utility::usernameFromSession(),
            'placement' => $placement
        );

        print $twig->render('admin/placement-form.html.twig', $data);
    }

    /**
     * Displays the Placement details using a twig template
     *
     * @param \Twig_Environment $twig
     * @param array             $param
     */
    public function showAction(\Twig_Environment $twig, $param = array())
    {
        $db = new PlacementRepository;

        // Get the 'id' of the Placement object to display
        $placement_id = filter_var( array_shift($param), FILTER_SANITIZE_NUMBER_INT );

        // Populate the data array
        $data = array(
            'username' => Utility::usernameFromSession(),
            'placement' => $db->getOneById($placement_id)
        );

        // Show Placement
        print $twig->render('admin/placement.html.twig', $data);
    }

}