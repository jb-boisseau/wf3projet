<?php
namespace WF3\Controller;

use Silex\Application;
//cette ligne nous permet d'utiliser le service fourni par symfony pour gérer 
// les $_GET et $_POST
use Symfony\Component\HttpFoundation\Request;


class HomeController{

	// Page d'accueil qui affiche tous les articles
	public function homePageAction(Application $app){

	 	return $app['twig']->render('index.html.twig');
	}

    // Page d'accueil qui affiche la reservation


	// Page de reservation 

	public function reservationAction(Application $app, Request $request){
        //$request->request;
	 	return $app['twig']->render('reservation.html.twig');
	}
    
}