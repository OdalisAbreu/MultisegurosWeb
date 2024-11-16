<?
session_start();
ini_set('display_errors', 1);
$equip = $_POST['privilegio'];

include("../../incluidos/conexion_inc.php");
Conectarse();



?>



<!-- FORMULARIO NUEVO -->

<div id="sub_arch">
	<h2>Cargar e importar archivo excel a MySQL</h2>
	<form name="importa" method="post" action="" enctype="multipart/form-data">
		<div>
			<div>
				<input type="file" class="filestyle" data-buttonText="Seleccione archivo" name="excel">
			</div>
		</div>
		<div>
			<input class="btn btn-default btn-file" type='submit' name='enviar' value="Importar" />
		</div>
		<input type="hidden" value="upload" name="action" />
		<input type="hidden" value="usuarios" name="mod">
		<input type="hidden" value="masiva" name="acc">
	</form>

	<!-- PROCESO DE CARGA Y PROCESAMIENTO DEL EXCEL-->
	<?php
	print_r($_POST);
	extract($_POST);

	if (isset($_POST['action'])) {
		$action = $_POST['action'];
	}

	if (isset($action) == "upload") {
		//cargamos el fichero
		$archivo = $_FILES['excel']['name'];
		$tipo = $_FILES['excel']['type'];
		$destino = "cop_" . $archivo; //Le agregamos un prefijo para identificarlo el archivo cargado
		if (copy($_FILES['excel']['tmp_name'], $destino)) echo "Archivo Cargado Con Exito";
		else echo "Error Al Cargar el Archivo";


		if (file_exists("cop_" . $archivo)) {

			$ArchivoActual = "cop_" . $archivo;


			$data = new Spreadsheet_Excel_Reader();
			$data->setOutputEncoding('CP1251');
			$data->read("cop_" . $archivo);
			//echo("<table>");
			for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {


				if ($i > 1) {

					$data->sheets[0]['numCols'];

					$numero 			= $data->sheets[0]['cells'][$i][0];
					$user_id		= $_SESSION['user_id'];
					$num_agencia 	= $data->sheets[0]['cells'][$i][1];
					$razon_social	= $data->sheets[0]['cells'][$i][2];
					$estado1		= $data->sheets[0]['cells'][$i][3];
					$telefono		= $data->sheets[0]['cells'][$i][4];
					$ejecutivo		= $data->sheets[0]['cells'][$i][5];
					$ciudad			= $data->sheets[0]['cells'][$i][6];
					$municipio		= $data->sheets[0]['cells'][$i][7];
					$provincia		= $data->sheets[0]['cells'][$i][8];
					$longitud		= $data->sheets[0]['cells'][$i][9];
					$latitud		= $data->sheets[0]['cells'][$i][10];
					$calle			= $data->sheets[0]['cells'][$i][11];
					$sector			= $data->sheets[0]['cells'][$i][12];
					$fecha			= date('Y-m-d H:i:s');
					$activo			= 'si';

					$insertar = "INSERT INTO agencia_via_dos (
	
	user_id, num_agencia , razon_social , estado1 , telefono , ejecutivo , ciudad , municipio, provincia , longitud , latitud , calle ,  sector , fecha , activo 
	
	) VALUES ('$user_id','$num_agencia','$razon_social','$estado1','$telefono','$ejecutivo','$ciudad','$municipio','$provincia','$longitud','$latitud','$calle','$sector','$fecha','$activo')";
					mysql_query($insertar);




					unlink($ArchivoActual);

					//DESGLOSE EN UNA SOLA FILA
				}
			}
			//echo("</table>");

		}
		//si por algun motivo no cargo el archivo cop_ 
		else {
			echo "Primero debes cargar el archivo con extencion .xlsx";
		}
	}






	//echo 'getHighestColumn() =  [' . $columnas . ']<br/>';
	//echo 'getHighestRow() =  [' . $filas . ']<br/>';
	if (isset($action) == "upload") {


		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('CP1251');
		$data->read($ArchivoActual);
		//echo("<table>");


		echo '<table border="1" class="table">';
		echo '<thead>';
		echo '<tr>';
		echo '<th>num_agencia</th>';
		echo '<th>razon_social</th>';
		echo '<th>estado1</th>';
		echo '<th>telefono</th>';
		echo '<th>ejecutivo</th>';
		echo '<th>ciudad</th>';
		echo '<th>municipio</th>';
		echo '<th>provincia</th>';
		echo '<th>longitud</th>';
		echo '<th>latitud</th>';
		echo '<th>calle</th>';
		echo '<th>sector</th>';
		echo '</tr> ';
		echo '</thead> ';
		echo '<tbody> ';


		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {


			if ($i > 1) {

				$data->sheets[0]['numCols'];

				$numero 			= $data->sheets[0]['cells'][$i][0];
				$user_id		= $_SESSION['user_id'];
				$num_agencia 	= $data->sheets[0]['cells'][$i][1];
				$razon_social	= $data->sheets[0]['cells'][$i][2];
				$estado1		= $data->sheets[0]['cells'][$i][3];
				$telefono		= $data->sheets[0]['cells'][$i][4];
				$ejecutivo		= $data->sheets[0]['cells'][$i][5];
				$ciudad			= $data->sheets[0]['cells'][$i][6];
				$municipio		= $data->sheets[0]['cells'][$i][7];
				$provincia		= $data->sheets[0]['cells'][$i][8];
				$longitud		= $data->sheets[0]['cells'][$i][9];
				$latitud		= $data->sheets[0]['cells'][$i][10];
				$calle			= $data->sheets[0]['cells'][$i][11];
				$sector			= $data->sheets[0]['cells'][$i][12];
				$fecha			= date('Y-m-d H:i:s');
				$activo			= si;

				if (!$num_agencia) {
					$num_agencia = 0;
				}
				if (!$razon_social) {
					$razon_social = 0;
				}
				if (!$estado1) {
					$estado1 = 0;
				}
				if (!$telefono) {
					$telefono = 0;
				}
				if (!$ejecutivo) {
					$ejecutivo = 0;
				}
				if (!$ciudad) {
					$ciudad = 0;
				}
				if (!$municipio) {
					$municipio = 0;
				}
				if (!$provincia) {
					$provincia = 0;
				}
				if (!$longitud) {
					$longitud = 0;
				}
				if (!$latitud) {
					$latitud = 0;
				}
				if (!$calle) {
					$calle = 0;
				}
				if (!$sector) {
					$sector = 0;
				}

				echo '<tr>';
				echo '<td>' . $num_agencia . '</td>';
				echo '<td>' . $razon_social . '</td>';
				echo '<td>' . $estado1 . '</td>';
				echo '<td>' . $telefono . '</td>';
				echo '<td>' . $ejecutivo . '</td>';
				echo '<td>' . $ciudad . '</td>';
				echo '<td>' . $municipio . '</td>';
				echo '<td>' . $provincia . '</td>';
				echo '<td>' . $longitud . '</td>';
				echo '<td>' . $latitud . '</td>';
				echo '<td>' . $calle . '</td>';
				echo '<td>' . $sector . '</td>';
				echo '</tr>';

				//DESGLOSE EN UNA SOLA FILA
			}
		}

		echo '</tbody>';
		echo '</table>';
	}
	?>
</div>