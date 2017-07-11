<?php
namespace Phizzle;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Mattsmithdev\PdoCrud\DatabaseTable;

$parent_directory = dirname( dirname(__FILE__) );
define('TEMPLATE_DIRECTORY', $parent_directory . '/templates');

class MainController
{

    public function loginAction(\Twig_Environment $twig)
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // login form has been submitted

            $isLoggedIn = false; //default is bad login

            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

            // search for user with username in repository
            $userDb = new UserRepository();

            $isLoggedIn = $userDb->canFindMatchingUsernameAndPassword($username, $password);

            if ($isLoggedIn) {

                $user = $userDb->getOnebyUsername($username);
                //STORE login status SESSION
                $_SESSION['user'] = $username;
                $_SESSION['role'] = $user->getRole();
                // set the redirect location
                $redirect = isset($_SESSION['redirect']) ? $_SESSION['redirect'] : 'index.php';
                // perform browser redirect
                header("Location: " . $redirect);
                // end request
                exit();

            } else {

                print $twig->render('message.html.twig');

            }

        } else {

            $data = array( 'page_title' => 'Login' );

            if ( Utility::isLoggedInFromSession() ) {
                $data['username'] = Utility::usernameFromSession();
            }

            print $twig->render('login.html.twig', $data);

        }

    }

    public function logoutAction()
    {

        if ( Utility::isLoggedInFromSession() ) {
            // ensure there is a session
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            // end the session
            session_destroy();
            // redirect to home page
            header("Location: index.php");
            exit();
        }

    }

    public function registerAction(\Twig_Environment $twig)
    {
        // create a log channel
        $logger = new Logger('name');
        $logger->pushHandler(new StreamHandler(__DIR__.'/../my_app.log', Logger::DEBUG));

        // Check that this is a POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // If the POST variable 'register' exists (the form submit button),
            // we can assume that the user has submitted the registration form.
            $logger->addInfo('A form was posted.');

            $logger->addInfo('We have a registration form.');

            $validation_rules = array(
                'username' => array( 'type' => 'string', 'required' => true, 'min' => 3, 'max' => 50, 'trim' => true ),
                'email' => array( 'type' => 'email', 'required' => true, 'trim' => true ),
                'firstname' => array( 'type' => 'string', 'required' => true, 'min' => 1, 'max' => 50, 'trim' => true ),
                'lastname' => array( 'type' => 'string', 'required' => true, 'min' => 2, 'max' => 50, 'trim' => true ),
                'password' => array( 'type' => 'string', 'required' => true, 'min' => 8, 'max' => 50, 'trim' => true )
            );

            $validate = new validation;
            $validate->addRules($validation_rules);
            $validate->addSource($_POST);
            $validate->run();

            if (sizeof($validate->errors) > 0) {
                print_r($validate->errors);
            } else {
                // create a new User object
                $newSignup = new User;
                // populate the User properties
                $newSignup->setUsername( $validate->sanitized['username'] );
                $newSignup->setFirstname( $validate->sanitized['firstname'] );
                $newSignup->setLastname( $validate->sanitized['lastname'] );
                $newSignup->setEmail( $validate->sanitized['email'] );
                $newSignup->setPassword( $validate->sanitized['password'] );
                // assign User role
                $newSignup->setRole( 1 );
                // save User to database
                $id = UserRepository::create($newSignup);
                if ($id > -1) {
                    // ensure there is a session
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    // store the username in the session
                    $_SESSION['user'] = $newSignup->getUsername();
                    $_SESSION['role'] = $newSignup->getRole();
                    // render the welcome template
                    print $twig->render('thanks.html.twig', array( 'firstname' => $newSignup->getFirstname(), 'username' => $newSignup->getUsername() ));
                } else {
                    // database error
                    $logger->addInfo('We have a database error.');
                }

            }

        } else {

            $data = array( 'page_title' => 'Register' );

            if ( Utility::isLoggedInFromSession() ) {
                // ensure there is a session
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                // end the session
                session_destroy();
            }

            print $twig->render('register.html.twig', $data);

        }

    }

    public function newsAction(\Twig_Environment $twig)
    {

        if (! Utility::isLoggedInFromSession() ) {
            Utility::doLoginRedirect();
        } else {

            // Store RSS feed from Gamasutra using the DOMDocument class
            // See: http://php.net/manual/en/class.domdocument.php
            $rss = new \DOMDocument();
            // Load the XML from Feedburner.com
            $rss->load('http://feeds.feedburner.com/GamasutraNews');
            // Array of feed elements to pass to the Twig template
            $feed = array();
            /*
             * Process each element of the RSS feed.
             *
             *  <item>
             *    <title>What does Nintendo need to do to make the NX a success?</title>
             *    <link>http://feedproxy.google.com/~r/GamasutraNews/~3/DOXxjxWRhW8/What_does_Nintendo_need_to_do_to_make_the_NX_a_success.php</link>
             *    <description>&lt;p&gt;&lt;img src="http://www.gamasutra.com/db_area/images/news/2016/May/272174/500_NOA.jpg" align="left" hspace="5"/&gt;What can Nintendo do ...</description>
             *    <guid isPermaLink="false">http://www.gamasutra.com/view/news/272174</guid>
             *    <pubDate>Fri, 6 May 2016 19:35:00 -0400</pubDate>
             *    <feedburner:origLink>http://www.gamasutra.com/view/news/272174/What_does_Nintendo_need_to_do_to_make_the_NX_a_success.php</feedburner:origLink>
             *  </item>
             *
             * Need to transform the content to present it in the news template - the images are different sizes which isn't ideal.
             */
            foreach ($rss->getElementsByTagName('item') as $node) {

                // Get the content from the <description> tag
                $content = $node->getElementsByTagName('description')->item(0)->nodeValue;
                $dom = new \DOMDocument();
                @$dom->loadHTML($content);
                // Get the images from the <description> tag content
                $images = $dom->getElementsByTagName('img');
                // Get the text of the <description> tag content
                $text = preg_replace("/<img[^>]+\>/i", "", $node->getElementsByTagName('description')->item(0)->nodeValue);
                // Add the elements to an array for use by the Twig template
                $item = array (
                    'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                    'image' => $images[0]->attributes['src']->nodeValue,
                    'text' => $text,
                    'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
                    'date' => date('l F d, Y', strtotime($node->getElementsByTagName('pubDate')->item(0)->nodeValue))
                );
                // Add item to feed array
                array_push($feed, $item);

            }

            $data = array(
                'page_title' => 'News',
                'active_page' => 'news',
                'username' => Utility::usernameFromSession(),
                'isAdmin' => Utility::isUserAuthorised(),
                'feed' => $feed
            );

            print $twig->render('news.html.twig', $data);
        }

    }

    public function profileAction(\Twig_Environment $twig)
    {

        if (! Utility::isLoggedInFromSession() ) {
            Utility::doLoginRedirect();
        } else {
            $data = array(
                'page_title' => 'Profile',
                'active_page' => 'profile',
                'username' => Utility::usernameFromSession(),
                'isAdmin' => Utility::isUserAuthorised()
            );
            print $twig->render('profile.html.twig', $data);
        }

    }

    public function indexAction(\Twig_Environment $twig)
    {



        $data = array('page_title' => 'Home');

        if (!Utility::isLoggedInFromSession()) {
            Utility::doLoginRedirect();
        } else {
            $data['username'] = Utility::usernameFromSession();
            $data['isAdmin'] = Utility::isUserAuthorised();

            print $twig->render('home.html.twig', $data);
        }
    }

}