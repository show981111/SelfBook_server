<?php
	
	//consoleTest("hi");
	require_once './vendor/autoload.php';
	require_once('selfBook.php');
	error_reporting(E_ALL);

	ini_set("display_errors", 1);


	//$userID = $_GET['userID'];
	$userID = $this->userID;
	$templateCode = $_GET['templateCode'];
	//$userID = 'show981111@gmail.com';
	// $templateCode = '1';
	// $userID = "test1";
	// $userPassword = "test1";
	// $userName = "test1";
	// $userID = implode("", $userID);
	// $templateCode = implode("", $templateCode);
	
	$test = new selfBook();
	if(isset($userID) && isset($templateCode))
	{
		$test->downloadDocx($userID, $templateCode);
	}else{
		http_response_code(403);
		die('Forbidden');
		echo "fail";
	}
?>