<?php
	
	//consoleTest("hi");
	require_once('selfBook.php');

	$userID = $_POST['userID'];
	$templateCode = $_POST['templateCode'];
	// $userID = "show981111@gmail.com";
	// $templateCode = "1";

	// $userID = "test0";
	// $templateCode = "1";

	$test = new selfBook();

	if(isset($userID) && isset($templateCode))
	{
		
		$test->makeOverView($userID, $templateCode);
	}
?>