<?php
	
	//consoleTest("hi");
	require_once('selfBook.php');

	//$userID = $_GET['userID'];
	$userID = $this->userID;
	$templateCode = $_GET['templateCode'];
	//$userID = $_GET['userID'];
	// $templateCode = "1";

	// $userID = "test0";
	// $templateCode = "1";

	$test = new selfBook();

	if(isset($userID) && isset($templateCode))
	{
		
		$test->makeOverView($userID, $templateCode);
	}
?>