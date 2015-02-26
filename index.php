<?php
putenv("env=development");
require_once 'vendor/autoload.php';
use \Fleet\Utils as Utils;

error_reporting(E_ALL & ~E_NOTICE);


// needed when installed into subdirectory
// check https://github.com/chriso/klein.php/wiki/Sub-Directory-Installation
$base  = dirname($_SERVER['PHP_SELF']);
if(ltrim($base, '/')){
    $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], strlen($base));
}

$klein = new \Klein\Klein();
$config = \Fleet\Config::getInstance();
$db = \Fleet\DB::getInstance();
$template = \Fleet\Template::getInstance();
$klein->config = $config;
$klein->db = $db;
$klein->template = $template;

date_default_timezone_set($config->app->timezone);

// route for /
$klein->respond('GET', '/', function ($request, $response, $service, $app) use ($klein) {
  // Set language to English
  putenv('LC_ALL=en_US');
  setlocale(LC_ALL, 'en_US');
  $response->redirect($klein->config->app->url."/en");
});

$klein->respond('GET', '/it', function ($request, $response, $service, $app) use ($klein) {
  putenv('LC_ALL=it_IT');
  setlocale(LC_ALL, 'it_IT');
	echo $klein->template->render('it.twig', (array) $klein->config);
});

$klein->respond('GET', '/en', function ($request, $response, $service, $app) use ($klein) {
  // Set language to English
  putenv('LC_ALL=en_US');
  setlocale(LC_ALL, 'en_US');
  echo $klein->template->render('en.twig', (array) $klein->config);
});

foreach (glob("application/controllers/*.php") as $filename)
{
    include $filename;
}

$klein->dispatch();
