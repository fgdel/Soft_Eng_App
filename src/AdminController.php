<?php
/**
 * AdminController.php
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
 * Class AdminController
 *
 * @package Phizzle
 */
class AdminController
{

    /**
     * The controller used for the request
     *
     * @var mixed
     */
    private $controller;

    /**
     * AdminController constructor.
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
        if (! Utility::checkUserIsAuthorised()) {
            Utility::doLoginRedirect();
        }

        $logger->addInfo('Passed AdminController security check.');

        $subController = array_shift($parameters);

        switch (strtolower(filter_var($subController, FILTER_SANITIZE_STRING))) {
        case 'placement':
            $logger->addInfo('Passing to PlacementController.');
            $this->controller = new PlacementController($twig, $parameters);
            break;
        case 'user':
            $logger->addInfo('Passing to UserController.');
            $this->controller = new UserController($twig, $parameters);
            break;
        default :
            $logger->addInfo('AdminController:default - display Index.');
            $this->indexAction($twig);
            break;
        }

    }

    /**
     * Displays the Admin index template
     *
     * @param \Twig_Environment $twig
     */
    public function indexAction(\Twig_Environment $twig)
    {
        $data = array(
            'page_title' => 'Admin',
            'username' => Utility::usernameFromSession(),
        );

        print $twig->render('admin/index.html.twig', $data);
    }
}
