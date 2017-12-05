<?php
namespace WF3\Controller;

use Silex\Application;


class HomeController{

	// Page d'accueil qui affiche tous les articles
	public function homePageAction(Application $app){

	 	return $app['twig']->render('index.html.twig');
	}
    
  
}