<?php
	
	//consoleTest("hi");
	require_once('selfBook.php');

	$userID = $_POST['userID'];
	$userPassword = $_POST['userPassword'];
	//$userID = "test0";
	// $userPassword = "test";
	// $userID = "test5";
	// $userPassword = "test5";

	$test = new selfBook();

	if(isset($userPassword))
	{
		
		$test->getUserInfo($userID, $userPassword);
	}else{
		
		$test->getUserInfo($userID);
	}
	
?>