<?php
session_start();
ini_set('display_errors', 1);
include("../../incluidos/conexion_inc.php");
include("../../incluidos/nombres.func.php");
Conectarse();

require_once 'excelenphp-master/Excel/reader.php';

//extract($_POST);

//print_r($_POST);
//exit;
if (isset($_POST['action'])) {
	$action = $_POST['action'];
}

$action = "upload";

if (isset($action) == "upload") {

	if ($_SESSION['user_id'] == '38') {
		$_SESSION['user_id'] = $_SESSION['dist_id'];
	}

	//cargamos el fichero
	$archivo = $_FILES['excel']['name'];
	$tipo = $_FILES['excel']['type'];

	$archivo = str_replace(" ", "_", $archivo);
	$dates 	 = date("H:i:s");

	//Le agregamos un prefijo para identificarlo el archivo cargado
	$destino = "cop_" . $dates . $archivo;

	if (copy($_FILES['excel']['tmp_name'], $destino)) {
		//echo "10/".$dates.$archivo."/"; 
		echo "100/100";

		//DESGLOSE DEL REGISTRO
		$ins1 = "INSERT INTO auditoria (user_id, descrip , peticion , codigo , fecha, ip) VALUES 
		('" . $_SESSION['user_id'] . "','" . $destino . "','subida_ok_100','100','" . date("Y-m-d H:i:s") . "','" . $_SERVER['REMOTE_ADDR'] . "')";
		mysql_query($ins1);
	} else {
		echo "101/101";
		//DESGLOSE DEL REGISTRO
		$ins2 = "INSERT INTO auditoria (user_id, descrip , peticion , codigo , fecha, ip) VALUES 
		('" . $_SESSION['user_id'] . "','" . $destino . "','subida_error_101','101','" . date("Y-m-d H:i:s") . "','" . $_SERVER['REMOTE_ADDR'] . "')";
		mysql_query($ins2);
	}



	if (file_exists("cop_" . $dates . $archivo)) {

		$ArchivoActual = "cop_" . $dates . $archivo;


		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('CP1251');
		$data->read("cop_" . $dates . $archivo);
		//echo("<table>");
		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {


			if ($i > 1) {

				$data->sheets[0]['numCols'];

				$numero = $data->sheets[0]['cells'][$i][0];
				$user_id = $_SESSION['user_id'];
				$num_agencia = $data->sheets[0]['cells'][$i][1];
				$razon_social = $data->sheets[0]['cells'][$i][2];
				$estado1 = $data->sheets[0]['cells'][$i][3];
				$telefono = $data->sheets[0]['cells'][$i][4];
				$ejecutivo = $data->sheets[0]['cells'][$i][5];
				$ciudad	= $data->sheets[0]['cells'][$i][6];
				$municipio = $data->sheets[0]['cells'][$i][7];
				$provincia = $data->sheets[0]['cells'][$i][8];
				$longitud = $data->sheets[0]['cells'][$i][9];
				$latitud = $data->sheets[0]['cells'][$i][10];
				$calle = $data->sheets[0]['cells'][$i][11];
				$sector	= $data->sheets[0]['cells'][$i][12];
				$comAplicada = $data->sheets[0]['cells'][$i][13];
				$comFaltante = $data->sheets[0]['cells'][$i][14];
				$descuentoISR = $data->sheets[0]['cells'][$i][15];
				$fecha = date('Y-m-d H:i:s');
				$activo	= "si";

				$ValidarAgencia = ValidarAgencia($num_agencia);


				if ($ValidarAgencia == '1') {
					// ACTUALIZAR


					mysql_query("DELETE FROM `agencia_via` WHERE num_agencia =" . $num_agencia . " LIMIT 1");

					$insertar2 = "INSERT INTO agencia_via (user_id, num_agencia , razon_social , estado1 , telefono , ejecutivo , ciudad , municipio, provincia, longitud, latitud, calle,  sector, fecha, activo, comAplicada, comFaltante, descuento_ISR, id_supervisor) 
					VALUES ('$user_id','$num_agencia','$razon_social','$estado1','$telefono','$ejecutivo','$ciudad','$municipio','$provincia','$longitud','$latitud','$calle','$sector','$fecha','$activo','$comAplicada','$comFaltante','$descuentoISR','0')";
					mysql_query($insertar2);

					/*$actualizar="UPDATE agencia_via SET 
		razon_social	=	".$razon_social.",
		estado1			=	".$estado1.",
		telefono		=	".$telefono.",
		ejecutivo		=	".$ejecutivo.",
		ciudad			=	".$ciudad.",
		municipio		=	".$municipio.",
		provincia		=	".$provincia.",
		longitud		=	".$longitud.",
		latitud			=	".$latitud.",
		calle			=	".$calle.",
		sector			=	".$sector.",
		fecha_update	=	".date("Y-m-d H:i:s")."
		 WHERE num_agencia =".$num_agencia." AND user_id = ".$_SESSION['user_id']." "; 
		 
		
		mysql_query($actualizar);*/

					//DESGLOSE DEL REGISTRO
					$actualizar2 = "INSERT INTO auditoria_subida_agencia (user_id, archivo, num_agencia , razon_social , estado1 , telefono , ejecutivo , ciudad , municipio, provincia, longitud, latitud, calle,  sector, fecha, tipo)
					 VALUES ('$user_id','" . $destino . "','$num_agencia','$razon_social','$estado1','$telefono','$ejecutivo','$ciudad','$municipio','$provincia','$longitud','$latitud','$calle','$sector','$fecha','actualizo')";
					mysql_query($actualizar2);
				} else {
					// INSERTAR	

					if ($num_agencia > 0) {
						$insertar = "INSERT INTO agencia_via (user_id, num_agencia , razon_social , estado1 , telefono , ejecutivo , ciudad , municipio, provincia, longitud, latitud, calle,  sector, fecha, activo, comAplicada, comFaltante, descuento_ISR, id_supervisor)
						 VALUES ('$user_id','$num_agencia','$razon_social','$estado1','$telefono','$ejecutivo','$ciudad','$municipio','$provincia','$longitud','$latitud','$calle','$sector','$fecha','$activo','$comAplicada','$comFaltante','$descuentoISR','0')";
						mysql_query($insertar);

						//DESGLOSE DEL REGISTRO
						$insertar1 = "INSERT INTO auditoria_subida_agencia (
		user_id, archivo, num_agencia , razon_social , estado1 , telefono , ejecutivo , ciudad , municipio, provincia, longitud, latitud, calle,  sector, fecha, tipo 
		) VALUES ('$user_id','" . $destino . "','$num_agencia','$razon_social','$estado1','$telefono','$ejecutivo','$ciudad','$municipio','$provincia','$longitud','$latitud','$calle','$sector','$fecha','registro')";
						mysql_query($insertar1);
					}
				}

				//unlink($ArchivoActual);	
			}
		}



		//echo("</table>");

	}
	//si por algun motivo no cargo el archivo cop_ 
	else {
		$ins3 = "INSERT INTO auditoria (
		user_id, descrip , peticion , codigo , fecha, ip
		) VALUES 
		('" . $_SESSION['user_id'] . "','Error Al Cargar el Archivo, Primero debes cargar el archivo con extencion .xls','subida_102','102','" . date("Y-m-d H:i:s") . "','" . $_SERVER['REMOTE_ADDR'] . "')";
		mysql_query($ins3);
		exit("102/102");
	}
}
