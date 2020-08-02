<?php
	
	//consoleTest("hi");
	require_once('selfBook.php');
	// error_reporting(E_ALL);

	// ini_set("display_errors", 1);
	$userID = $_GET['userID'];
	$delegateCode = $_GET['delegateCode'];
	// $userID = "show981111@gmail.com";
	// $delegateCode = "20";
	// $userPassword = "test1";
	// $userName = "test1";
	//echo "dsad";
	$test = new selfBook();

	if(isset($userID) && isset($delegateCode))
	{
		$test->getDetail($userID, $delegateCode);
	}
?>