<?php
// ce fichier contient la liste des routes = url ) que l'on va accepter
//silex va parcourir les routes de haut en bas et s'arrête à la première qui correspond


//page d'accueil :
$app->match('/', 'WF3\Controller\HomeController::homePageAction')->bind('home');

//page de paiement :
$app->get('/payments', 'WF3\Controller\HomeController::paymentsAction')->bind('payments');

//page de reservation :
$app->match('/reservation', 'WF3\Controller\HomeController::reservationAction')->bind('reservation');

//Livre d'or :
$app->match('/livreDor', 'WF3\Controller\HomeController::livreDorAction')->bind('livreDor');

$app->match('/livreDor-messages', 'WF3\Controller\HomeController::livreDorMessagesAction')->bind('livreDorMessages');

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

//Lien vers contact :
$app->match('/contact', 'WF3\Controller\HomeController::contactAction')->bind('contact');

//Route AJAX, pour récupérer le prix d'un spectacle :
$app->match('/ajax/prix', 'WF3\Controller\AjaxController::PrixAction')
->bind('ajaxPrix');

//Route AJAX, pour récupérer les réservations d'un spectacle :
$app->match('/ajax/reservation', 'WF3\Controller\AjaxController::reservationAction')
->bind('ajaxReservation');

//Ajout d'un Article de Presse :
$app->match('/admin/ajoutPress', 'WF3\Controller\AdminController::ajoutPressAction')->bind('ajoutPress');

//Supprimer un Article de Presse :
$app->get('/admin/deletePress/{id}', 'WF3\Controller\AdminController::deletePressAction')
->assert('id', '\d+')
->bind('deletePress');

//modification d'un presse dans l'admin :
$app->match('/admin/updatePress/{id}', 'WF3\Controller\AdminController::updatePressAction')
->assert('id', '\d+')
->bind('updatePress');

//Réservation Success :
$app->match('/success', 'WF3\Controller\HomeController::successAction')->bind('success');

//Réservation Error :
$app->match('/error', 'WF3\Controller\HomeController::errorAction')->bind('error');

