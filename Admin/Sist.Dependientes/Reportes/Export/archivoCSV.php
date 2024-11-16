<?

$file_example = "../reportes/Reporte_de_ventas.csv";
header('Content-Encoding: UTF-8');
header('Content-Description: File Transfer');
header('Content-Type: text/csv; charset=UTF-8');
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

