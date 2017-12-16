<?php
namespace WF3\Controller;

use Silex\Application;
//cette ligne nous permet d'utiliser le service fourni par symfony pour gérer 
// les $_GET et $_POST
use Symfony\Component\HttpFoundation\Request;

class AjaxController{
    
    //Méthode pour récupérer le prix d'un spectacle :
    public function PrixAction(Application $app, Request $request){
        //Récupération de l'id du Spectacle :
        $id = $request->request->get('idSpectacle');
        $spectacle = $app['dao.spectacle']->find($id);
        //Nombre de places restantes pour ce Spectacle :
        $placeRestante = ($spectacle->getNbTickets() - $spectacle->getReservation());

        return $app['twig']->render('ajax/prix.html.twig', array(
            'spectacle'=>$spectacle,
            'placeRestante'=>$placeRestante
        ));
    }

    // Méthode pour récupérer le nombre de places réservées pour un  spectacle : 
    public function reservationAction(application $app, Request $request){
        //Récupération de l'id du Spectacle :
        $id = $request->request->get('idSpectacle');
        //Trouver le spectacle dans la BBD :
        $spectacle = $app['dao.spectacle']->find($id);
        //Récupération du nombre de réservation :
        $reservation = $request->request->get('reservation');
        //Cumul de toutes les réservations liées à ce Spectacle :
        $spectacle->setReservation((int)$spectacle->getReservation() + (int)$reservation);

        //Vérification que le nombre de tickets demandé ne dépasse pas le nombre de places disponibles :
        if($spectacle->getNbTickets() >= $spectacle->getReservation()){
            $app['dao.spectacle']->update($id, $spectacle);
            return 'ok';
        }
        else{
            return 'ko';
        }
    } 

}