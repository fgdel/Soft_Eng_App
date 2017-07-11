<?php

namespace Phizzle;

/**
 * Class ProfileController
 * @package Phizzle
 */
class ProfileController
{
    /**
     * ProfileController constructor.
     * @param \Twig_Environment $twig
     * @param array $parameters
     */
    public function __construct(\Twig_Environment $twig, $parameters = array())
    {
        // Check that the Parameters array is not empty
        if (0 < count($parameters)) {
            // The first element of the array will contain the 'action' to be performed
            $action = array_shift($parameters);
            // Use the filtered action value to determine which class method to call
            switch (strtolower(filter_var($action, FILTER_SANITIZE_STRING))) {
                case 'create' :
                    // Check if this is a POST request
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Process POST : Create new Profile
                        $this->createAction($twig);
                    } else {
                        // Render Add New Profile form
                        $profile = new Profile;
                        $this->showFormAction($twig, $profile);
                    }
                    break;
                case 'update' :
                    // Check if this is a POST request
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Process POST : Update Profile
                        $this->updateAction($twig);
                    } else {
                        // Prepare for the Update Profile form
                        // Get the 'id' from the parameters array
                        $profile_id = filter_var(array_shift($parameters), FILTER_SANITIZE_NUMBER_INT);
                        // Query the database to get the Profile
                        $db = new ProfileRepository;
                        if (!$profile = $db->getOneById($profile_id)) {
                            // The Profile was not in the database so use an empty Profile object instead
                            $profile = new Profile;
                        }
                        // Render the Update User form
                        $this->showFormAction($twig, $profile);
                    }
                    break;
                default:
                    // Display the User
                    $this->showAction($twig, $parameters);
                    break;
            }
        } else {
            // No Parameters were provided so display the index
            $this->indexAction($twig);
        }
    }

    /**
     * @param \Twig_Environment $twig
     */
    public function indexAction(\Twig_Environment $twig)
    {
        $username = Utility::usernameFromSession();
        $db = new ProfileRepository;
        $data = array(
            'active_page' => 'dashboard/profile',
            'username' => Utility::usernameFromSession(),
            'profile_list' => $db->getOneById($username)
        );
        print $twig->render('dashboard/profile.html.twig', $data);
    }

    /**
     * @param \Twig_Environment $twig
     */
    public function updateAction(\Twig_Environment $twig)
    {
        if (!Utility::checkUserIsAuthorised()) {
            Utility::doLoginRedirect();
        } else {

            $profile_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

            $db = new ProfileRepository;

            // Get the User object from the database
            $profile = $db->getOneById($profile_id);
            // Update the object properties
            $profile->setUsername(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
            $profile->setFirstname(filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING));
            $profile->setLastname(filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING));
            $profile->setEmail(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING));
            $profile->setRole(filter_input(INPUT_POST, 'role', FILTER_SANITIZE_NUMBER_INT));
            $profile->setWebsite(filter_input(INPUT_POST, 'website', FILTER_SANITIZE_STRING));
            $profile->setDescription(filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING));
            $profile->setStatus(filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING));

            // Update the database record
            $result = $db->update($profile, $profile_id);

            if (!$result) {
                // In the event of a problem return to the form
                $this->showFormAction($twig, $profile);
            } else {
                // Update was successful. Display the User list
                header("Location: /?profile");
                exit();
            }

        }
    }

    /**
     * @param \Twig_Environment $twig
     * @param \Phizzle\User $user
     */
    public function showFormAction(\Twig_Environment $twig, Profile $profile)
    {
        $data = array(
        	'active_page' => 'profile',
            'username' => Utility::usernameFromSession(),
            'profile' => $profile
        );
        print $twig->render('profile-form.html.twig', $data);
    }

    /**
     * @param \Twig_Environment $twig
     * @param array $param
     */
    public function showAction(\Twig_Environment $twig, $param = array())
    {

        $profile_id = filter_var(array_shift($param), FILTER_SANITIZE_NUMBER_INT);

        $db = new ProfileRepository;

        $data = array(
            'username' => Utility::usernameFromSession(),
            'profile' => $db->getOneById($profile_id)
        );

        // Display User
        print $twig->render('profile-form.html.twig', $data);

    }

}