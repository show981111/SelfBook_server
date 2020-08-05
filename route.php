<?php

	
	class Route{

		public $userID;

		public function __construct($tokenUserID)
		{
			$this->userID = $tokenUserID;
			//echo "ROUTE INIT";
		}
		
		public function get($request){
			switch ($request) {
				case 'templateInfo':
					require_once('getTemplateInfo.php');
					break;
				// case 'templateContents':
				// 	require_once('getTemplateContent.php');
				// 	break;
				case 'overview':
					require_once('makeOverView.php');
					break;
				case 'chapters':
					require_once('getChapter.php');
					break;
				case 'delegates':
					require_once('getDelegate.php');
					break;
				case 'details':
					require_once('getDetail.php');
					break;
				case 'purchases':
					//echo "constructer ".$this->userID;
					require_once('getUserInfo.php');
					break;
				case 'draft':
					require_once('downloadDocx.php');
					break;
				
				default:
					header("HTTP/1.1 404 Not Found"); 
					break;
			}
		}

		public function post($request){


			switch ($request) {
				case 'user':
					require_once('registerUser.php');
					break;
				case 'purchases':
					require_once('setUserPurchase.php');
					break;
				case 'login':
					require_once('userAuth.php');
					break;
				case 'authCode':
					require_once('sendAuth.php');
					break;
				case 'checkVerificationCode':
					require_once('checkVerificationCode.php');
					break;

				default:
					header("HTTP/1.1 404 Not Found"); 
					break;
			}
		}

		public function put($request){
			switch ($request) {
				case 'answer':
					require_once('setUserAnswer.php');
					break;
				case 'resetUser':
					require_once('resetPW.php');
					break;
				case 'answers':
					require_once('skipDelegateAndDetail.php');
					break;
				case 'draft':
					require_once('makeDocx.php');
					break;
				
				default:
					header("HTTP/1.1 404 Not Found"); 
					break;
			}
		}


	}
?>