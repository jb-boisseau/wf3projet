<?php
namespace WF3\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WF3\Form\Type\SpectacleType;
use WF3\Form\Type\PressType;
use WF3\Domain\Spectacle;
use WF3\Domain\Press;
use WF3\Form\Type\LoginType;
use WF3\Domain\User;
use App\Service\FileUploader;

class AdminController{


    //////////////////////////////////////////////////////////////////////////////////////////

    //page d'accueil du back office
    public function indexAction(Application $app){
        $spectacles = $app['dao.spectacle']->LastShow();
        $articles = $app['dao.press']->findAll();
        return $app['twig']->render('admin/homeAdmin.html.twig', array('spectacles'=>$spectacles, 'articles'=>$articles));
    }

    

    ////////////////////////////////////////////////////////////////////////////////////////////

    //Ajout d'un spectacle :
    public function ajoutSpectacleAction(Application $app, Request $request){
        $spectacle = new Spectacle();
        $spectacleForm = $app['form.factory']->create(SpectacleType::class, $spectacle);

        $spectacleForm->handleRequest($request);

        if($spectacleForm->isSubmitted() AND $spectacleForm->isValid()){
            $path =__DIR__.'/../..'.$app['upload_dir'];
            $file = $request->files->get('spectacle')['image'];
            $filename       = md5(uniqid()) . '.' . $file->guessExtension();
            $spectacle      -> setImage($filename);
                $extension = $file->guessExtension();
                //on va créer une miniature
                //je décide que mes miniatures ont une largeur de 200px
                $newWidth = 200;

                if($extension == 'jpg' or 'jpeg' ){
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
            $file->move(
                'uploads/img', $filename);
        
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

            $path=__DIR__.'/../..'.$app['upload_dir'] . 'imdfgsdfgg/';
            $file= $request->files->get('spectacle')['image'];
            $filename=md5(uniqid()).'.'.$file->guessExtension();
            //je récupère l'ancienne image
            $image = $spectacle->getImage();
             $extension = $file->guessExtension();
                //on va créer une miniature
                //je décide que mes miniatures ont une largeur de 200px
                $newWidth = 200;

                if($extension == 'jpg' or 'jpeg' ){
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
            $spectacle->setImage($filename); 
            $file->move(
                'uploads/img', $filename);
            if(file_exists('../'.$app['upload_dir'] . "img/" .$image)){
                unlink('../'.$app['upload_dir'] . "img/" .$image);
            }                    
            
                

            $datetime=$spectacle->getDateVenue();
            $spectacle->setDateVenue($datetime->format('Y-m-d h:i:s'));
            $app['dao.spectacle']->update($id, $spectacle);
            $app['session']->getFlashBag()->add('success', 'Représentation bien modifiée');
            
        }

        return $app['twig']     ->render('admin/modifierSpectacle.html.twig', array(
                'spectacleForm' => $spectacleForm->createView(),
                'title'         => 'modification',
                'spectacle'     => $spectacle,
                'test'          => $request->files)
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

    


                                                                
    // 88888888ba   88888888ba   88888888888  ad88888ba    ad88888ba   
    // 88      "8b  88      "8b  88          d8"     "8b  d8"     "8b  
    // 88      ,8P  88      ,8P  88          Y8,          Y8,          
    // 88aaaaaa8P'  88aaaaaa8P'  88aaaaa     `Y8aaaaa,    `Y8aaaaa,    
    // 88""""""'    88""""88'    88"""""       `"""""8b,    `"""""8b,  
    // 88           88    `8b    88                  `8b          `8b  
    // 88           88     `8b   88          Y8a     a8P  Y8a     a8P  
    // 88           88      `8b  88888888888  "Y88888P"    "Y88888P"   
                                                                  




    //Ajout d'un Article de Presse :
    public function ajoutPressAction(Application $app, Request $request){
        $press = new Press();
        $pressForm = $app['form.factory']->create(PressType::class, $press);

        $pressForm->handleRequest($request);

        if($pressForm->isSubmitted() AND $pressForm->isValid()){
            $path =__DIR__.'/../..'.$app['upload_dir'];
            $file = $request->files->get('press')['image'];
            $filename       = md5(uniqid()) . '.' . $file->guessExtension();
            $press      -> setImage($filename);
                $extension = $file->guessExtension();
                //on va créer une miniature
                //je décide que mes miniatures ont une largeur de 200px
                $newWidth = 200;

                if($extension == 'jpg' or 'jpeg' ){
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
            $file->move(
                'uploads/img', $filename);
        
            $app['dao.press']->insert($press);
            $app['session']->getFlashBag()->add('success', 'Article de presse ajouté');
            //on redirige vers la page d'accueil
            return $app->redirect($app['url_generator']->generate('homeAdmin'));
        }

        return $app['twig']->render('admin/ajoutpress.html.twig', array(
                'pressForm' => $pressForm->createView(),
                'title' => 'ajout',
                'test' => $request->files
            
        ));
    }


    //Suppression d'un Article de presse :
    public function deletePressAction(Application $app, $id){
        $press = $app['dao.press']->delete($id);
        //on crée un message de réussite dans la session
        $app['session']->getFlashBag()->add('success', 'Article de press bien supprimé');
        //on redirige vers la page d'accueil
        return $app->redirect($app['url_generator']->generate('homeAdmin'));
    }


    //Modifier d'un Article de Presse : 
    public function updatePressAction(Application $app, Request $request, $id){
        //on récupère les infos de l'article
        $press = $app['dao.press']->find($id);
        $image = $press->getImage();
        $press->setImage(NULL);
    

        //on crée le formulaire et on lui passe le spectacle en paramètre
        //il va utiliser $article pour pré remplir les champs
        $pressForm = $app['form.factory']->create(PressType::class, $press);

        $pressForm->handleRequest($request);
        
        if($pressForm->isSubmitted() && $pressForm->isValid()){
            //si le formulaire a été soumis
            //on update avec les données envoyées par l'utilisateur

            $path=__DIR__.'/../..'.$app['upload_dir'] . 'imdfgsdfgg/';
            $file= $request->files->get('press')['image'];
            $filename=md5(uniqid()).'.'.$file->guessExtension();
            //je récupère l'ancienne image
            $image = $press->getImage();
                $extension = $file->guessExtension();
                //on va créer une miniature
                //je décide que mes miniatures ont une largeur de 200px
                $newWidth = 200;

                if($extension == 'jpg' or 'jpeg' ){
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
            $press->setImage($filename); 
            $file->move(
                'uploads/img', $filename);
            if(file_exists('../'.$app['upload_dir'] . "img/" .$image)){
                unlink('../'.$app['upload_dir'] . "img/" .$image);
            }                    
            
            $app['dao.press']->update($id, $press);
            $app['session']->getFlashBag()->add('success', 'Article de presse bien modifiée');
            
        }

        return $app['twig']     ->render('admin/modifierPress.html.twig', array(
                'pressForm' => $pressForm->createView(),
                'title'         => 'modification',
                'press'     => $press,
                'test'          => $request->files)
                );
        //on redirige vers la page d'accueil
    }

















}


