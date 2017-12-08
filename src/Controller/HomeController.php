<?php
namespace WF3\Controller;

use Silex\Application;
//cette ligne nous permet d'utiliser le service fourni par symfony pour gérer 
// les $_GET et $_POST
use Symfony\Component\HttpFoundation\Request;
use WF3\Form\Type\ReservationType;



class HomeController{

	// Page d'accueil qui affiche tous les articles
	public function homePageAction(Application $app){

	 	return $app['twig']->render('index.html.twig');
	}

	// Page de reservation 
   
	public function reservationAction(Application $app, Request $request){
        $reservationForm = $app['form.factory']->create(ReservationType::class);
        $reservationForm->handleRequest($request);
        
        if ($reservationForm->isSubmitted() && $reservationForm->isValid())
        {
            $data = $reservationForm->getData();
            $message = \Swift_Message::newInstance()
                        ->setSubject($data['subject'])
                        ->setFrom(array('promo5wf3@gmx.fr'))
                        ->setTo(array('votre@mail.com'))
                        ->setBody($app['twig']->render('reservation.html.twig',
                            array('name'=>$data['name'],
                                   'email' => $data['email'],
                                   'message' => $data['message']
                            )
                        ), 'text/html');

            $app['mailer']->send($message);


        }
        return $app['twig']->render('reservation.html.twig', array(
            'reservationForm' => $reservationForm->createView(),
            'data' => $reservationForm->getData()
        ));
	}
    
	
   
	//page d'accueil du back office
	public function livreDorAction(Application $app){
		return $app['twig']->render('livredor.html.twig');
	}
}