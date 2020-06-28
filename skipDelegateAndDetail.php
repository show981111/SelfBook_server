<?php
	
	//consoleTest("hi");
	require_once('selfBook.php');

	$userID = $_POST['userID'];
	$delegateCode = $_POST['delegateCode'];
	// $userID = "test1";
	// $templateCode = "6";
	// $userPassword = "test1";
	// $userName = "test1";
	// $userID = "test0";
	// $key = "1";
	// $input = "adsad";
	// $from = "setBookTitle";
	$test = new selfBook();
	if(isset($userID) && isset($delegateCode))
	{
		$test->skipDelegateAndDetail($userID, $delegateCode);
	}else{
		echo "fail";
	}
?>