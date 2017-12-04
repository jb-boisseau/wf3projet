<?php
namespace WF3\Controller;

use Silex\Application;
//cette ligne nous permet d'utiliser le service fourni par symfony pour gérer 
// les $_GET et $_POST
use Symfony\Component\HttpFoundation\Request;
use WF3\Domain\Article;
use WF3\Form\Type\ArticleType;
use WF3\Form\Type\ContactType;
use WF3\Domain\User;
use WF3\Form\Type\UserRegisterType;
use WF3\Form\Type\SearchEngineType;
use WF3\Form\Type\UploadImageType;
//permet de générer des erreurs 403 (accès interdit)
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class HomeController{

	// Page d'accueil qui affiche tous les articles
	public function homePageAction(Application $app){
		$articles = $app['dao.article']->getArticlesWithAuthor();

	 	return $app['twig']->render('index.html.twig', array('articles' => $articles));
	}
    
    // Page de recherche par auteur
    public function rechercheAction(Application $app, Request $request){
        
        $articles =[];
        $rechercheForm = $app['form.factory']->create(SearchEngineType::class);
        $rechercheForm->handleRequest($request);
        if($rechercheForm->isSubmitted() AND $rechercheForm->isValid()){
            //le formulaire a été envoyé
            //$request->request est égal à $_POST
            //$request->query est égal à $_GET
            $post = $request->request->get('search_engine');
            $articles = $app['dao.article']->getAllArticlesFromUsernameLike($post['auteur']);
        }
        return $app['twig']->render('recherche.html.twig', array(
            'form'=>$rechercheForm->createView(),
            'articles'=>$articles//,
            //'test'=>$request->files->get('search_engine')['attachment']->getOriginalName()
        ));
    }

	//page qui affiche les 5 derniers articles
	public function lastFiveArticlesAction(Application $app){
		$articles = $app['dao.article']->getLastArticles();

	 	return $app['twig']->render('last_articles.html.twig', array('articles' => $articles));
	}

	//page d'affichage d'un article
	public function articleAction(Application $app, $id){
		$article = $app['dao.article']->find($id);
    	return $app['twig']->render('article.html.twig', array('article' => $article));
	}

	//page de suppression d'article
	public function deleteArticleAction(Application $app, $id){
        //on va vérifier que l'utilisateur est connecté
        if(!$app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')){
            //je peux rediriger l'utilisateur non authentifié
            //return $app->redirect($app['url_generator']->generate('home'));
            throw new AccessDeniedHttpException();
        }
        //on récupère l'utilisateur connecté qui veut faire la suppression
        //on récupère le token si l'utilisateur est connecté
        $token = $app['security.token_storage']->getToken();
        if(NULL !== $token){
            $user = $token->getUser();
        }
        //on va chercher les infos sur cet article
        $article = $app['dao.article']->find($id);
        //on vérifie que cet utlisateur est bien l'auteur de l'article
        if($user->getId() != $article->getAuthor()){
            //si l'utilisateur n'est pas l'auteur: accès interdit
            throw new AccessDeniedHttpException();
        }
		$article = $app['dao.article']->delete($id);
        //on crée un message de réussite dans la session
        $app['session']->getFlashBag()->add('success', 'Article bien supprimé');
        //on redirige vers la page d'accueil
        return $app->redirect($app['url_generator']->generate('home'));
	}

	//liste des utilisateurs
	public function usersListAction(Application $app){
		$users = $app['dao.user']->findAll();
    	return $app['twig']->render('users.list.html.twig'
                                , array('users' => $users));
	}

	//fiche d'un utilisateur
	public function userAction(Application $app,Request $request, $id){
		$user = $app['dao.user']->find($id);
	    //on va chercher la liste des articles écrits par l'utilisateur dont l'id est $id
	    //on utilise la méthode getArticlesFromUser() de la classe ArticleDAO
	    $articles = $app['dao.article']->getArticlesFromUser($id);
        //création du formulaire d'upload
        $uploadForm = $app['form.factory']->create(UploadImageType::class);
        $uploadForm->handleRequest($request);
        if($uploadForm->isSubmitted() AND $uploadForm->isValid()){
            //on récupère les infos du fichier envoyé
            //ici comme j'ai généré le formulaire avec ma classe UploadImageType
            //c'est silex qui a généré le nom "upload_image"
            $file = $request->files->get('upload_image')['image'];
            //je lui dis où stocker le fichier
            //$app['upload_dir'] est défini dans app/config/prod.php
            $path = __DIR__.'/../../'.$app['upload_dir'];
            //le nom original est dispo avec :
            //$filename = $file->getClientOriginalName();
            //guessExtension() renvoie l'extension du fichier
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $user->setImage($filename);
            $app['dao.user']->update($user-getId(),$user);
            //on transfère le fichier
            $file->move($path,$filename);
        }
	    return $app['twig']->render('user.html.twig', array(
            'user' => $user, 
            'articles' => $articles,
            'uploadForm' => $uploadForm->createView()
        ));
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
                        ->setTo(array('votre@mail.com'))
                        ->setBody($app['twig']->render('contact.email.html.twig',
                            array('name'=>$data['name'],
                                   'email' => $data['email'],
                                   'message' => $data['message']
                            )
                        ), 'text/html');

            $app['mailer']->send($message);


        }
        return $app['twig']->render('contact.html.twig', array(
            'title' => 'Contact Us',
            'contactForm' => $contactForm->createView(),
            'data' => $contactForm->getData()
        ));
	}
    
    //page résultats recherche
    public function seurcheAction(Application $app, Request $request){
        $articles = $app['dao.article']->findArticlesByTitle($request->query->get('title'));
        //$articles = $app['dao.article']->findArticlesByTitle($_GET['title']);
        return $app['twig']->render('results.search.html.twig', array('articles' => $articles));
    }
    
    public function loginAction(Application $app, Request $request){
    	//j'appelle la vue qui contient le formulaire de connexion
    	//error va contenir les éventuels messages d'erreur
    	return $app['twig']->render('login.html.twig', array(
    		'error' => $app['security.last_error']($request),
    		'last_username' => $app['session']->get('_security.last_username')
    	));
    }

    public function ajoutArticleAction(Application $app, Request $request){
    	//on va vérifier que l'utilisateur est connecté
    	if(!$app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')){
            //je peux rediriger l'utilisateur non authentifié
            //return $app->redirect($app['url_generator']->generate('home'));
            throw new AccessDeniedHttpException();
        }
        //on récupère le token si l'utilisateur est connecté
        $token = $app['security.token_storage']->getToken();
        if(NULL !== $token){
            $user = $token->getUser();
        }

    	//je crée un objet article vide
    	$article = new Article();
    	//je crée mon objet formulaire à partir de la classe ArticleType
    	$articleForm = $app['form.factory']->create(ArticleType::class, $article);
    	//on envoie les paramètres de la requête à notre objet formulaire
    	$articleForm->handleRequest($request);
    	//on vérifie si le formulaire a été envoyé
    	//et si les données envoyées sont valides
    	if($articleForm->isSubmitted() && $articleForm->isValid()){
    		//c'est l'utilisateur connecté qui est l'auteur de l'article
    		$article->setAuthor($user->getId());
    		//on insère dans la base
    		$app['dao.article']->insert(array(
    			'title'=>$article->getTitle(),
    			'content'=>$article->getContent(),
    			'author'=>$article->getAuthor()
    		));
    		//on stocke en session un message de réussite
    		$app['session']->getFlashBag()->add('success', 'Article bien enregistré');

    	}

    	//j'envoie à la vue le formulaire grâce à $articleForm->createView() 
    	return $app['twig']->render('ajout.article.html.twig', array(
    			'articleForm' => $articleForm->createView()
    	));
    }
    
    /**
     * User sign in controller.
     *
     * @param Application $app Silex application
     * @param Request $request the http request
     */
    public function signInAction(Application $app, Request $request){	
        $user = new User();
        $userForm = $app['form.factory']->create(UserRegisterType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            // generate a random salt value
            $salt = substr(md5(time()), 0, 23);
            $user->setSalt($salt);
            //get plain password 
            $plainPassword = $user->getPassword();
            // find the default encoder
            $encoder = $app['security.encoder.bcrypt'];
            // compute the encoded password
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($password);
            //new users role is ROLE_USER by default
            $user->setRole('ROLE_USER');
            $app['dao.user']->insert($user);

            /*//this code automatically login new user 
            $token = new UsernamePasswordToken(
                $user, 
                $user->getPassword(), 
                'main',                 //key of the firewall you are trying to authenticate 
                array('ROLE_USER')
            );
            $app['security.token_storage']->setToken($token);

            // _security_main is, again, the key of the firewall
            $app['session']->set('_security_main', serialize($token));
            $app['session']->save(); // this will be done automatically but it does not hurt to do it explicitly*/


            $app['session']->getFlashBag()->add('success', 'Hello ' .  $user->getUsername());
            // Redirect to admin home page
            return $app->redirect($app['url_generator']->generate('homepage'));
        }
        return $app['twig']->render('user_register.html.twig', array(
            'title' => 'Sign in',
            'userForm' => $userForm->createView()
        ));
    }

}