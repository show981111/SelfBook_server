<?php 
	
	header('Content-Type: application/json; charset=UTF-8'); 
	header("HTTP/1.1 200 OK"); 
	header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");

	error_reporting(E_ALL);

	ini_set("display_errors", 1);

	echo 'index called!';
	$request = $_SERVER['REQUEST_URI'];
	
	$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$uri = explode('/', $uri);

	if ($uri[1] !== 'user') { 
		echo 'nothing!';
		//header("HTTP/1.1 404 Not Found"); 
		exit(); 
	}

	$requestMethod = $_SERVER["REQUEST_METHOD"];

	// switch ($requestMethod) {
	// 	case 'POST':
	// 		require_once('getUserInfo.php');
	// 		break;
		
	// 	default:
	// 		# code...
	// 		break;
	// }

?>
