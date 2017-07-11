<?php
/**
 * Created by PhpStorm.
 * User: vagrant
 * Date: 17/04/16
 * Time: 18:36
 */
//----------------- autoload any classes we are using ------------------
define('APP_PATH', dirname(__DIR__, 1) );
require_once APP_PATH . '/vendor/autoload.php';

// Load the database settings
require_once __DIR__ . '/config_db.php';

define('TEMPLATE_PATH', APP_PATH . '/templates' );

use Phizzle\MainController;
use Phizzle\DeveloperRepository;

//----------------- Twig setup ----------------------------
$loader = new Twig_Loader_Filesystem( TEMPLATE_PATH );

$twig = new Twig_Environment($loader);

$action_url_function = new Twig_SimpleFunction('action_url', function ($action) {
    $base_url = '/';
    switch ($action) {
        case 'admin' :
        case 'admin/user' :
        case 'admin/placement' :
        case 'admin/cv' :
        case 'dashboard' :
        case 'dashboard/profile' :
        case 'dashboard/cv' :
        case 'login' :
        case 'logout' :
        case 'register' :
        case 'news' :
            $link_url = $base_url . '?' . $action;
            break;
        default:
            $link_url = $base_url;
    }
    echo $link_url;
});
$twig->addFunction($action_url_function);

//create an instance of MainController class for use in index.php
$mainController = new MainController();