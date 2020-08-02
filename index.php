<?php 
	
	header('Content-Type: application/json; charset=UTF-8'); 
	header("HTTP/1.1 200 OK"); 
	header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT");

	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	//header("Access-Control-Allow-Methods: POST");
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

	include 'db.php';
	require_once './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	// error_reporting(E_ALL);

	// ini_set("display_errors", 1);

	$request = $_SERVER['REQUEST_URI'];
	
	$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$uri = explode('/', $uri);

	// if ($uri[1] !== 'auth') { 
	// 	echo 'nothing!';
	// 	//header("HTTP/1.1 404 Not Found"); 
	// 	exit(); 
	// }
	$jwt = null;
	$granted = 0;

	if($uri[1] !== 'login'){
		$data = json_decode(file_get_contents("php://input"));
		$authHeader = $_SERVER['HTTP_AUTHORIZATION'];
		$arr = explode(" ", $authHeader);
		$jwt = $arr[1];
		//echo $jwt;
		
		if($jwt){

		    try {
		        $decoded = JWT::decode($jwt, $secretKey, array('HS256'));
		        $granted = 1;
		    }catch (Exception $e){
			    http_response_code(401);
			    return;
			}
		}else{
			http_response_code(401);
			return;
		}
	}

	if($granted == 1 || $uri[1] === 'login'){

		require_once('route.php');

		$requestMethod = $_SERVER["REQUEST_METHOD"];

		$route = new Route();
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
