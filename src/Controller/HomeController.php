<?php
namespace WF3\Controller;

use Silex\Application;
//cette ligne nous permet d'utiliser le service fourni par symfony pour gérer 
// les $_GET et $_POST
use Symfony\Component\HttpFoundation\Request;
use WF3\Form\Type\ReservationType;
use WF3\Domain\Livredor;
use WF3\Form\Type\LivredorType;
use WF3\Form\Type\ContactType;
use WF3\Form\Type\UploadImageType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class HomeController{

	// Page d'accueil qui affiche tous les spectacles :
	public function homePageAction(Application $app){
        $spectacles = $app['dao.spectacle']->findAll();
        return $app['twig']->render('index.html.twig', array('spectacles'=>$spectacles));
    }


	// Page d'accueil qui affiche tous les articles :
	public function paymentsAction(Application $app){
	 	return $app['twig']->render('payments.html.twig');
	}


	//page contact
	public function contactAction(Application $app, Request $request){
        $contactForm = $app['form.factory']->create(ContactType::class);
        $contactForm->handleRequest($request);
        
        if ($contactForm->isSubmitted() && $contactForm->isValid())
        {
            $data = $contactForm->getData();
            $message = \Swift_Message::newInstance()
                        ->setSubject($data['subject'])
                        ->setFrom(array('promo5wf3@gmx.fr'))
                        ->setTo(array('batty.arnaud@hotmail.fr'))
                        ->setBody($app['twig']->render('contact.email.html.twig',
                            array('name'=>$data['name'],
                                   'email' => $data['email'],
                                   'message' => $data['message']
                            )
                        ), 'text/html');

            $app['mailer']->send($message);
            $app['session']->getFlashBag()->add('success', 'Email envoyé, nous vous répondrons dès que possible !');
            return $app->redirect($app['url_generator']->generate('home'));

        }
        return $app['twig']->render('contact.html.twig', array(
            'title' => 'Contact Us',
            'contactForm' => $contactForm->createView(),
            'data' => $contactForm->getData()
        ));
	}


    // Page de reservation 
	public function reservationAction(Application $app, Request $request){
        //Récupération des données dans $data
        $data = $app['dao.spectacle']->findAll();
        $listed= array('Choisissez votre spectacle'=>0);
        
        foreach($data as $spectacle){
            $listed[$spectacle->getTitle()] = $spectacle->getId();
        }

        $reservationForm = $app['form.factory']->create(ReservationType::class);

        $reservationForm->add('spectacles', ChoiceType::class, array('choices'=>$listed));
        
        $reservationForm->handleRequest($request);
        
        if ($reservationForm->isSubmitted() && $reservationForm->isValid())
        {
            $data = $reservationForm->getData();
            $message = \Swift_Message::newInstance()
                        ->setSubject($data['subject'])
                        ->setFrom(array('promo5wf3@gmx.fr'))
                        ->setTo(array($data['email']))
                        ->setBody($app['twig']->render('reservation.html.twig',
                            array('name'        => $data['name'],
                                   'email'      => $data['email'],
                                   'message'    => $data['message']
                            )
                        ), 'text/html');

            $app['mailer']->send($message);


        }
        return $app['twig']->render('reservation.html.twig', array(
            'reservationForm' => $reservationForm->createView(),
            'data' => $reservationForm->getData()
        ));
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
            $app['session']->getFlashBag()->add('success', 'Message enregistré, merci pour votre contribution !');
            return $app->redirect($app['url_generator']->generate('livreDorMessages'));
		}

		return $app['twig']->render('livredor.html.twig',
			array('livredorForm'=>$livredorForm->createView())
		);
	}
    
    //page du livre d'or :
    public function livreDorMessagesAction(Application $app){
        
        $messages = $app['dao.livredor']->findLast10();

        return $app['twig']->render('livredor.message.html.twig',
            array('messages'=>$messages)
        );
    }
}