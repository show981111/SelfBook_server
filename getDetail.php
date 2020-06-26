<?php
	
	//consoleTest("hi");
	require_once('selfBook.php');
	// error_reporting(E_ALL);

	// ini_set("display_errors", 1);
	$userID = $_POST['userID'];
	$delegateCode = $_POST['delegateCode'];
	// $userID = "test0";
	// $delegateCode = "7";
	// $userPassword = "test1";
	// $userName = "test1";
	//echo "dsad";
	$test = new selfBook();

	if(isset($userID) && isset($delegateCode))
	{
		$test->getDetail($userID, $delegateCode);
	}
?>