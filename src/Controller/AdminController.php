<?php
namespace WF3\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WF3\Form\Type\SpectaclesType;
use WF3\Domain\Spectacles;


class AdminController{

    //page d'accueil du back office
    public function indexAction(Application $app){
        $spectacles = $app['dao.spectacle']->findAll();
        return $app['twig']->render('admin/homeAdmin.html.twig', array('spectacle'=>$spectacles));
    }

    
}