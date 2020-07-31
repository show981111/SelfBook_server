<?php
	
	require_once './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	require_once('selfBook.php');

	error_reporting(E_ALL);

	ini_set("display_errors", 1);

	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Methods: POST");
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


	$userID = $_POST['userID'];
	$userPassword = $_POST['userPassword'];



	if(isset($userID) && isset($userPassword))
	{
		$test = new selfBook();
		$test->userAuth($userID, $userPassword);
	}else{
		echo "fail";
	}
?>