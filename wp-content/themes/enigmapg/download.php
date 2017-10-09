<?php
#-------------------Change request values to php variables---------------#
foreach ($_REQUEST as $key => $value) {
   if (!is_array ($value)) {
      $$key = trim($value);
   }
}//next
#------------------------------------------------------------------------#
$file_url = $_SERVER['DOCUMENT_ROOT'].parse_url($file_url, PHP_URL_PATH);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.basename($file_url));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($file_url));
ob_clean();
flush();
readfile($file_url);
exit;

/*
   header('Content-Type: application/pdf');
   header("Content-Transfer-Encoding: Binary"); 
   header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
   readfile($file_url); 
*/
?>