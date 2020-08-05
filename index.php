<?php 

	// error_reporting(E_ALL);

	// ini_set("display_errors", 1);

	require_once('authCheck.php');

	 
	if($granted == 1 || $uri[1] === 'login' || $uri[1] === 'templateInfo' || $uri[1] === 'authCode' || $uri[1] === 'resetUser' || $uri[1] === 'checkVerificationCode' ){

		require_once('route.php');
		$requestMethod = $_SERVER["REQUEST_METHOD"];

		$route = new Route($tokenUserID);
		// $route->get($uri[1]);

		switch ($requestMethod) {
			case 'GET':
				$route->get($uri[1]);
				break;
			case 'POST':
				$route->post($uri[1]);
				break;
			case 'PUT':
				$route->put($uri[1]);
				break;
			
			default:
				header("HTTP/1.1 404 Not Found"); 
				break;
		}
	}

?>
