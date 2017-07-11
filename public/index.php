<?php

require_once dirname(__DIR__, 1) . '/app/setup.php';
require_once dirname(__DIR__, 1) . '/app/config_db.php';
require_once dirname(__DIR__, 1) . '/src/Config.php';


use Phizzle\MainController;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

// start session
session_start();

$logger = new Logger('name');
$logger->pushHandler(new StreamHandler(__DIR__.'/../my_app.log', Logger::DEBUG));
$logger->pushHandler(new FirePHPHandler());

// For URL http://localhost:8080/?admin/user/update/1
$urlStr =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
// urlStr = //localhost/?admin/user/update/1
$urlQuery = parse_url( $urlStr , PHP_URL_QUERY );
// urlQuery = admin/user/view/123
$urlPieces = explode('/', $urlQuery);
// urlPieces = [ 'admin', 'user', 'update', '1' ]

if (count($urlPieces) > 0) {
    // remove the first element from the array
    $action = strtolower( filter_var(array_shift($urlPieces), FILTER_SANITIZE_STRING) );

    switch ($action) {
        case 'admin' :
            $logger->addInfo('Going to AdminController.');
            $adminController = new \Phizzle\AdminController($twig, $urlPieces);
            break;
        case 'login' :
            $mainController->loginAction($twig);
            break;
        case 'logout' :
            $mainController->logoutAction($twig);
            break;
        case 'register' :
            $mainController->registerAction($twig);
            break;
        case 'news' :
            $mainController->newsAction($twig);
            break;
        case 'dashboard' :
            $logger->addInfo('Going to DashboardController.');
            $dashboardController = new \Phizzle\DashboardController($twig, $urlPieces);
            break;
        default:
            $mainController->indexAction($twig);
    }

}



