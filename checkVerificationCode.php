<?php
	
	//consoleTest("hi");
	require_once('selfBook.php');
	// error_reporting(E_ALL);

	// ini_set("display_errors", 1);
	$userID = $_POST['userID'];
	$verificationCode = $_POST['verificationCode'];
	$temp = $_POST['temp'];

	// $userID = $_POST['userID'];
	// $templateCode = $_POST['templateCode'];
	// $userID = "show981111@gmail.com";
	// $templateCode = "1";
	// $userPassword = "test1";
	// $userName = "test1";
	//echo "dsad";
	$test = new selfBook();

	if(isset($userID) && isset($verificationCode) && isset($temp))
	{
		if($temp == "false")
		{
			$test->checkVerificationCode($userID, $verificationCode, "USER");
		}else{
			//echo "TEMP";
			$test->checkVerificationCode($userID, $verificationCode, "TEMPUSER");
		}
	}
?>