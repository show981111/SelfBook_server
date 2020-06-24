<?php
	
	//consoleTest("hi");
	require_once('selfBook.php');

	$userID = $_POST['userID'];
	$key = $_POST['key'];
	$input = $_POST['input'];
	$from = $_POST['from'];
	// $userID = "test1";
	// $templateCode = "6";
	// $userPassword = "test1";
	// $userName = "test1";
	// $userID = "test0";
	// $key = "1";
	// $input = "adsad";
	// $from = "setBookTitle";
	$test = new selfBook();
	if(isset($userID) && isset($input))
	{
		$test->setUserAnswer($userID, $key, $input, $from);
	}
?>