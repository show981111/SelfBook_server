<?php
	
	//consoleTest("hi");
	require_once('selfBook.php');

	$userID = $_POST['userID'];
	$userPassword = $_POST['userPassword'];
	// $userID = "test1";
	// $userPassword = "test1";
	// $userName = "test1";
	
	$test = new selfBook();
	if(isset($userID) && isset($userPassword))
	{
		$hashed_password = password_hash($userPassword, PASSWORD_DEFAULT);
		$test->resetPW($userID, $hashed_password);
	}else{
		echo "fail";
	}
?>