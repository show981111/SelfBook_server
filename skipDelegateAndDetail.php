<?php
	
	//consoleTest("hi");
	require_once('selfBook.php');

	parse_str(file_get_contents('php://input'), $_PUT);
	//var_dump($_PUT);

	// echo file_get_contents('php://input');
	$userID = $_PUT["userID"];
	$delegateCode = $_PUT["delegateCode"];

	$test = new selfBook();
	if(isset($userID) && isset($delegateCode))
	{
		$test->skipDelegateAndDetail($userID, $delegateCode);
	}else{
		echo "fail";
	}
?>