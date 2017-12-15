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
        array('^/admin', 'ROLE_ADMIN')
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

//swiftmailer :
$app['swiftmailer.options'] = array(
    'host' => 'mail.gmx.com',
    'port' => '465',
    'username' => 'promo5wf3@gmx.fr',
    'password' => 'ttttttttt33',
    'encryption' => 'SSL',
    'auth_mode' => null
);

//Service Paypal :
$app->register(new SKoziel\Silex\PayPalRest\PayPalServiceProvider(), array(
    'paypal.settings'=>array(
    'mode'=>'sandbox', //'live' or 'sandbox'(default)
    'clientID'=>'AU04RLCXMte6BDV2bK13dxWiNdI1wQZDS7M5jajFGMjiiRYPQhBUFd8_CIVxHP92L1-WVjX8A0yF10mR', //Checkout PayPal Documentation for more info
    'secret'=>'EABju82JQdI1r62sDMEu-IzBngQVDsl1J6Gxfexz0MOhiA3q2avyztK-a6H3v5x6TiGxNDTUO2vF71rq', //Checkout PayPal Documentation for more info
    'connectionTimeOut'=>30, //Connection time out in seconds, optional, default = 30
    'logEnabled'=>false, //This parameter is optional, default = true
    'logdir'=>'logs/', //This parameter is optional, default = ROOT/logs
    'currency'=>'EUR' //This parameter is optional, default = EUR
)));
    


$app['dao.spectacle'] = function($app){
	$spectacleDAO = new WF3\DAO\SpectacleDAO($app['db'], 'spectacle', 'WF3\Domain\Spectacle');
    //on injecte dans $articleDAO une instance de la classe UserDAO : injection de dépendance
    //elle est faite une seule fois, ici
    return $spectacleDAO;
};

$app['dao.press'] = function($app){
	$pressDAO = new WF3\DAO\PressDAO($app['db'], 'press', 'WF3\Domain\Press');
    //on injecte dans $articleDAO une instance de la classe UserDAO : injection de dépendance
    //elle est faite une seule fois, ici
    return $pressDAO;
};

$app['dao.reservation'] = function($app){
	$reservationDAO = new WF3\DAO\ReservationDAO($app['db'], 'reservation', 'WF3\Domain\Reservation');
    //on injecte dans $articleDAO une instance de la classe UserDAO : injection de dépendance
    //elle est faite une seule fois, ici
    return $reservationDAO;
};

//on enregistre un nouveau service :
//on pourra ainsi accéder à notre classe UserDAO grâce à $app['dao.user'] 
$app['dao.user'] = function($app){
	return new WF3\DAO\UserDAO($app['db'], 'users', 'WF3\Domain\User');
};

$app['dao.livredor'] = function($app){
    return new WF3\DAO\LivreDorDAO($app['db'], 'livredor', 'WF3\Domain\Livredor');
};

$app['dao.paypalInvoice'] = function($app){
    return new \WF3\DAO\PaypalInvoiceDAO($app['db'], 'paypalInvoice', 'WF3\Domain\paypalInvoice');
};

$app['dao.sale'] = function($app){
    return new \WF3\DAO\SaleDAO($app['db'], 'sale', 'WF3\Domain\Sale');
};



