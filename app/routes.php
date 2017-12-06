<?php
// ce fichier contient la liste des routes = url ) que l'on va accepter
//silex va parcourir les routes de haut en bas et s'arrête à la première qui correspond


//page d'accueil :
$app->get('/', 'WF3\Controller\HomeController::homePageAction')->bind('home');
//page de reservation :
$app->get('/reservation', 'WF3\Controller\HomeController::reservationAction')->bind('reservation');

//Page Menu admin :
$app->get('/admin', 'WF3\Controller\AdminController::indexAction')->bind('homeAdmin');

//Ajout d'un Spectacle :
$app->match('/admin/ajoutSpectacle', 'WF3\Controller\AdminController::ajoutSpectacleAction')->bind('ajoutSpectacle');