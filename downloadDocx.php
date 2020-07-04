<?php
	
	//consoleTest("hi");
	require_once './vendor/autoload.php';
	require_once('selfBook.php');
	error_reporting(E_ALL);

	ini_set("display_errors", 1);

	// $userID = $_POST['userID'];
	// $templateCode = $_POST['templateCode'];
	$userID = 'show981111@gmail.com';
	$templateCode = '1';
	// $userID = "test1";
	// $userPassword = "test1";
	// $userName = "test1";
	
	$test = new selfBook();
	if(isset($userID) && isset($templateCode))
	{
		$test->downloadDocx($userID, $templateCode);
	}else{
		echo "fail";
	}
?>