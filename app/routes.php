<?php
// ce fichier contient la liste des routes = url ) que l'on va accepter
//silex va parcourir les routes de haut en bas et s'arrête à la première qui correspond


//page d'accueil :
$app->get('/', 'WF3\Controller\HomeController::homePageAction')->bind('home');

//page de reservation :
$app->get('/', 'WF3\Controller\HomeController::reservationAction')->bind('reservation');

//Page Menu admin :
$app->get('/admin', 'WF3\Controller\AdminController::indexAction')->bind('homeAdmin');

//Ajout d'un Spectacle :
$app->match('/admin/ajoutSpectacle', 'WF3\Controller\AdminController::ajoutSpectacleAction')->bind('ajoutSpectacle');

//Supprimer un spectacle :
$app->get('/deleteSpectacle/{id}', 'WF3\Controller\AdminController::deleteSpectacleAction')
->assert('id', '\d+')
->bind('deleteSpectacle');

//modification d'un spectacle dans l'admin :
$app->match('/admin/updateArticle/{id}', 'WF3\Controller\AdminController::updateSpectacleAction')
->assert('id', '\d+')
->bind('updateSpectacle');