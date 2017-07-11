<?php
/**
 * DashboardController.php
 */
namespace Phizzle;

use Phizzle\DeveloperController;
//use Phizzle\ProductController;
use Phizzle\UserController;
use Phizzle\VideoController;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

/**
 * Class DashboardController
 *
 * @package Phizzle
 */
class DashboardController
{

    /**
     * The controller used for the request
     *
     * @var mixed
     */
    private $controller;

    /**
     * DashboardController constructor.
     * Routes request to the correct controller based on the provided parameters array.
     *
     * @param \Twig_Environment $twig       Twig template
     * @param array             $parameters URL elements
     */
    public function __construct(\Twig_Environment $twig, $parameters = array())
    {

         $logger = new Logger('name');
         $logger->pushHandler(new StreamHandler(APP_PATH.'/my_app.log', Logger::DEBUG));

        // Security check
        // if (! Utility::checkUserIsAuthorised()) {
        //     Utility::doLoginRedirect();
        // }
        // $logger->addInfo('Passed DashboardController security check.');

        $subController = array_shift($parameters);

        switch (strtolower(filter_var($subController, FILTER_SANITIZE_STRING))) {
        case 'profile':
            $logger->addInfo('Passing to ProfileController.');
            $this->controller = new ProfileController($twig, $parameters);
            break;
        case 'cv':
            $logger->addInfo('Passing to CvController.');
            $this->controller = new CvController($twig, $parameters);
            break;
        default :
            $logger->addInfo('DashboardController:default - display Dashboard.');
            $this->_dashboardAction($twig);
            break;
        }

    }

    /**
     * Displays the dashboard template
     *
     * @param \Twig_Environment $twig
     */
    public function _dashboardAction(\Twig_Environment $twig)
    {

        $data = array(
            'page_title' => 'Dashboard',
            'username' => Utility::usernameFromSession(),
            'isAdmin' => Utility::isUserAuthorised()
            );

        print $twig->render('dashboard/dashboard.html.twig', $data);
    }

}
