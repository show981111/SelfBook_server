<?php

require_once './vendor/autoload.php';
require_once('selfBook.php');



parse_str(file_get_contents('php://input'), $_PUT);
	//var_dump($_PUT);

	// echo file_get_contents('php://input');


$userID = $_PUT['userID'];
$templateCode = $_PUT['templateCode'];



if(isset($userID) && isset($templateCode))
{
	$test = new selfBook();
	$test->makeDocx($userID, $templateCode);
}else{
	echo "fail";
}
//$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/html/document/testTemplate.docx');
// $templateProcessor->setValue('title', '제목 뭐로하징 이게 교체가 되는건가?! 진짜로 아니 되는거야 마는거야 ㅆ발');
// $templateProcessor->setValue('madeDate', '2020-06-20ㅇㅁㄴㅇㅁㄴdasddasdas');
// $templateProcessor->setValue('userID', '이용승~ㅇㄴㅁㅇㅁㄴ!dsadsadsadas');
// if(file_exists('/var/www/html/document/testTemplateVersion.docx'))
// {
// 	unlink('/var/www/html/document/testTemplateVersion.docx');
// 	echo "success?";
// }
// $templateProcessor->saveAs('/var/www/html/document/testTemplateVersion.docx');

?>