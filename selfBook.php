<?php
	

	class selfBook{

		private $con;
		private $s3;
		private $bucketName;

		public function __construct()
		{
			include 'db.php';
			$this->con = mysqli_connect($host, $user, $pass, $db) or die('Unable to connect');
		}

		function __destruct()
		{
			mysqli_close($this->con);
		}

		public function getTemplateInfo()
		{
			$query = "SELECT TemplateCode,Author,TemplateName,price,madeDate, bookCover, templateIntro FROM TEMPLATEOVERVIEW ";
			$result = mysqli_query($this->con,$query);

			$response = array();

			if($result)
			{
				while($row = mysqli_fetch_array($result)){
		
					array_push($response, array("templateCode"=>$row[0], "author"=>$row[1], "templateName"=>$row[2],"bookPrice"=>$row[3], "madeDate" => $row[4], "bookCover" => $row[5], "templateIntro" => $row[6] ));
				}
			}

			echo json_encode($response,JSON_UNESCAPED_UNICODE);
		}

		public function registerUser($userID, $userPassword, $userName)
		{
			$already = "SELECT * FROM USER WHERE userID = '$userID' ";
			$alreadyQuery = mysqli_query($this->con, $already);
			if(mysqli_num_rows($alreadyQuery) > 0)
			{
				echo "already";
				return;
			}
			$query = "INSERT INTO USER(userID, userPassword, userName) VALUES ('$userID', '$userPassword', '$userName')";
			$result = mysqli_query($this->con, $query);

			$response;

			if(mysqli_affected_rows($this->con) > 0)
			{
				$response = "success";
			}else
			{
				$response = "fail";
			}

			echo $response;
		}

		public function getUserInfo()
		{
			$userID;
			$userPassword;
			$count = 0;
			for ($i = 0; $i < func_num_args(); $i++) {
				if($i == 0)
				{
					$userID = func_get_arg($i);
				}
				if($i == 1)
				{
					$userPassword = func_get_arg($i);
				}
				$count++;
			}
			
			$query;
			
			$query = "SELECT A.userID, A.userName, B.TemplateCode,B.title ,B.publishDate, A.userPassword, C.bookCover FROM USER A 
					LEFT JOIN USERPURCHASES B ON A.userID = B.userID 
					LEFT JOIN TEMPLATEOVERVIEW C ON B.TemplateCode = C.TemplateCode
					WHERE A.userID = '$userID' ";
			$flag = 1;
			if(isset($query))
			{
				$result = mysqli_query($this->con, $query);
				$response = array();
				if($result)
				{
					while($row = mysqli_fetch_array($result)){
						if($count == 2)
						{
							if(password_verify($userPassword, $row[5])){
								$falg = 1;
							}else{
								$flag = 0;
								// echo $userPassword. " ". $row[5];
							}
						}
						if($flag == 1)
						{
							array_push($response, array("userID"=>$row[0], "userName"=>$row[1], "userTemplateCode"=>$row[2], "userBookName"=>$row[3],"userBookPublishDate" => $row[4], "userBookCover" => $row[6] ));
						}
					}
				}
			}

			echo json_encode($response,JSON_UNESCAPED_UNICODE);
		}

		public function setUserPurchase($userID, $templateCode)
		{
			$already = "SELECT * FROM USERPURCHASES WHERE userID = '$userID' AND TemplateCode = '$templateCode' ";
			$res = mysqli_query($this->con, $already);
			if(mysqli_num_rows($res) > 0)
			{
				echo "already";
				return;
			}
			$query = "INSERT INTO USERPURCHASES(userID, TemplateCode) VALUES ('$userID', '$templateCode')";
			$result = mysqli_query($this->con, $query);
			$response;
			if(mysqli_affected_rows($this->con) > 0)
			{
				$response = "success";
			}else
			{
				$response = "fail";
			}
			echo $response;
		}

		public function setUserAnswer($userID, $key, $input, $from, $isEcho)
		{
			$query; 
			$response;
			if($from == "setBookTitle"){
				$query = "UPDATE USERPURCHASES SET title = '$input' WHERE userID = '$userID' AND TemplateCode = '$key' ";
			}else{
				$selectSame = "SELECT * FROM USERANSWER WHERE userID = '$userID' AND Q_ID = '$key' AND answer = '$input' ";
				$selectQuery = mysqli_query($this->con, $selectSame);
				if(mysqli_num_rows($selectQuery ) > 0)
				{
					$response = "redundant";
				}else{
					$update_query = "UPDATE USERANSWER SET answer = '$input' WHERE userID = '$userID' AND Q_ID = '$key' ";
					$res = mysqli_query($this->con, $update_query);
					if(mysqli_affected_rows($this->con) > 0)
					{
						$response = "success";
					}else
					{
						$query = "INSERT INTO USERANSWER(Q_ID, userID, answer) VALUES ('$key', '$userID', '$input')";
						$response = "fail";
					}
				}
			}
			
			if($response != "success" && $response != "redundant")
			{
				$result = mysqli_query($this->con, $query);
		
				if(mysqli_affected_rows($this->con) > 0)
				{
					$response = "success";
				}else
				{
					$response = "fail";
				}
			}
			if($isEcho == true)
			{
				echo $response;
			}else{
				return $response;
			}

		}

		public function getTemplateContent($userID, $templateCode)//모든 템플릿 내용 한번에 파씽 
		{
			$query = "SELECT A.ID AS templateCode, A.name AS templateName, B.ID AS chapterCode, B.name AS chapterName,C.ID AS delegateCode, C.name AS delegateName, C.hint AS delegateHint , F.answer AS delegateAnswer, D.ID AS detailCode, D.name AS detailName , D.hint AS detailHint, E.answer AS detailAnswer
				FROM TEMPLATECONTENT AS A
				LEFT JOIN TEMPLATECONTENT AS B ON B.P_ID = A.ID
				LEFT JOIN TEMPLATECONTENT AS C ON C.P_ID = B.ID
				LEFT JOIN USERANSWER AS F ON (F.Q_ID = C.ID AND F.userID = '$userID')
				LEFT JOIN TEMPLATECONTENT AS D ON D.P_ID = C.ID
				LEFT JOIN USERANSWER AS E ON (E.Q_ID = D.ID AND E.userID = '$userID')
				WHERE A.ID = '$templateCode' order by templateCode ASC, chapterCode ASC, delegateCode ASC, detailCode ASC ";

			$result = mysqli_query($this->con, $query);
			$response = array();
			$count = 0;
			$pastChapterCode;
			$pastDelegateCode;
			$pastDetailCode;
			$chapterIndex = -1;
			$delegateIndex = -1;
			$delegateArray = array();
			
			while($row = mysqli_fetch_assoc($result)){
				//echo "dd";
				if($count == 0)
				{
					$response['templateCode'] = $row['templateCode'];
					$response['templateName'] = $row['templateName'];
					$response['templateChildren'] = array();
					$count = 1;
				}

				if(!empty($row['chapterCode']) && !empty($row['chapterName'])  )
				{	
					if($pastChapterCode != $row['chapterCode'])//중복 삽입 방지를 위해서, 이미 삽입된 챕터가 아닐 경우에 
					{
						$pastChapterCode = $row['chapterCode'];

						array_push($response['templateChildren'], array("chapterCode" =>$row['chapterCode'] , "chapterName" => $row['chapterName'], "chapterChildren" => array() ) );
						$chapterIndex++;//인덱스 증가
						$delegateIndex = -1;
						//echo "#####CHAPINDEX".$chapterIndex."#######";
						//echo json_encode($response['templateChildren'][$chapterIndex],JSON_UNESCAPED_UNICODE);
					}
					
					// if($delegateItem){
					// 	array_push($response['templateChildren'][$chapterIndex]['chapterChildren'], $delegateArray);
						
					// }
				}
				if(!empty($row['delegateCode']) && !empty($row['delegateName']) ){
					//echo $pastQuestionCode. " VS ". $row['questionCode'];
					if($pastDelegateCode != $row['delegateCode'])//중복 삽입 방지를 위해서
					{
						//$delegateItem = true;
						$pastDelegateCode = $row['delegateCode'];
						array_push($response['templateChildren'][$chapterIndex]['chapterChildren'], array("delegateCode" =>$row['delegateCode'] , "delegateName" => $row['delegateName'], "delegateHint" => $row['delegateHint'], "delegateAnswer" => $row['delegateAnswer'], "delegateChildren" => array() ) );
						$delegateIndex++;
						
					}
					// if(isset($detailArray)){
					// 	echo "=============";
					// 	echo json_encode(array('result' => $detailArray),JSON_UNESCAPED_UNICODE);
					// 	array_push($delegateArray[$delegateIndex]['delegateChildren'], $detailArray);
						
					// }
				}
				//$detailArray = null;

				if(!empty($row['detailCode']) && !empty($row['detailName']) ){
					//echo $pastQuestionCode. " VS ". $row['questionCode'];
					if($pastDetailCode != $row['detailCode'])//중복 삽입 방지를 위해서
					{
						array_push($response['templateChildren'][$chapterIndex]['chapterChildren'][$delegateIndex]['delegateChildren'], 
							array("detailCode" =>$row['detailCode'] , "detailName" => $row['detailName'], "detailHint" => $row['detailHint'], "detailAnswer" => $row['detailAnswer'] ) );
						// $detailArray =  array("detailCode" =>$row['detailCode'] , "detailName" => $row['detailName'], "hint" => $row['detailHint'], "answer" => $row['answer'] );
						$pastDetailCode = $row['detailCode'];

					}
				}


				//$delegateItem = false;//&& isset($row['hint']) && isset($row['answer'])
				

				
			}
			//echo "+++++++++++++++++++++++++++++++++";
			//$result = array("result" => $response);
			//echo json_encode($result,JSON_UNESCAPED_UNICODE);
			echo json_encode(array('result' => $response),JSON_UNESCAPED_UNICODE);

		}

		public function getChapter($userID, $templateCode)
		{
			$response = array();
			$query = "SELECT A.ID AS templateCode, A.name AS templateName, B.ID AS chapterCode, B.name AS chapterName 
				FROM TEMPLATECONTENT AS A
				LEFT JOIN TEMPLATECONTENT AS B ON B.P_ID = A.ID
				WHERE A.ID = '$templateCode' order by templateCode ASC, chapterCode ASC";
			$res = mysqli_query($this->con, $query);

			while($row = mysqli_fetch_array($res))
			{
				$status = 1;

				$getChapterChildren = "SELECT C.ID AS delegateCode, F.answer AS delegateAnswer, D.ID AS detailCode, E.answer AS detailAnswer
					FROM TEMPLATECONTENT AS B
					LEFT JOIN TEMPLATECONTENT AS C ON C.P_ID = B.ID
					LEFT JOIN USERANSWER AS F ON (F.Q_ID = C.ID AND F.userID = '$userID')
					LEFT JOIN TEMPLATECONTENT AS D ON D.P_ID = C.ID
					LEFT JOIN USERANSWER AS E ON (E.Q_ID = D.ID AND E.userID = '$userID')
					WHERE B.ID = '$row[2]' order by delegateCode ASC, detailCode ASC ";


				$exe = mysqli_query($this->con, $getChapterChildren);

				while($answers = mysqli_fetch_array($exe))
				{
					//echo $answers[0]."A" .$answers[1]."A".$answers[2]."A".$answers[3]."\n";
					if(!empty($answers[0]) && empty($answers[1])){//delegate answer 가 비어있는 경우 
						$status = 0;
						break;
					}
					if(!empty($answers[2]) && empty($answers[3]) )// detailCode가 비어있지 않은데 detailAnswer 가 비어있는 경우 
					{
						$status = 0;
						break;
					}
				}

				array_push($response, array('chapterCode' => $row[2], 'chapterName' => $row[3], 'status' => $status ));
			}

			echo json_encode($response,JSON_UNESCAPED_UNICODE);

		}

		public function getDelegate($userID, $chapterCode)
		{
			$response = array();
			$query = "SELECT B.ID AS chpaterCode, B.name AS chapterName, C.ID AS delegateCode, C.name AS delegateName, C.hint AS delegateHint , D.answer AS delegateAnswer 
				FROM TEMPLATECONTENT AS B
				LEFT JOIN TEMPLATECONTENT AS C ON C.P_ID = B.ID 
				LEFT JOIN USERANSWER AS D ON (D.Q_ID = C.ID AND D.userID = '$userID')
				WHERE B.ID = '$chapterCode' order by chpaterCode ASC, delegateCode ASC";

			$res = mysqli_query($this->con, $query);

			while($row = mysqli_fetch_array($res))
			{
				$status = 1;
				if(empty($row[5])){
					$status = 0;//델리게이트 엔서가 있나 먼저 체크 
				}else{
					$getDelegateChildren = "SELECT D.ID AS detailCode, E.answer AS detailAnswer
						FROM TEMPLATECONTENT AS D
						LEFT JOIN USERANSWER AS E ON (E.Q_ID = D.ID AND E.userID = '$userID')
						WHERE D.P_ID = '$row[2]'  order by detailCode ASC ";


					$exe = mysqli_query($this->con, $getDelegateChildren);

					while($answers = mysqli_fetch_array($exe))
					{
						//echo $answers[1]. " ";
						//echo $answers[0]."A" .$answers[1]."A".$answers[2]."A".$answers[3]."\n";
						if(!empty($answers[0]) && empty($answers[1])){//detail answer 가 비어있는 경우 
							$status = 0;
							//echo "status0 : ".$answers[1]. " ";
							break;
						}
					}
				}

				array_push($response, array('delegateCode' => $row[2], 'delegateName' => $row[3],'delegateHint' => $row[4],'delegateAnswer' => $row[5], 'status' => $status ));
			}

			echo json_encode($response,JSON_UNESCAPED_UNICODE);
		}

		public function getDetail($userID, $delegateCode)
		{
			$response = array();
			$query = "SELECT D.ID AS detailCode, D.name AS detailName , D.hint AS detailHint, E.answer AS detailAnswer
						FROM TEMPLATECONTENT AS D
						LEFT JOIN USERANSWER AS E ON (E.Q_ID = D.ID AND E.userID = '$userID')
						WHERE D.P_ID = '$delegateCode'  order by detailCode ASC ";

			$res = mysqli_query($this->con, $query);
			$status = 0;
			while($row = mysqli_fetch_array($res))
			{
				if(!empty($row[3])){
					$status = 1;
				}else{
					$status = 0;
				}
				array_push($response, array('detailCode' => $row[0], 'detailName' => $row[1],'detailHint' => $row[2], 'detailAnswer' => $row[3],'status' => $status));
			}

			echo json_encode($response,JSON_UNESCAPED_UNICODE);
		}

		public function makeOverView($userID, $templateCode)
		{
			$query = "SELECT A.ID AS templateCode, A.name AS templateName, B.ID AS chapterCode, B.name AS chapterName,C.ID AS delegateCode, C.name AS delegateName, C.hint AS delegateHint , F.answer AS delegateAnswer, D.ID AS detailCode, D.name AS detailName , D.hint AS detailHint, E.answer AS detailAnswer
				FROM TEMPLATECONTENT AS A
				LEFT JOIN TEMPLATECONTENT AS B ON B.P_ID = A.ID
				LEFT JOIN TEMPLATECONTENT AS C ON C.P_ID = B.ID
				LEFT JOIN USERANSWER AS F ON (F.Q_ID = C.ID AND F.userID = '$userID')
				LEFT JOIN TEMPLATECONTENT AS D ON D.P_ID = C.ID
				LEFT JOIN USERANSWER AS E ON (E.Q_ID = D.ID AND E.userID = '$userID')
				WHERE A.ID = '$templateCode' order by templateCode ASC, chapterCode ASC, delegateCode ASC, detailCode ASC ";

			$getTitleQuery = "SELECT title FROM USERPURCHASES WHERE userID = '$userID' AND TemplateCode = '$templateCode' ";
			$res = mysqli_query($this->con, $getTitleQuery);
			$response = "" ;
			while($title = mysqli_fetch_array($res)){
				$response = "<h1>".$title[0]."</h1>";
			}

			$result = mysqli_query($this->con, $query);
			$count = 0;
			$pastChapterCode;
			$pastDelegateCode;
			$pastDetailCode;
			$chapterIndex = -1;
			$delegateIndex = -1;
			$delegateArray = array();
			
			while($row = mysqli_fetch_assoc($result)){
				//echo "dd";
				$chapterParagraph = "";
				$delegateParagraph = "";
				$detailParagraph = "";
				$chapterNum = 1;
				if(!empty($row['chapterCode']) && !empty($row['chapterName'])  )
				{	
					if($pastChapterCode != $row['chapterCode'])//중복 삽입 방지를 위해서, 이미 삽입된 챕터가 아닐 경우에 
					{
						$pastChapterCode = $row['chapterCode'];

						$chapterParagraph = "<h2>". $chapterNum. ". ".$row['chapterName']."</h2>";
						$response = $response.$chapterParagraph;
						$chapterIndex++;//인덱스 증가
						$delegateIndex = -1;
						//echo "#####CHAPINDEX".$chapterIndex."#######";
						//echo json_encode($response['templateChildren'][$chapterIndex],JSON_UNESCAPED_UNICODE);
					}
					
					// if($delegateItem){
					// 	array_push($response['templateChildren'][$chapterIndex]['chapterChildren'], $delegateArray);
						
					// }
				}
				if(!empty($row['delegateCode']) && !empty($row['delegateName']) ){
					//echo $pastQuestionCode. " VS ". $row['questionCode'];
					if($pastDelegateCode != $row['delegateCode'])//중복 삽입 방지를 위해서
					{
						//$delegateItem = true;
						$pastDelegateCode = $row['delegateCode'];
						$delegateParagraph = "<h3> *Q : ".$row['delegateName']."</h3>"."<b> *A ".$row['delegateAnswer']."</b><br><br>";
						$response = $response.$delegateParagraph;
						$delegateIndex++;
						
					}
					// if(isset($detailArray)){
					// 	echo "=============";
					// 	echo json_encode(array('result' => $detailArray),JSON_UNESCAPED_UNICODE);
					// 	array_push($delegateArray[$delegateIndex]['delegateChildren'], $detailArray);
						
					// }
				}
				//$detailArray = null;

				if(!empty($row['detailCode']) && !empty($row['detailName']) ){
					//echo $pastQuestionCode. " VS ". $row['questionCode'];
					if($pastDetailCode != $row['detailCode'])//중복 삽입 방지를 위해서
					{
						$detailParagraph = "<b> Q : ".$row['detailName']."</b><br>\n A : ".$row['detailAnswer']."<br>\n";
						// $detailArray =  array("detailCode" =>$row['detailCode'] , "detailName" => $row['detailName'], "hint" => $row['detailHint'], "answer" => $row['answer'] );
						$response = $response.$detailParagraph;
						$pastDetailCode = $row['detailCode'];

					}
				}


				//$delegateItem = false;//&& isset($row['hint']) && isset($row['answer'])
				

				
			}
			//echo "+++++++++++++++++++++++++++++++++";
			//$result = array("result" => $response);
			//echo json_encode($result,JSON_UNESCAPED_UNICODE);
			echo $response;
		}

		public function validUser($userID)
		{
			
			$exist = "SELECT * FROM USER WHERE userID = '$userID' ";
			$res = mysqli_query($this->con, $exist);
			if(mysqli_num_rows($res) > 0)
			{
				return "success";
				
			}else{
				return "none";
			}

			
			//echo "success".$verificationCode.$sent. "dd";
		}

		public function resetPW($userID, $userPassword)
		{
			$query = "UPDATE USER SET userPassword = '$userPassword' WHERE userID = '$userID' ";
			$res = mysqli_query($this->con, $query);
			if(mysqli_affected_rows($this->con) > 0)
			{
				echo "success";
			}else{
				echo "fail";
			}
		}

		public function skipDelegateAndDetail($userID, $delegateCode)
		{	
			$response = "success";
			$query = "SELECT D.ID AS detailCode
						FROM TEMPLATECONTENT AS D
						WHERE D.P_ID = '$delegateCode'  order by detailCode ASC ";

			$res = mysqli_query($this->con, $query);
			if($this->setUserAnswer($userID, $delegateCode , "skipped", "setUserAnswer", false) != "fail")
			{
				while($row = mysqli_fetch_array($res))
				{
					$response = $this->setUserAnswer($userID, $row[0] , "skipped", "setUserAnswer", false);
					if($response == "fail")
					{
						break;
					}else{
						$response = "success";
					}
				}
			}else{
				$response = "fail";
			}

			echo $response;

		}

		public function downloadDocx($userID, $templateCode){
			$result_s3 = $this->s3_connect();
			if($result_s3 != "fail"){
				$query = "SELECT draftPath FROM USERPURCHASES WHERE userID = '$userID' AND TemplateCode = '$templateCode' ";
				$res = mysqli_query($this->con,$query);
				if($res)
				{
					$key;
					while($row = mysqli_fetch_array($res)){
						$key = $row[0];
					}
					if(!empty($key))
					{
						$keyPath = 'document/'.$key;
						$this->downloadFileFromS3($keyPath, $key);
					}else{
						echo "noPurchase";
						return;
					}
				}else{
					echo "fail";
				}
			}else{
				echo "fail";
			}

		}

		public function makeDocx($userID, $templateCode)
		{
			$userName;
			$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/html/document/testTemplate.docx');

			date_default_timezone_set("Asia/Seoul");
			$publishDate = date('Y-m-d', time());
			$templateProcessor->setValue('madeDate', $publishDate);

		
			$getBasicInfo = "SELECT A.title AS title , B.userName AS author
								FROM USERPURCHASES AS A 
								LEFT JOIN USER AS B ON A.userID = B.userID
								WHERE A.userID = '$userID' AND A.TemplateCode = '$templateCode' ";


			$getBasicInfoRes = mysqli_query($this->con, $getBasicInfo);

			if($getBasicInfoRes && mysqli_num_rows($getBasicInfoRes) > 0)
			{
				while($row = mysqli_fetch_array($getBasicInfoRes)){
					$templateProcessor->setValue('title', $row[0]);
					$templateProcessor->setValue('userID', $row[1]);
					$userName = $row[1];
				}
			}else{
				echo "fail";
				return;
			}


			$query = "SELECT C.ID AS delegateCode, C.name AS delegateName ,F.answer AS delegateAnswer
				FROM TEMPLATECONTENT AS A
				LEFT JOIN TEMPLATECONTENT AS B ON B.P_ID = A.ID
				LEFT JOIN TEMPLATECONTENT AS C ON C.P_ID = B.ID
				LEFT JOIN USERANSWER AS F ON (F.Q_ID = C.ID AND F.userID = '$userID')
				WHERE A.ID = '$templateCode' order by  delegateCode ASC ";

			$res = mysqli_query($this->con, $query);
			$replacement = array();
			while($row = mysqli_fetch_array($res)){

				if(!empty($row[2]) && $row[2] != "skipped" )
				{
					array_push($replacement, array("question" => $row[1], "answer" => $row[2]) );//여기에 스킵만 제외하는 조건문 걸면 끄읕!
				}


				$getDetailQuery = "SELECT A.ID AS detailCode, A.name AS detailName, B.answer AS detailAnswer
									FROM TEMPLATECONTENT AS A LEFT JOIN USERANSWER AS B ON (B.Q_ID = A.ID AND B.userID = '$userID')
									WHERE A.P_ID = '$row[0]' ";
				
				$detailRes = mysqli_query($this->con, $getDetailQuery);
				
				if($detailRes)
				{
					while($detail = mysqli_fetch_array($detailRes)) {
						// echo "inside";
						// echo $detail[0]. " : ". $detail[1];
						if(!empty($detail[2]) && $detail[2] != "skipped")//여기에 스킵만 제외하는 조건문 걸면 끄읕!
						{
							// echo "block".$detail[0];
							array_push($replacement, array("question" => $detail[1], "answer" => $detail[2]) );
							
							//$templateProcessor->setValue($detail[0], $detail[1]);
							//$templateProcessor->deleteBlock("block".$row[0]);
							
						}
					}
				}


			}

			$templateProcessor->cloneBlock("content", 0, true, false,$replacement );

			$newPath = $userName."_".$templateCode.".docx";

			$updateNewPath = "UPDATE USERPURCHASES SET draftPath = '$newPath', publishDate = '$publishDate' WHERE userID = '$userID' AND TemplateCode = '$templateCode'";
			$updateRes = mysqli_query($this->con, $updateNewPath);

			// if(mysqli_affected_rows($this->con) > 0)
			// {
			// 	echo "success";
			// }else{
			// 	echo "fail";
			// }
			$base_URL = "/var/www/html/document/";
			//1. save it to ec2 first
			$templateProcessor->saveAs($base_URL.$newPath);
			//2.send it to S3
			$result_s3 = $this->s3_connect();


			if($result_s3 != "fail")
			{
				$file_url = $base_URL.$newPath;
				$keyPath = 'document/'.$newPath;
				$result_s3 = $this->uploadFileToS3($file_url, $keyPath);
				
				if(file_exists($file_url))
				{
					unlink($file_url);
				}
				
			}else{
				echo $result_s3;
				return;
			}
			echo $result_s3;
			

		}



		public function s3_connect(){
			include 's3key.php';

			try{
				$this->s3 = new Aws\S3\S3Client([
					'region'  => 'ap-northeast-2',
					'version' => 'latest',
					'credentials' => [
						'key'    => $key,
						'secret' => $secret,
					]
				]);
			}catch (S3Exception $e) {
			    echo $e->getMessage() . PHP_EOL;
			    return "fail";
			};

			$this->bucketName = "selfbook-bucket-1";
			return "success";


		}

		public function deleteFileFromS3($key){

			try{
				$result = $this->s3->deleteObject(array(
				    'Bucket' => $this->bucketName,
				    'Key'    => $key
				));  

			}catch (S3Exception $e) {
			    echo $e->getMessage() . PHP_EOL;
			};
			exit;
			     
		}

		public function uploadFileToS3($ec2Path, $key){

			$file_data = file_get_contents($ec2Path);

			try {
    				// Upload data.
			    $result = $this->s3->putObject([
			        'Bucket' => $this->bucketName,
			        'Key'    => $key,
			        'Body'   => $file_data,
			        // 'ACL'    => 'public-read'
			    ]);

			    // Print the URL to the object.
			    $res_URL = $result['ObjectURL'];
			    if(!empty($res_URL))
			    {
			    	return "success";
			    }else{
			    	return "fail";
			    }
			    
			} catch (S3Exception $e) {
			    echo $e->getMessage() . PHP_EOL;
			    return "fail";
			}
		}

		public function downloadFileFromS3($key, $fileName){

			try {
			    // Get the object.
			    $result = $this->s3->getObject([
			        'Bucket' => $this->bucketName,
			        'Key'    => $key
			    ]);

			    // Display the object in the browser.
			    header("Content-Type: {$result['ContentType']}");
			    header('Content-Disposition: attachment; filename=' . $fileName);
			    echo $result['Body'];
			    exit;
			} catch (S3Exception $e) {
			    echo $e->getMessage() . PHP_EOL;
			}
		}

		public function getImage(){
			$this->s3_connect();
			$this->downloadFileFromS3('images/test_movie_1.png','test_movie_1.png');
		}
	}

?>