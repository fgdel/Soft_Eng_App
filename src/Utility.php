<?php
/**
 * Utility.php
 */

namespace Phizzle;

/**
 * Class Utility
 * @package Phizzle
 * A utility class to provide some common functionality across the website.
 */
class Utility
{
    /**
     * Check if user is logged in
     * @return bool Returns true if 'user' variable is found in session
     */
    public static function isLoggedInFromSession() {

        // Default variable to false. i.e. Not logged in
        $isLoggedIn = false;

        // User is logged in if there is a 'user' entry in the SESSION superglobal
        if (isset($_SESSION['user'])) {
            $isLoggedIn = true;
        }

        return $isLoggedIn;

    }

    /**
     * Get the username from the session
     * @return string
     */
    public static function usernameFromSession() {

        // Default to empty username
        $username = '';

        // Extract the username value from the SESSION superglobal
        if (isset($_SESSION['user'])) {
            $username = $_SESSION['user'];
        }

        return $username;
        
    }

    /**
     * Get the user_id from the session
     * @return string
     */
    public static function userIdFromSession() {

        // Default to empty userId
        $user_id = '';

        // Extract the user_id value from the SESSION superglobal
        if (isset($_SESSION['id'])) {
            $user_id = $_SESSION['id'];
        }

        return $user_id;

    }

    /**
     * Redirect user to the Login form
     * Sets a redirect variable in the session to redirect the user back to the page after login.
     */
    public static function doLoginRedirect() {

        // Set the session variable for redirect to return to requested page after successful login
        $_SESSION['redirect'] = $_SERVER['REQUEST_URI']; // e.g. /index.php?action=screen
        // Go to login page
        header("HTTP/1.1 403 Unauthorised");
        header("Location: " . "/?login");
        exit();

    }

    /**
     * Uses the 'username' and 'role' variables in SESSION to determine if the user is an authorised admin
     * @return bool
     */
    public static function isUserAuthorised() {

        $authorised = false;

        // User and Role are set in the SESSION super global
        $username = $_SESSION['user'];
        $role = $_SESSION['role'];

        $db = new \Phizzle\UserRepository;

        if ($db->canFindUsernameWithAdminRole($username, $role)) {
            $authorised = true;
        }

        return $authorised;

    }

    /**
     * Redirects to login screen if User is not logged in. Otherwise checks if the User is an authorised admin.
     * @return bool
     */
    public static function checkUserIsAuthorised() {

        if (! self::isLoggedInFromSession()) {
            // User is not logged in!
            header("HTTP/1.1 403 Unauthorised");
            header("Location: " . "/index.php");
            exit();
        }

        return self::isUserAuthorised();

    }
}