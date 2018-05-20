<?php session_start(); ?>
<?php 
//DEFINE INCLUDE PATH
$root = $_SERVER['DOCUMENT_ROOT'];
$path = '/duffa/';
set_include_path($root . $path);


//include classes 
spl_autoload_register(function ($class) {
	$file = str_replace('\\', '/', $class);
	require "php/models/" . $file . ".php";
});

//include facebook sdk
require "facebook/vendor/autoload.php";
	$fb = new Facebook\Facebook([
		'app_id' => '',
		'app_secret' => '',
		'default_graph_version' => 'v3.0'
	]);

$fb_app_id = '209234716344947';

//create a cookie if logged in and it's been requested
if(isset($_SESSION['userid'], $_GET['remember']) && $_GET['remember'] == true) {
	$rememberMe = new Account();
	$rememberMe->getCookie();
}

//log in if cookie matches
if(isset($_COOKIE['remembermeduffa'])) {
	$cookie = new Account();
	$cookie->rememberMe();
}

//unset the session variables/shop cookie so that the user can shop fresh
if(isset($_GET['unset']) && $_GET['unset'] == true) {
$_SESSION['cart'] = '[]';
$_SESSION['order'] = '[]';
setcookie('duffa_shop', '[]', time() - 3600, "/duffa/"); 
}
?>
