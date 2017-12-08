<?php
// ce fichier contient la liste des routes = url ) que l'on va accepter
//silex va parcourir les routes de haut en bas et s'arrête à la première qui correspond


//page d'accueil :
$app->match('/', 'WF3\Controller\HomeController::homePageAction')->bind('home');

//page de reservation :
$app->match('/reservation', 'WF3\Controller\HomeController::reservationAction')->bind('reservation');

//Livre d'or :
$app->match('/livreDor', 'WF3\Controller\HomeController::livreDorAction')->bind('livreDor');

//Page Menu admin :
$app->get('/admin', 'WF3\Controller\AdminController::indexAction')->bind('homeAdmin');

//Ajout d'un Spectacle :
$app->match('/admin/ajoutSpectacle', 'WF3\Controller\AdminController::ajoutSpectacleAction')->bind('ajoutSpectacle');

//Supprimer un spectacle :
$app->get('/admin/deleteSpectacle/{id}', 'WF3\Controller\AdminController::deleteSpectacleAction')
->assert('id', '\d+')
->bind('deleteSpectacle');

//modification d'un spectacle dans l'admin :
$app->match('/admin/updateSpectacle/{id}', 'WF3\Controller\AdminController::updateSpectacleAction')
->assert('id', '\d+')
->bind('updateSpectacle');

//Connexion pour les administrateurs :
$app->get('/login', 'WF3\Controller\AdminController::loginAction')->bind('login');

//Lien vers le Calendrier :
$app->get('/calendar', 'WF3\Controller\HomeController::calendarPageAction')->bind('calendar');



//Déconnexion pour les administrateurs :