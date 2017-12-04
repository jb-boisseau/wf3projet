<?php
// ce fichier contient la liste des routes = url ) que l'on va accepter
//silex va parcourir les routes de haut en bas et s'arrête à la première qui correspond

//page d'accueil qui affiche tout les articles
$app->get('/', 'WF3\Controller\HomeController::homePageAction')->bind('home');
//bind permet de nommer une route
//on peut alors appeler cette route dans une vue twig pour générer l'url correspondante

//page qui affiche les 5 derniers articles
$app->get('/derniers_articles', 'WF3\Controller\HomeController::lastFiveArticlesAction')->bind('lastArticles');

//page qui affiche un article
$app->get('/article/{id}', 'WF3\Controller\HomeController::articleAction')
->assert('id', '\d+')
->bind('voirArticle');

//page qui suuprime un article
$app->get('/article/delete/{id}', 'WF3\Controller\HomeController::deleteArticleAction')
->assert('id', '\d+')
->bind('deleteArticle');

//affichage des utilisateurs
$app->get('/users/list', 'WF3\Controller\HomeController::usersListAction')->bind('usersList');

//page qui affiche un auteur
$app->match('/user/{id}', 'WF3\Controller\HomeController::userAction')
->assert('id', '\d+')
->bind('voirUser');

//page contact
$app->match('/contact/moi', 'WF3\Controller\HomeController::contactAction')->bind('contactezmoi');

//page résultats du moteur de recherche
$app->get('/seurche', 'WF3\Controller\HomeController::seurcheAction')
    ->bind('rechercheParTitre');

//page résultats de l'autre moteur de recherche (le vrai)
$app->match('/recherche', 'WF3\Controller\HomeController::rechercheAction')
    ->bind('rechercheParAuteur');

//page résultats de l'autre moteur de recherche (le vrai) 
// !!! --> MAIS via AJAX
$app->match('/ajax/recherche', 'WF3\Controller\AjaxController::rechercheAction')
    ->bind('ajaxRechercheParAuteur');

//page de création d'article
    //match permet d'accepter les requêtes en get et en post
$app->match('/ajout/article', 'WF3\Controller\HomeController::ajoutArticleAction')->bind('ajoutArticle');

   //login page
$app->get('/login', 'WF3\Controller\HomeController::loginAction')->bind('login');

//sign in page
$app->match('/sign_in', 'WF3\Controller\HomeController::signInAction')->bind('signin');

//admin home page
$app->get('/admin', 'WF3\Controller\AdminController::indexAction')->bind('homeAdmin');

//suppression d'articles dans l'admin
$app->get('/admin/delete/article/{id}', 'WF3\Controller\AdminController::deleteArticleAction')
    ->assert('id', '\d+')
    ->bind('deleteArticleAdmin');

//modification d'articles dans l'admin
$app->match('/admin/update/article/{id}', 'WF3\Controller\AdminController::updateArticleAction')
    ->assert('id', '\d+')
    ->bind('updateArticleAdmin');

//ajout d'articles dans l'admin
$app->match('/admin/add/article/', 'WF3\Controller\AdminController::addArticleAction')
    ->bind('addArticleAdmin');

//ajout d'utilisateur dans l'admin
$app->match('/admin/add/user/', 'WF3\Controller\AdminController::addUserAction')
    ->bind('addUserAdmin');

//suppression d'articles dans l'admin
$app->get('/admin/delete/user/{id}', 'WF3\Controller\AdminController::deleteUserAction')
    ->assert('id', '\d+')
    ->bind('deleteUserAdmin');

//modification d'articles dans l'admin
$app->match('/admin/update/user/{id}', 'WF3\Controller\AdminController::updateUserAction')
    ->assert('id', '\d+')
    ->bind('updateUserAdmin');


//si aucune ne correspond : erreur 404