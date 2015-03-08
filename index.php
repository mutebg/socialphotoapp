<?php
ini_set('display_errors', 'On');
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

session_start();


require 'backend/config.php';
require 'backend/3rdparty/Slim/Slim.php';
require 'backend/3rdparty/NotORM/NotORM.php'; 
require 'backend/inc/SocialService.php';
require 'backend/inc/helpers.php';

//init db connection and ORM
$pdo = new PDO('mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'], 
	$config['db_user'], $config['db_pass'], 
	array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$db = new NotORM($pdo);

//init slim framework
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

//config framework
$app->config( array(
	'templates.path' => $config['template_dir']
));

$app->myConfig = $config;
$app->db = $db;
$app->view->setData('base', $config['base_url']);


//home page
$app->get('/', function () use ($app) {
	$facebookService 	= new SocialService('facebook', 	$app->myConfig);
	$instaService 		= new SocialService('instagram', 	$app->myConfig);
	$dropboxService 	= new SocialService('dropbox', 		$app->myConfig);

	$app->render('welcome.php', array( 
		'facebook_login_url' 	=> $facebookService->getLoginURL(),
		'instagram_login_url' 	=> $instaService->getLoginURL(),
		'dropbox_login_url' 	=> $dropboxService->getLoginURL(),
	));
});


//return address after login in services
$app->get('/return/:social/', function($social) use ($app) {

	$socialService = new SocialService($social, $app->myConfig);
	$status = $socialService->handleResponse();	

	if ( $status ) {
		$redirectURL = 'app/' . $social . '/';
	}  else {
		$redirectURL = '?error=' . $social .  '_auth';
	}
	
	//redirect to page where user could select time interval
	$app->response->redirect($app->myConfig['base_url'] . $redirectURL );
});


//page where user cand select dates and view own photos
$app->get('/app/:social/', function($social) use ($app) {
	$app->render('app.php', array( 'social' => $social ) );
});


//post begin and end date, and process to copy images from social
$app->get('/photos/', function() use ($app) {
	$from = strtotime($_GET['from']);
	$to = strtotime($_GET['to']);
	$social = $_GET['social'];

	$socialService = new SocialService($social, $app->myConfig);
	$photos = $socialService->getPhotos($from, $to);

	//save photos to DB
	if ( $photos ) {
		$dir = $app->myConfig['photos_dir'];
		$userID = time(); //@TODO

		foreach ($photos as $i => $photo) {
			$dest = prepareName($photo['path']);
			
			if ( copyImage($photo['path'], $dir . $dest) ) {
				$insert = array (
					'social' 	=> $social,
					'path'		=> $dest,
					'user_id' 	=> $userID,
					'name'		=> $photo['name'],
					'date_add'	=> $photo['date'],
				);
				$app->db->photos()->insert($insert);
			}
			$photos[$i]['path'] =  $dir . $dest;
		}
		$result = array('status' => 'success', 'data' => $photos);
	} else {
		$result = array('status' => 'error', 'message' => 'No photos' );
	}

	//return json for angular gallery app
	echo json_encode($result);
});

$app->run();