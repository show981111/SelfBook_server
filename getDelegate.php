<?php
	
	//consoleTest("hi");
	require_once('selfBook.php');
	
	// error_reporting(E_ALL);

	// ini_set("display_errors", 1);
	$userID = $_GET['userID'];
	$chapterCode = $_GET['chapterCode'];

	// $userID = $_POST['userID'];
	// $chapterCode = $_POST['chapterCode'];
	// $userID = "show981111@gmail.com";
	// $chapterCode = "2";
	// $userPassword = "test1";
	// $userName = "test1";
	//echo "dsad";
	$test = new selfBook();

	if(isset($userID) && isset($chapterCode))
	{
		$test->getDelegate($userID, $chapterCode);
	}
?>