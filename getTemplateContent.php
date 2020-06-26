<?php
	
	//consoleTest("hi");
	require_once('selfBook.php');
	// error_reporting(E_ALL);

	// ini_set("display_errors", 1);
	// $userID = $_POST['userID'];
	// $templateCode = $_POST['templateCode'];
	$userID = "test0";
	$templateCode = "1";
	// $userPassword = "test1";
	// $userName = "test1";
	//echo "dsad";
	$test = new selfBook();

	if(isset($userID) && isset($templateCode))
	{
		$test->getTemplateContent($userID, $templateCode);
	}
?>