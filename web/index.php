<?php
//on utilise l'autoloader généré par composer
require_once(__DIR__ . '/../vendor/autoload.php');

//on peut alors instancier la classe application 
//$app est un objet qui représente notre $application
$app = new Silex\Application();

require_once(__DIR__.'/../app/config/dev.php');
require_once(__DIR__.'/../app/app.php');
require_once(__DIR__.'/../app/routes.php');

$app->run();

