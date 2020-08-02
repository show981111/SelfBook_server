<?php
	
	//consoleTest("hi");
	require_once('selfBook.php');

	parse_str(file_get_contents('php://input'), $_PUT);
	//var_dump($_PUT);

	// echo file_get_contents('php://input');

	$userID = $_PUT['userID'];
	$userPassword = $_PUT['userPassword'];
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