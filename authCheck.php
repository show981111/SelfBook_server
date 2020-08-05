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
	$tokenUserID;

	$jwt = null;
	$granted = 0;
	
	if($uri[1] !== 'login' && $uri[1] !== "getTemplateInfo.php" && $uri[1] !== "templateInfo" && $uri[1] !== "registerUser.php"
		&& $uri[1] !== 'authCode' && $uri[1] !== 'resetUser' && $uri[1] !== 'checkVerificationCode' ){

		$data = json_decode(file_get_contents("php://input"));
		$authHeader = $_SERVER['HTTP_AUTHORIZATION'];
		$arr = explode(" ", $authHeader);
		$jwt = $arr[1];
		//echo $jwt;
		
		if($jwt){

		    try {
		        $decoded = JWT::decode($jwt, $secretKey, array('HS256'));
		        $granted = 1;
		        $decoded_array = (array) $decoded;
		        $dataLoaded = (array) $decoded_array['data'];
		        // echo $dataLoaded['userID'];
		        // echo "Decode:\n" . print_r($decoded_array, true) . "\n";
		    }catch (Exception $e){
		    	//echo $e;
			    http_response_code(401);
			    exit();
			}

			if($granted == 1){//클라이언트가 준 토큰이 유저가 로그인했을때 받은 토큰이 맞는지 체크!
				$userID = $dataLoaded['userID'];
				$issuedAt =  $decoded_array['iat'];
				//echo $userID. " ". $issuedAt;
				$con = mysqli_connect($host, $user, $pass, $db) or die('Unable to connect');
				$select = "SELECT tokenIssuedAt FROM USER WHERE userID = '$userID' ";
				$res = mysqli_query($con, $select);

				while($row = mysqli_fetch_array($res)){
					if($issuedAt == $row[0]){
						//echo "success";
						$tokenUserID = $userID;
						return;
					}else{
						http_response_code(401);
						exit();
					}
				}
			}
		}else{
			http_response_code(401);
			exit();
		}

	
	}


	
?>