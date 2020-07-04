<?php

require_once './vendor/autoload.php';
error_reporting(E_ALL);

ini_set("display_errors", 1);

$file_name = 'authTest(202007041443)';   
// $temp_file_location = $_FILES['image']['tmp_name']; 

include 's3key.php';

$s3 = new Aws\S3\S3Client([
	'region'  => 'ap-northeast-2',
	'version' => 'latest',
	'credentials' => [
		'key'    => $key,
		'secret' => $secret,
	]
]);		

//$file_url = "http://13.125.206.125/document/authTest(202007041443).docx";
//$file_data = file_get_contents($file_url);

// try{
// 	$result = $s3->deleteObject(array(
// 	    'Bucket' => "selfbook-bucket-1",
// 	    'Key'    => "document/authTest_202007041443.docx"
// 	));  
// }catch (S3Exception $e) {
//     echo $e->getMessage() . PHP_EOL;
// };

// try {
//     // Upload data.
//     $result = $s3->putObject([
//         'Bucket' => 'selfbook-bucket-1',
//         'Key'    => 'document/authTest_202007041443.docx',
//         'Body'   => $file_data,
//         // 'ACL'    => 'public-read'
//     ]);

//     // Print the URL to the object.
//     echo $result['ObjectURL'] . PHP_EOL;
// } catch (S3Exception $e) {
//     echo $e->getMessage() . PHP_EOL;
// }

// try {
//     // Get the object.
//     $result = $s3->getObject([
//         'Bucket' => 'selfbook-bucket-1',
//         'Key'    => 'document/authTest_202007041443.docx'
//     ]);

//     // Display the object in the browser.
//     header("Content-Type: {$result['ContentType']}");
//     header('Content-Disposition: attachment; filename=' . 'authTest_202007041443.docx');
//     echo $result['Body'];
// } catch (S3Exception $e) {
//     echo $e->getMessage() . PHP_EOL;
// }

var_dump($result);



?>