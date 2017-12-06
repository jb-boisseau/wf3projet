<?php
namespace WF3\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WF3\Form\Type\SpectacleType;
use WF3\Domain\Spectacle;
use WF3\Form\Type\LoginType;
use WF3\Domain\User;

class AdminController{

    //page d'accueil du back office
    public function indexAction(Application $app){
        $spectacles = $app['dao.spectacle']->findAll();
        return $app['twig']->render('admin/homeAdmin.html.twig', array('spectacles'=>$spectacles));
    }


    //Ajout d'un spectacle :
    public function ajoutSpectacleAction(Application $app, Request $request){
        $spectacle = new Spectacle();

        $spectacleForm = $app['form.factory']->create(SpectacleType::class, $spectacle);

        $spectacleForm->handleRequest($request);

        if($spectacleForm->isSubmitted() AND $spectacleForm->isValid()){
            $datetime=$spectacle->getDateVenue();
            $spectacle->setDateVenue($datetime->format('Y-m-d h:i:s'));
            $app['dao.spectacle']->insert($spectacle);
            $app['session']->getFlashBag()->add('success', 'Spectacle ajouté');
            //on redirige vers la page d'accueil
            return $app->redirect($app['url_generator']->generate('homeAdmin'));
        }

        return $app['twig']->render('admin/ajoutSpectacle.html.twig', array(
                'spectacleForm' => $spectacleForm->createView(),
                'title' => 'ajout'
        ));
    }


    //suppression d'un Spectacle :
    public function deleteSpectacleAction(Application $app, $id){
        $spectacle = $app['dao.spectacle']->delete($id);
        //on crée un message de réussite dans la session
        $app['session']->getFlashBag()->add('success', 'Représentation bien supprimé');
        //on redirige vers la page d'accueil
        return $app->redirect($app['url_generator']->generate('homeAdmin'));
    }


    //modification d'un Spectacle :
    public function updateSpectacleAction(Application $app, Request $request, $id){
        //on récupère les infos de l'article
        $spectacle = $app['dao.spectacle']->find($id);
        $spectacle->setDateVenue(new \DateTime($spectacle->getDateVenue()));
        //on crée le formulaire et on lui passe le spectacle en paramètre
        //il va utiliser $article pour pré remplir les champs
        $spectacleForm = $app['form.factory']->create(SpectacleType::class, $spectacle);

        $spectacleForm->handleRequest($request);

        if($spectacleForm->isSubmitted() && $spectacleForm->isValid()){
            //si le formulaire a été soumis
            //on update avec les données envoyées par l'utilisateur

            $datetime=$spectacle->getDateVenue();
            $spectacle->setDateVenue($datetime->format('Y-m-d h:i:s'));
            $app['dao.spectacle']->update($id, $spectacle);
            $app['session']->getFlashBag()->add('success', 'Représentation bien modifiée');
            //on redirige vers la page d'accueil
            return $app->redirect($app['url_generator']->generate('homeAdmin'));
        }

        return $app['twig']->render('admin/modifierSpectacle.html.twig', array(
                'spectacleForm' => $spectacleForm->createView(),
                'title' => 'modif')
               );
        //on redirige vers la page d'accueil
    }
    
    
    //Connexion pour accéder à la page Administration :
    public function loginAction(Application $app, Request $request){
    	//j'appelle la vue qui contient le formulaire de connexion
    	//error va contenir les éventuels messages d'erreur
    	return $app['twig']->render('login.html.twig', array(
    		'error' => $app['security.last_error']($request),
    		'last_username' => $app['session']->get('_security.last_username')
    	));
    }


}

