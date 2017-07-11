<?php

namespace Phizzle;

/**
 * Class UserController
 * @package Phizzle
 */
class UserController
{
    /**
     * UserController constructor.
     * @param \Twig_Environment $twig
     * @param array $parameters
     */
    public function __construct(\Twig_Environment $twig, $parameters = array())
    {
        // Check that the Parameters array is not empty
        if (0 < count($parameters)) {
            // The first element of the array will contain the 'action' to be performed
            $action = array_shift($parameters);
            // Use the filtered action value to  determine which class method to call
            switch (strtolower(filter_var($action, FILTER_SANITIZE_STRING))) {
                case 'create' :
                    // Check if this is a POST request
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Process POST : Create new User
                        $this->createAction($twig);
                    } else {
                        // Render Add New User form
                        $user = new User;
                        $this->showFormAction($twig, $user);
                    }
                    break;
                case 'update' :
                    // Check if this is a POST request
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Process POST : Update User
                        $this->updateAction($twig);
                    } else {
                        // Prepare for the Update User form
                        // Get the 'id' from the parameters array
                        $user_id = filter_var(array_shift($parameters), FILTER_SANITIZE_NUMBER_INT);
                        // Query the database to get the User
                        $db = new UserRepository;
                        if (!$user = $db->getOneById($user_id)) {
                            // The User was not in the database so use an empty User object instead
                            $user = new User;
                        }
                        // Render the Update User form
                        $this->showFormAction($twig, $user);
                    }
                    break;
                case 'delete' :
                    // Delete the User
                    $this->deleteAction($twig, $parameters);
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
        $db = new UserRepository;
        $data = array(
        	'active_page' => 'admin/user',
            'username' => Utility::usernameFromSession(),
            'user_list' => $db->getAll()
        );
        print $twig->render('admin/user-list.html.twig', $data);
    }

    /**
     * @param \Twig_Environment $twig
     */
    public function createAction(\Twig_Environment $twig)
    {
        if (!Utility::checkUserIsAuthorised()) {
            Utility::doLoginRedirect();
        } else {
            // Create a new User object
            $user = new User;
            $db = new UserRepository;

            // Populate the User object
            $user->setUsername(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
            $user->setFirstname(filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING));
            $user->setLastname(filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING));
            $user->setEmail(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING));
            $user->setRole(filter_input(INPUT_POST, 'role', FILTER_SANITIZE_NUMBER_INT));
            $user->setPassword(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));

            // Add the new User to the database
            $user_id = $db->create($user);

            if (!$user_id) {
                // An error occurred. Return to the form
                $this->showFormAction($twig, $user);
            } else {
                // User added to the database. Display the User list
                header("Location: /?admin/user");
                exit();
            }

        }
    }

    /**
     * @param \Twig_Environment $twig
     */
    public function updateAction(\Twig_Environment $twig)
    {
        if (!Utility::checkUserIsAuthorised()) {
            Utility::doLoginRedirect();
        } else {

            $user_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

            $db = new UserRepository;

            // Get the User object from the database
            $user = $db->getOneById($user_id);
            // Update the object properties
            $user->setUsername(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
            $user->setFirstname(filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING));
            $user->setLastname(filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING));
            $user->setEmail(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING));
            $user->setRole(filter_input(INPUT_POST, 'role', FILTER_SANITIZE_NUMBER_INT));
            // The password is a special case : only update if a new value has been submitted
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
            if (!empty($password)) {
                $user->setPassword($password);
            }
            // Update the database record
            $result = $db->update($user, $user_id);

            if (!$result) {
                // In the event of a problem return to the form
                $this->showFormAction($twig, $user);
            } else {
                // Update was successful. Display the User list
                header("Location: /?admin/user");
                exit();
            }

        }
    }

    /**
     * @param \Twig_Environment $twig
     * @param array $param
     */
    public function deleteAction(\Twig_Environment $twig, $param = array())
    {
        if (!Utility::checkUserIsAuthorised()) {
            Utility::doLoginRedirect();
        } else {
            $db = new UserRepository;
            // Get the 'id' of the User record to be deleted
            $delete_id = filter_var(array_shift($param), FILTER_SANITIZE_NUMBER_INT);
            // Get the 'id' of the current admin user from the SESSION super global variable
            $current_user = $db->getOneByUsername($_SESSION['user']);
            // Two checks to perform:
            // #1 : Check that the user is not trying to delete their own record
            // #2 : Check that a User with the provide 'id' exists in the database
            if (($delete_id <> $current_user->getId()) && $user = $db->getOneById($delete_id)) {
                // Delete the User from the database
                $db->delete($user->getId());
            }
            // Return to the User listings
            header("Location: /?admin/user");
            exit();
        }
    }

    /**
     * @param \Twig_Environment $twig
     * @param \Phizzle\User $user
     */
    public function showFormAction(\Twig_Environment $twig, User $user)
    {
        $data = array(
        	'active_page' => 'admin/user',
            'username' => Utility::usernameFromSession(),
            'user' => $user
        );
        print $twig->render('admin/user-form.html.twig', $data);
    }

    /**
     * @param \Twig_Environment $twig
     * @param array $param
     */
    public function showAction(\Twig_Environment $twig, $param = array())
    {

        $user_id = filter_var(array_shift($param), FILTER_SANITIZE_NUMBER_INT);

        $db = new UserRepository;

        $data = array(
            'username' => Utility::usernameFromSession(),
            'user' => $db->getOneById($user_id)
        );

        // Display User
        print $twig->render('user-form.html.twig', $data);

    }

}