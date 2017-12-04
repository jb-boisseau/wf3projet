<?php
namespace WF3\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WF3\Form\Type\ArticleType;
use WF3\Domain\Article;
use WF3\Form\Type\UserType;
use WF3\Domain\User;

class AdminController{

    //page d'accueil du back office
    public function indexAction(Application $app){
        $articles = $app['dao.article']->findAll();
        $users = $app['dao.user']->findAll();
        return $app['twig']->render('admin/index.admin.html.twig', array(
                                        'articles'=>$articles,
                                        'users' =>$users
                                    ));
    }

    //suppression d'article
    //page de suppression d'article
    public function deleteArticleAction(Application $app, $id){
        $article = $app['dao.article']->delete($id);
        //on crée un message de réussite dans la session
        $app['session']->getFlashBag()->add('success', 'Article bien supprimé');
        //on redirige vers la page d'accueil
        return $app->redirect($app['url_generator']->generate('homeAdmin'));
    }

    //modification d'article
    public function updateArticleAction(Application $app, Request $request, $id){
        //on récupère les infos de l'article
        $article = $app['dao.article']->find($id);
        //on crée le formulaire et on lui passe l'article en paramètre
        //il va utiliser $article pour pré remplir les champs
        $articleForm = $app['form.factory']->create(ArticleType::class, $article);

        $articleForm->handleRequest($request);

        if($articleForm->isSubmitted() && $articleForm->isValid()){
            //si le formulaire a été soumis
            //on update avec les données envoyées par l'utilisateur
            $app['dao.article']->update($id, array(
                'title'=>$article->getTitle(),
                'content'=>$article->getContent(),
                'author'=>$article->getAuthor()->getId()
            ));
        }

        return $app['twig']->render('admin/admin.ajout.article.html.twig', array(
                'articleForm' => $articleForm->createView(),
                'title' => 'modif'
        ));

    }

    public function addArticleAction(Application $app, Request $request){
        $article = new Article();

        $articleForm = $app['form.factory']->create(ArticleType::class, $article);

        $articleForm->handleRequest($request);

        if($articleForm->isSubmitted() AND $articleForm->isValid()){
            $app['dao.article']->insert(array(
                'title'=>$article->getTitle(),
                'content'=>$article->getContent(),
                'author'=>$app['user']->getId()
            ));
        }

        return $app['twig']->render('admin/admin.ajout.article.html.twig', array(
                'articleForm' => $articleForm->createView(),
                'title' => 'ajout'
        ));
    }
    
    /**
     * Admin user add controller.
     *
     * @param Application $app Silex application
     * @param Request $request the http request
     */
    public function addUserAction(Application $app, Request $request){	
        $user = new User();
        $userForm = $app['form.factory']->create(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            // on génère un salt
            $salt = substr(md5(time()), 0, 23);
            $user->setSalt($salt);
            //on récupère le mot de passe en clair (envoyé par l'utilisateur) 
            $plainPassword = $user->getPassword();
            // on récupère l'encoder de silex
            $encoder = $app['security.encoder.bcrypt'];
            // on encode le mdp
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            //on remplace le mdp en clair par le mdp crypté
            $user->setPassword($password);
            $app['dao.user']->insert($user);
            $app['session']->getFlashBag()->add('success', 'The user was successfully created.');
            // Redirect to admin home page
            return $app->redirect($app['url_generator']->generate('homeAdmin'));
        }
        return $app['twig']->render('admin/admin.ajout.user.html.twig', array(
            'title' => 'Add user',
            'userForm' => $userForm->createView(),
            'user' => $user
        ));
    }
    
    //suppression d'article
    //page de suppression d'article
    public function deleteUserAction(Application $app, $id){
        //on va supprimer les articles écrits par l'utilisateur
        $nbArticlesSupprimes = $app['dao.article']->deleteAllArticlesFromUser($id);
        $article = $app['dao.user']->delete($id);
        //on crée un message de réussite dans la session
        $app['session']->getFlashBag()->add('success', 'Utilisateur bien supprimé, ses ' . $nbArticlesSupprimes . ' article(s) pourri(s) aussi supprimés');
        //on redirige vers la page d'accueil
        return $app->redirect($app['url_generator']->generate('homeAdmin'));
    }

    //modification d'article
    public function updateUserAction(Application $app, Request $request, $id){
        $user = $app['dao.user']->find($id);
        $userForm = $app['form.factory']->create(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            // on génère un salt
            $salt = substr(md5(time()), 0, 23);
            $user->setSalt($salt);
            //on récupère le mot de passe en clair (envoyé par l'utilisateur) 
            $plainPassword = $user->getPassword();
            // on récupère l'encoder de silex
            $encoder = $app['security.encoder.bcrypt'];
            // on encode le mdp
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            //on remplace le mdp en clair par le mdp crypté
            $user->setPassword($password);
            $app['dao.user']->update($id, $user);
            $app['session']->getFlashBag()->add('success', 'The user was successfully updated.');
            // Redirect to admin home page
            return $app->redirect($app['url_generator']->generate('homeAdmin'));
        }
        return $app['twig']->render('admin/admin.ajout.user.html.twig', array(
            'title' => 'update user',
            'userForm' => $userForm->createView(),
            'user' => $user
        ));

    }
}