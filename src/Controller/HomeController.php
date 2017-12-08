<?php
namespace WF3\Controller;

use Silex\Application;
//cette ligne nous permet d'utiliser le service fourni par symfony pour gérer 
// les $_GET et $_POST
use Symfony\Component\HttpFoundation\Request;


class HomeController{

	// Page d'accueil qui affiche tous les articles + Envoi du mail par contact
	public function homePageAction(Application $app, Request $request){
		
		if(!isset($request->request->get('subject'))){
            $message = \Swift_Message::newInstance()
                        ->setSubject($request->request->get('subject'))
                        ->setFrom(array($request->request->get('email')))
                        ->setTo(array('votre@mail.com'))
                        ->setBody($app['twig']->render('index.html.twig',
                            array('name'=>$request->request->get('name'),
								   'email' => $request->request->get('email'),
								   'tel' => $request->request->get('tel'),
                                   'content' => $request->request->get('content')
                            )
                        ), 'text/html');

            $app['mailer']->send($message);
		}

	 	return $app['twig']->render('index.html.twig', array('test'=>$request->request));
	}

	// Page de reservation 

	public function reservationAction(Application $app, Request $request){

        
	 	return $app['twig']->render('reservation.html.twig', array('test'=>$request->request->get('name')));

	}
	
	
	//page d'accueil du back office
	public function livreDorAction(Application $app){
		return $app['twig']->render('livredor.html.twig');
	}
}