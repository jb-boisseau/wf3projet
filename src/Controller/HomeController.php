<?php
namespace WF3\Controller;

use Silex\Application;
//cette ligne nous permet d'utiliser le service fourni par symfony pour gérer 
// les $_GET et $_POST
use Symfony\Component\HttpFoundation\Request;
use WF3\Domain\Livredor;
use WF3\Form\Type\LivredorType;

class HomeController{

	// Page d'accueil qui affiche tous les articles :
	public function homePageAction(Application $app){
	 	return $app['twig']->render('index.html.twig');
	}

	// Page de reservation :
	public function reservationAction(Application $app, Request $request){
	 	return $app['twig']->render('reservation.html.twig', array('test'=>$request->request->get('name')));
	}

	//Page du calendrier :
	public function calendarPageAction(Application $app, Request $request){
		return $app['twig']->render('calendar.html.twig');
   }
	
	//page du livre d'or :
	public function livreDorAction(Application $app, Request $request){
		$livredor = new Livredor();
		$livredorForm = $app['form.factory']->create(LivredorType::class, $livredor);
		$livredorForm->handleRequest($request);
		if($livredorForm->isSubmitted() && $livredorForm->isValid()){
			$app['dao.livredor']->insert($livredor);
		}


		return $app['twig']->render('livredor.html.twig',
			array('livredorForm'=>$livredorForm->createView())
		);
	}
}