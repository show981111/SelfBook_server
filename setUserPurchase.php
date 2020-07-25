<?php
	
	//consoleTest("hi");
	require_once('selfBook.php');

	$userID = $_POST['userID'];
	$templateCode = $_POST['templateCode'];
	
	// $userID = "test1";
	// $templateCode = "6";
	// $userPassword = "test1";
	// $userName = "test1";

	$test = new selfBook();
	if(isset($userID) && isset($templateCode))
	{
		$test->setUserPurchase($userID, $templateCode);
	}else{
		echo "fail";
	}
?>