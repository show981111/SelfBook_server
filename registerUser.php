<?php
	
	//consoleTest("hi");
	require_once('selfBook.php');

	$userID = $_POST['userID'];
	$userPassword = $_POST['userPassword'];
	$userName = $_POST['userName'];
	// $userID = "test1";
	// $userPassword = "test1";
	// $userName = "test1";
	$hashed_password = password_hash($userPassword, PASSWORD_DEFAULT);
	$test = new selfBook();
	if(isset($userID) && isset($userPassword) && isset($userName))
	{
		$test->registerUser($userID, $hashed_password, $userName);
	}
?>