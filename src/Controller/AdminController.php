<?php
namespace WF3\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WF3\Form\Type\SpectacleType;
use WF3\Domain\Spectacle;


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
            $app['dao.spectacle']->insert(array(
                'title'=>$spectacle->getTitle(),
                'content'=>$spectacle->getContent(),
                'nbTickets'=>$spectacle->getNbTickets(),
                'place'=>$spectacle->getPlace(),
                'type'=>$spectacle->getType(),
                'dateVenue'=>$spectacle->getDateVenue()
            ));
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













}
