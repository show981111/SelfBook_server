<?php
	
	//consoleTest("hi");
	require_once('selfBook.php');

	// $userID = $_POST['userID'];
	// $key = $_POST['key'];
	// $input = $_POST['input'];
	// $from = $_POST['from'];

	parse_str(file_get_contents('php://input'), $_PUT);
	// var_dump($_PUT);

	// echo file_get_contents('php://input');

	//$userID = $_PUT['userID'];
	$userID = $this->userID;
	$key = $_PUT['key'];
	$input = $_PUT['input'];
	$from = $_PUT['from'];
	
	$test = new selfBook();
	if(isset($userID) && isset($input))
	{
		$test->setUserAnswer($userID, $key, $input, $from, true);
	}
?>