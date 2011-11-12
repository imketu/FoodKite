<?php

/**
 * Step 1: Require the Slim PHP 5 Framework
 *
 * If using the default file layout, the `Slim/` directory
 * will already be on your include path. If you move the `Slim/`
 * directory elsewhere, ensure that it is added to your include path
 * or update this file path as needed.
 */
require 'Slim/Slim.php';
include_once('Zend/Cache.php');
include_once('Libs/DatabaseAbstraction.Class.php');
require 'Libs/Facebook/facebook.php';

/**
 * Step 2: Instantiate the Slim application
 *
 * Here we instantiate the Slim application with its default settings.
 * However, we could also pass a key-value array of settings.
 * Refer to the online documentation for available settings.
 */
$app = new Slim(array(
										'templates.path' => './Templates',
										'mode' => 'development'
								));

$app->configureMode('production', function () use ($app) {
    $app->config(array(
        'log.enable' => true,
        'log.path' => '../logs',
        'debug' => false
    ));
});

$app->configureMode('development', function () use ($app) {
    $app->config(array(
        'log.enable' => false,
        'debug' => true
    ));
});

$app->config(array(
    'foursqaureClientKey' => "2RQUQ00FWEYXU3H22VZFWYBXIIL1Y12GQOHMS0NTVMIYSSIN",
    'foursqaureClientSecret' => "UUMORJWMLYSNDCJGG4D4DL4ITH1UVT2WGFXXKALJWSUDED3B"
));



/* set up cache resource*/
$frontendOptions = array(
 'lifetime' => 57600, 
 'automatic_serialization' => true
);

$backendOptions = array(
	  'cache_dir' => 'Data/' // Directory where to put the cache files
);

$app->cache = Zend_Cache::factory('Core',
                           'File',
                           $frontendOptions,
                           $backendOptions);

/* set up db resource*/
$app->db = new DB('localhost', 'root', 'admin', 'FoodkiteDBV2');


/* setup facebook SDK resources*/

$app->facebook = new Facebook(array(
	'appId'  => '252574368127488',
	'secret' => '30878cf580a58b83c80281bebd1aac99',
));



/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, and `Slim::delete`
 * is an anonymous function. If you are using PHP < 5.3, the
 * second argument should be any variable that returns `true` for
 * `is_callable()`. An example GET route for PHP < 5.3 is:
 *
 * $app = new Slim();
 * $app->get('/hello/:name', 'myFunction');
 * function myFunction($name) { echo "Hello, $name"; }
 *
 * The routes below work with PHP >= 5.3.
 */
require 'Modules/Users.php';
require 'Modules/Place.php';
require 'Modules/Menu.php';
require 'Modules/Tuckin.php';
require 'Modules/Admin.php';

//GET route
$app->get('/', $authenticateUsers, function () use ($app) {
   $app->redirect("/FoodKite/App/place/discovery/");
});

$app->get('/login-panel/',  function() use ($app){
	$args= array ('scope' => 'offline_access,email,publish_stream,user_birthday,user_location,read_stream,friends_likes',
								'redirect_uri' => 'http://foodkite.me/FoodKite/App/login/',
								'display' => 'touch');
	$loginUrl = $app->facebook->getLoginUrl($args);
	$app->render('login.php', array("title"=> "Log in using FB cradentials", "loginUrl"=>$loginUrl , "isDialog" => TRUE) );
});


$app->get('/login/(:returnvalue)', function() use ($app, $loginWebUsers){
	$loginWebUsers();
	$app->redirect("/FoodKite/App/");
});

$app->get('/logout/(:returnvalue)',  function() use ($app){
	session_destroy();
	$app->redirect("/FoodKite/App/login-panel/");
});

$app->get("/notification/", function() use ($app){
	$message = $app->db->query("SELECT `Message` FROM Notification WHERE USER_FBID = ?", $_SESSION["USER_FBID"])->fetch(0);
	if(!empty($message))
		echo $message;
});

//POST route
$app->post('/post', function () {
    echo 'This is a POST route';
});

//PUT route
$app->put('/put', function () {
    echo 'This is a PUT route';
});

//DELETE route
$app->delete('/delete', function () {
    echo 'This is a DELETE route';
});

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This is responsible for executing
 * the Slim application using the settings and routes defined above.
 */
$app->run();
