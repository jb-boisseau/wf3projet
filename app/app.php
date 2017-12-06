<?php
// On utilise des composants Symfony qui vont nous permettre d'avoir des erreurs plus précises
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Silex\Provider;

// On enregistre ces services dans l'application Silex
ErrorHandler::register();
ExceptionHandler::register();

$app->register(new Provider\HttpFragmentServiceProvider());
$app->register(new Provider\ServiceControllerServiceProvider());

//On enregistre le service dbal
$app->register(new Silex\Provider\DoctrineServiceProvider());

//on enregistre le service twig
$app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../views'));

//enregistrement du service Symfony asset 
$app->register(new Silex\Provider\AssetServiceProvider(), array(
    'assets.version' => 'v1'
));

$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern' => '^/',
            'anonymous' => true,
            'logout' => true,
            'form' => array('login_path' => '/login', 'check_path' => '/login_check', 'default_target_path' => '/admin'),
            'users' => function () use ($app) {
                return new WF3\DAO\UserDAO($app['db'], 'users', 'WF3\Domain\User');
            },
            'logout' => array('logout_path' => '/logout', 'invalidate_session' => true)
        ),
    ),
    'security.role_hierarchy' => array(
        'ROLE_ADMIN' => array('ROLE_USER')
    ),
    'security.access_rules' => array(
        array('^/admin1', 'ROLE_ADMIN')
    )
));


// Service web profiler de symfony
$app->register(new Provider\WebProfilerServiceProvider(), array(
    'profiler.cache_dir' => __DIR__.'/../cache/profiler',
    'profiler.mount_prefix' => '/_profiler', // this is the default
));
//ajout du odule dbal au webprofiler
$app->register(new Sorien\Provider\DoctrineProfilerServiceProvider());

//enregistrement du composant form
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());

//enregistrement du service Validator
$app->register(new Silex\Provider\ValidatorServiceProvider());

//enregistrement du service SwiftMailer
$app->register(new Silex\Provider\SwiftmailerServiceProvider());
//swiftmailer
$app['swiftmailer.options'] = array(
    'host' => 'mail.gmx.com',
    'port' => '465',
    'username' => 'promo5wf3@gmx.fr',
    'password' => 'ttttttttt33',
    'encryption' => 'SSL',
    'auth_mode' => null
);


$app['dao.spectacle'] = function($app){
	$spectacleDAO = new WF3\DAO\SpectacleDAO($app['db'], 'spectacle', 'WF3\Domain\Spectacle');
    //on injecte dans $articleDAO une instance de la classe UserDAO : injection de dépendance
    //elle est faite une seule fois, ici
    return $spectacleDAO;
};

//on enregistre un nouveau service :
//on pourra ainsi accéder à notre classe UserDAO grâce à $app['dao.user'] 
$app['dao.user'] = function($app){
	return new WF3\DAO\UserDAO($app['db'], 'users', 'WF3\Domain\User');
};



