<?php

require_once './vendor/autoload.php';
error_reporting(E_ALL);

ini_set("display_errors", 1);

// $phpword = new \PhpOffice\PhpWord\PhpWord();
// $section = $phpword->addSection();

// $section->addText("Hello World!");

// $phpword->save('/var/www/html/hello.docx', 'Word2007');

$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/html/document/testTemplate.docx');
$templateProcessor->setValue('title', '제목 뭐로하징 이게 교체가 되는건가?! 진짜로 아니 되는거야 마는거야 ㅆ발');
$templateProcessor->setValue('madeDate', '2020-06-20ㅇㅁㄴㅇㅁㄴdasddasdas');
$templateProcessor->setValue('userID', '이용승~ㅇㄴㅁㅇㅁㄴ!dsadsadsadas');
if(file_exists('/var/www/html/document/testTemplateVersion.docx'))
{
	unlink('/var/www/html/document/testTemplateVersion.docx');
	echo "success?";
}
$templateProcessor->saveAs('/var/www/html/document/testTemplateVersion.docx');
//$templateProcessor->saveAs($filename);

//Creating the new document...
//$phpWord = new vendor\PhpOffice\PhpWord\PhpWord();

/* Note: any element you append to a document must reside inside of a Section. */

// Adding an empty Section to the document...
// $section = $phpWord->addSection();
// // Adding Text element to the Section having font styled by default...
// $section->addText(
//     '"Learn from yesterday, live for today, hope for tomorrow. '
//         . 'The important thing is not to stop questioning." '
//         . '(Albert Einstein)'
// );

// /*
//  * Note: it's possible to customize font style of the Text element you add in three ways:
//  * - inline;
//  * - using named font style (new font style object will be implicitly created);
//  * - using explicitly created font style object.
//  */

// // Adding Text element with font customized inline...
// $section->addText(
//     '"Great achievement is usually born of great sacrifice, '
//         . 'and is never the result of selfishness." '
//         . '(Napoleon Hill)',
//     array('name' => 'Tahoma', 'size' => 10)
// );

// // Adding Text element with font customized using named font style...
// $fontStyleName = 'oneUserDefinedStyle';
// $phpWord->addFontStyle(
//     $fontStyleName,
//     array('name' => 'Tahoma', 'size' => 10, 'color' => '1B2232', 'bold' => true)
// );
// $section->addText(
//     '"The greatest accomplishment is not in never falling, '
//         . 'but in rising again after you fall." '
//         . '(Vince Lombardi)',
//     $fontStyleName
// );

// // Adding Text element with font customized using explicitly created font style object...
// $fontStyle = new \PhpOffice\PhpWord\Style\Font();
// $fontStyle->setBold(true);
// $fontStyle->setName('Tahoma');
// $fontStyle->setSize(13);
// $myTextElement = $section->addText('"Believe you can and you\'re halfway there." (Theodor Roosevelt)');
// $myTextElement->setFontStyle($fontStyle);

// // Saving the document as OOXML file...
// $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
// $objWriter->save('helloWorld.docx');

// // Saving the document as ODF file...
// $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
// $objWriter->save('helloWorld.odt');

// // Saving the document as HTML file...
// $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
// $objWriter->save('helloWorld.html');

/* Note: we skip RTF, because it's not XML-based and requires a different example. */
/* Note: we skip PDF, because "HTML-to-PDF" approach is used to create PDF documents. */
?>