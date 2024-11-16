<?	
//EJEMPLO 8
$filename = $_GET['nombre'];
$file_example = $_SERVER['DOCUMENT_ROOT']."/Seg_V2/Admin/SubirArchivos/".$filename."";
	
header('Content-Description: File Transfer');
header('Content-Type: text/pdf');
header('Content-Disposition: attachment; filename='.basename($file_example));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file_example));
ob_clean();
flush();
readfile($file_example);

?>