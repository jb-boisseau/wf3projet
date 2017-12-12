<?php
namespace WF3\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WF3\Form\Type\SpectacleType;
use WF3\Domain\Spectacle;
use WF3\Form\Type\LoginType;
use WF3\Domain\User;
use App\Service\FileUploader;

class AdminController{


    //////////////////////////////////////////////////////////////////////////////////////////

    //page d'accueil du back office
    public function indexAction(Application $app){
        $spectacles = $app['dao.spectacle']->findAll();
        return $app['twig']->render('admin/homeAdmin.html.twig', array('spectacles'=>$spectacles));
    }

    

    ////////////////////////////////////////////////////////////////////////////////////////////

    //Ajout d'un spectacle :
    public function ajoutSpectacleAction(Application $app, Request $request){
        $spectacle = new Spectacle();
        $spectacleForm = $app['form.factory']->create(SpectacleType::class, $spectacle);

        $spectacleForm->handleRequest($request);

        if($spectacleForm->isSubmitted() AND $spectacleForm->isValid()){
            $path =__DIR__.'/../..'.$app['upload_dir'];
            $file = $request->files->get('spectacle')['image']['image'];
            $filename       = md5(uniqid()) . '.' . $file->guessExtension();
            $spectacle      -> setImage($filename);
                $extension = $file->guessExtension();
                //on va créer une miniature
                //je décide que mes miniatures ont une largeur de 200px
                $newWidth = 200;

                if($extension == 'jpg' ){
                    //jpeg ou pjg
                    $newImage = imagecreatefromjpeg($file->getPathname());  
                }
                elseif($extension == 'png'){
                    //png
                    $newImage = imagecreatefrompng($file->getPathname());
                }
                else{
                    //fichier gif
                    $newImage = imagecreatefromgif($file->getPathname());
                }

                //on récupère les dimensions de l'image
                //largeur
                $imageWidth = imagesx($newImage);
                //hauteur
                $imageHeight = imagesy($newImage);

                //j'ai décidé de la largeur des mes miniatures (200px), je dois donc calculer la nouvelle hauteur (on doit conserver le ratio pour ne pas déformer l'image)
                //on calcule la nouvelle hauteur
                $newHeight = ($imageHeight * $newWidth) / $imageWidth;

                //on crée la miniature
                $miniature = imagecreatetruecolor($newWidth, $newHeight);
                
                if($extension == 'png'){
                    imagesavealpha($miniature, true);
                    $white = imagecolorallocate($miniature, 255, 255, 255);
                    // On rend l'arrière-plan transparent
                    imagecolortransparent($miniature, $white);
                }

                //on va ensuite "remplir" la miniature à partir de l'image envoyée
                imagecopyresampled($miniature, $newImage, 0, 0, 0, 0, $newWidth, $newHeight, $imageWidth, $imageHeight);

                //on définit le dossier qui va contenir les miniatures
                $thumbnailsFolder = 'uploads/thumbnails/';

                //on teste l'extension
                if($extension == 'jpg'){
                    imagejpeg($miniature, $thumbnailsFolder . $filename);
                }
                elseif($extension == 'png'){
                    imagepng($miniature, $thumbnailsFolder . $filename);
                }
                else{
                    imagegif($miniature, $thumbnailsFolder . $filename);
                }
        
            $datetime       = $spectacle->getDateVenue();
            $spectacle      ->setDateVenue($datetime->format('Y-m-d h:i:s'));
            $app['dao.spectacle']->insert($spectacle);
            $app['session']->getFlashBag()->add('success', 'Spectacle ajouté');
            //on redirige vers la page d'accueil
            return $app->redirect($app['url_generator']->generate('homeAdmin'));
        }

        return $app['twig']->render('admin/ajoutSpectacle.html.twig', array(
                'spectacleForm' => $spectacleForm->createView(),
                'title' => 'ajout',
                'test' => $request->files
            
        ));
    }

 
    //suppression d'un Spectacle :
    public function deleteSpectacleAction(Application $app, $id){
        $spectacle = $app['dao.spectacle']->delete($id);
        //on crée un message de réussite dans la session
        $app['session']->getFlashBag()->add('success', 'Représentation bien supprimée');
        //on redirige vers la page d'accueil
        return $app->redirect($app['url_generator']->generate('homeAdmin'));



    }

    


    ////////////////////////////////////////////////////////////////////////////////////////////

    //modification d'un Spectacle :
    public function updateSpectacleAction(Application $app, Request $request, $id){
        //on récupère les infos de l'article
        $spectacle = $app['dao.spectacle']->find($id);
        $image=$spectacle->getImage();
        $spectacle->setImage(NULL);
        $spectacle->setDateVenue(new \DateTime($spectacle->getDateVenue()));
        //on crée le formulaire et on lui passe le spectacle en paramètre
        //il va utiliser $article pour pré remplir les champs
        $spectacleForm = $app['form.factory']->create(SpectacleType::class, $spectacle);

        $spectacleForm->handleRequest($request);
        
        if($spectacleForm->isSubmitted() && $spectacleForm->isValid()){
            //si le formulaire a été soumis
            //on update avec les données envoyées par l'utilisateur
                if($spectacle->getImage()===NULL){
                    $spectacle->setImage($image);
                    $app['dao.spectacle']->update($id, $spectacle);
                    $app['session']->getFlashBag()->add('success', 'Image bien modifiée');
                }
                else{
                    $path=__DIR__.'/../..'.$app['upload_dir'];
                    $file= $request->files->get('register')['image'];
                    $filename=md5(uniqid()).'.'.$file->guessExtension();
                    $spectacle->setImage($filename); 
                    $app['dao.spectacle']->update($id, $spectacle);
                    $file->move($path,$filename);
                }
                if(file_exists('../'.$app['upload_dir'] . "/" .$image)){
                    unlink('../'.$app['upload_dir'] . "/" .$image);
                    $app['session']->getFlashBag()->add('success', 'Image bien modifiée');
                }

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
    
    ////////////////////////////////////////////////////////////////////////////////////////////    


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


