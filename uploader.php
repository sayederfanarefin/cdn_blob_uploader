<?php


$blob_name = $_POST["file_name"];

$source = $_POST["source"];

$file_name = $_FILES["file_content"]["name"];
$file_tmp = $_FILES['file_content']['tmp_name'];

$target_dir = "uploads/".$file_name;

move_uploaded_file($file_tmp, $target_dir);


require_once 'vendor/autoload.php';

use WindowsAzure\Common\ServicesBuilder; //vendor\microsoft\windowsazure\src\Common\ServicesBuilder ;  // WindowsAzure\Common\ServicesBuilder;
use MicrosoftAzure\Storage\Common\ServiceException; //vendor\microsoft\azurestorage\src\Common\ServiceException; //MicrosoftAzure\Storage\Common\ServiceException;

// Create blob REST proxy.

$connectionString = "DefaultEndpointsProtocol=http;AccountName=neonsoftscdnbd;AccountKey=2MZExG2E1yDT2MY5Bzcs+RPDxY7ByuG1If9hC7037g2IjTZZ27vzNDJjdb0mTu1wZvYo3sgTh9ElxNCbXJi2DA==";

$blobRestProxy = ServicesBuilder::getInstance()->createBlobService($connectionString);


$content = fopen($target_dir, "r");

//$blob_name = "txt/b.txt";

try {
    //Upload blob
    $blobRestProxy->createBlockBlob("contents", $blob_name, $content);
}
catch(ServiceException $e){
    // Handle exception based on error codes and messages.
    // Error codes and messages are here:
    // http://msdn.microsoft.com/library/azure/dd179439.aspx
    $code = $e->getCode();
    $error_message = $e->getMessage();
    echo $code.": ".$error_message."<br />";
}

unlink($target_dir);


if($source == "1"){
    header('Location: index.html');
}else{
    header("HTTP/1.1 200 OK");
}
exit;

?>
