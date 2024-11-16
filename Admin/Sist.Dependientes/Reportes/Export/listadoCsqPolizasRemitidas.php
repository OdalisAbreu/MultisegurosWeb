<?
$fecha1  =  $_GET['fecha1'];
$fecha2  =  $_GET['fecha2'];

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Reporte de polizas remitidas desde - " . $fecha1 . " - hasta - " . $fecha2 . ".xls");


session_start();
ini_set('display_errors', 1);

include("../../../../incluidos/conexion_inc.php");
include("../../../../incluidos/nombres.func.php");
Conectarse();
include("../../../../incluidos/fechas.func.php");

// --------------------------------------------	
if ($_GET['fecha1']) {
	$fecha1 = $_GET['fecha1'];
} else {
	$fecha1 = fecha_despues('' . date('d/m/Y') . '', -0);
}
// --------------------------------------------
if ($_GET['fecha2']) {
	$fecha2 = $_GET['fecha2'];
} else {
	$fecha2 = fecha_despues('' . date('d/m/Y') . '', 0);
}
function Agencias($id)
{

	$red  = mysql_query("SELECT * FROM agencia_via WHERE num_agencia='" . $id . "' LIMIT 1");
	$rred = mysql_fetch_array($red);

	if ($rred['num_agencia']) {
		return $rred['razon_social'] . "/" . $rred['ejecutivo'];
	} else {
		return " - CSQ - ";
	}
}

if ($_GET['consul'] == '1') {

	$rutas = [];
	$idAgencias = [];
	$rutasCompletas = [];
	$agenciasValidas = [];
	$cont = 0;
	$contAgencias = 0;
	$clientes = [];



	$fd1	= explode('/', $fecha1);
	$fh1	= explode('/', $fecha2);
	$fDesde 	= $fd1[2] . '-' . $fd1[1] . '-' . $fd1[0];
	$fHasta 	= $fh1[2] . '-' . $fh1[1] . '-' . $fh1[0];
	$wFecha 	= "AND trans.fecha >= '$fDesde 00:00:00' AND trans.fecha < '$fHasta 23:59:59'";

	if ($_GET['ruta_id'] != '1todos') {
		$ejecutivo = " ejecutivo LIKE '%" . $_GET['ruta_id'] . "%' ";
	} else {
		//$ejecutivo = "id !='' ";
	}

	$idpers = "AND user_id ='" . $_SESSION['dist_id'] . "' ";

	$ED = mysql_query("SELECT * from agencia_via WHERE $ejecutivo $idpers ");
	//echo "<br>SELECT * from agencia_via WHERE $ejecutivo $idpers  <br>";
	while ($RED = mysql_fetch_array($ED)) {
		$agenci .= "trans.x_id LIKE '%" . $RED['num_agencia'] . "-%' OR ";
	}

	$resulAgen = rtrim($agenci, 'OR ');


	if ($resulAgen) {
		$resulAgen = "AND (" . $resulAgen . ") ";
	}
	// - ------------------------- Genera y limpia las rutas --------------------------------------------------------------- 
	$queryVefore = mysql_query("SELECT DISTINCT SUBSTRING(trans.x_id, 1, 6) AS id  FROM seguro_transacciones AS trans 
	LEFT JOIN seguro_transacciones_reversos AS rever ON rever.id_trans = trans.id 
	WHERE rever.id_trans IS NULL AND trans.monto !='' AND trans.user_id ='" . $_SESSION['dist_id'] . "'  $wFecha $resulAgen order by id DESC");
	while ($row = mysql_fetch_array($queryVefore)) {
		$idAgencias[$contAgencias++] = $row['id'];
		$consultAgencia = mysql_query("SELECT * FROM agencia_via where num_agencia = " . $row['id']);
		$agenciaUnica = mysql_fetch_row($consultAgencia);
		$rutas[$cont++] = $agenciaUnica[7];
		if ($agenciaUnica[7] != '') {
			array_push($agenciasValidas, $agenciaUnica); // Acumula los datos de las agencias que existen
		}
	}
	$rutasUnicas = array_unique($rutas); // Acumulamos las rutas unicas 
	// Separa los que tienen registro de los que no tienen Registro
	foreach ($idAgencias as $idAgencia) {
		$valido = 0;
		foreach ($agenciasValidas as $agenciaValida) {
			if ($agenciaValida[2] == $idAgencia) {
				$clientes['Asignados'][] = $agenciaValida;
				$valido = 1;
			}
		}
		if ($valido == 0) {
			$clientes['No Asignados'][] = $idAgencia;
		}
	}
?>

	<table>
		<tr>
			<td colspan="5" align="center" style="background-color: #f7caac; border-radius: 14p">
				<h1><strong>Reporte Consumo Voxtel Entre fechas </strong></h1>
			</td>
		</tr>
		<tr>
			<td colspan="5" align="center">
				<h3>
					<strong>Fecha desde:</strong> <?= $fecha1; ?>
					<strong>Fecha Hasta:</strong> <?= $fecha2; ?>
				</h3>
			</td>
		</tr>
		<tr style="font-size:16px; font-weight:bold; background-color:#d5d5d5;">
			<td>Codigo agencia</td>
			<td style="min-width: 150px;">Cliente</td>
			<td>Poliza</td>
			<td style="width:150px">Fecha de Emisión</td>
			<td style="width:220px">Nombre</td>
			<td>Total de venta</td>
			<td>%Com.Aplicada</td>
			<td>%Com. Faltante</td>
			<td>Descuento 10% ISR</td>
			<td>Comision </td>
		</tr>
		<tr>
			<td></td>
			<td style="min-width: 240px;"></td>
			<td></td>
			<td style="width:150px"></td>
			<td style="width:220px"></td>
			<td></td>
			<td>0%</td>
			<td>0%</td>
			<td>0%</td>
			<td></td>
		</tr>
		<tr style="font-size:16px; font-weight:bold; background-color:#9bc2e6;">
			<td></td>
			<td>Cliente No Asignado</td>
			<td>No. Poliza</td>
			<td>Fecha de Emisión</td>
			<td>Nombre</td>
			<td>Total de venta</td>
			<td>%Com.Aplicada</td>
			<td>%Com. Faltante</td>
			<td>Descuento 10% ISR</td>
			<td>Comision</td>
		</tr>

		<?
		$totalVentas = 0;
		$ComAplicada = 0;
		$ComFaltante = 0;
		$DescuentoISR = 0;
		$comision = 0;

		foreach ($clientes['No Asignados'] as $cliente) {
			$query = mysql_query("SELECT trans.* FROM seguro_transacciones trans 
				LEFT JOIN seguro_transacciones_reversos rever ON rever.id_trans = trans.id
		WHERE rever.id_trans IS NULL AND trans.monto !='' AND trans.user_id ='" . $_SESSION['dist_id'] . "'  $wFecha $resulAgen AND SUBSTRING(trans.x_id, 1, 6) = $cliente order by id DESC");
			while ($row = mysql_fetch_array($query)) {

				$Agent = explode("-", $row['x_id']);
				$Agencia = explode("/", AgenciaVia($Agent[0]));
				$varia = array("Ruta ", "RUTA", " ", "-", "/");
				$ruta = str_replace($varia, "", $Agencia[1]);

				$array[$Agencia[1]][] = $row;
			}
		}
		ksort($array);
		/*echo "<pre>";
		print_r($array);
		echo  "</pre>";*/

		foreach ($array as $key => $val) {
			foreach ($val as $key2 => $row) {

				$client2 = explode("|", ClientesVerRemesa($row['id_cliente']));
				$Agent2 = explode("-", $row['x_id']);
				$Agencia2 = explode("/", Agencias($Agent2[0]));

				$consultAgencia = mysql_query("SELECT * FROM agencia_via where num_agencia = " . $Agent2[0]);
				$agenciaUnica = mysql_fetch_row($consultAgencia);

				$varia2 = array("Ruta ", "RUTA", " ", "-", "/");
				$ruta2 = str_replace($varia2, "", $Agencia2[1]);
				// echo "<pre>";
				// print_r($row);
				// echo  "</pre>";
		?>
				<tr>
					<td><?= $Agent2[0] ?> </td>
					<td><?= strtoupper($Agencia2[0]) ?></td>
					<td><?= GetPrefijo($row['id_aseg']) . "-" . str_pad($row['id_poliza'], 6, "0", STR_PAD_LEFT); ?></td>
					<td><?= FechaListPDF($row['fecha']) ?></td>
					<td><?= $client2[0] . " " . $client2[1] ?></td>
					<!--	<td><? //= strtoupper($Agencia2[1]) 
								?></td> -->
					<td><?= strtoupper($row['monto']) ?></td><? $totalVentas = $totalVentas + $row['monto']; ?>
					<td><? if ($agenciaUnica[15] == '') {
							echo 0;
						} else {
							echo $agenciaUnica[15];
						} ?></td> <? $ComAplicada = $ComAplicada +  $agenciaUnica[15]; ?>
					<td><? if ($agenciaUnica[16] == '') {
							echo 0;
						} else {
							echo $agenciaUnica[16];
						}   ?></td><? $ComFaltante  = $ComFaltante  +  $agenciaUnica[16]; ?>
					<td><? if ($agenciaUnica[17] == '') {
							echo 0;
						} else {
							echo $agenciaUnica[17];
						}   ?></td><? $DescuentoISR = $DescuentoISR +  $agenciaUnica[17]; ?>
					<td><?= $agenciaUnica[16] - $agenciaUnica[17]  ?></td> <? $comision = $comision + ($agenciaUnica[16] - $agenciaUnica[17]) ?>
				</tr>
	<?

			}
		}
	}
	?>
	<tr style="font-size:16px; background-color:#9bc2e6;">
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td>$ <?= round($totalVentas, 2) ?></td>
		<td>$ <?= round($ComAplicada, 2) ?></td>
		<td>$ <?= round($ComFaltante, 2) ?></td>
		<td>$ <?= round($DescuentoISR, 2) ?></td>
		<td></td>
	</tr>
	<tr style="font-size:18px; font-weight:bold; ">
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td>Total Aplicar</td>
		<td>$ <?= round($comision, 2) ?></td>
	</tr>
	<tr></tr>
	<tr></tr>
	<tr></tr>
	</table>


	<?
	//---------------------------------------------------------------------------------------------------------------------------------------------

	//-------------------- estrae las rutas -------------------------------
	$rutasActivas = [];
	foreach ($clientes['Asignados'] as $clientA) {
		$rutasActivas[] = $clientA[7];
	}
	$rutasActivasUnicas = array_unique($rutasActivas);
	//-----------------------------------------------------------------------

	foreach ($rutasActivasUnicas as $rutasActiva) {
	?>
		<table>
			<tr>
				<td colspan="5" align="center" style="background-color: #f7caac; border-radius: 14p">
					<h1><strong>Reporte Consumo Voxtel Entre fechas </strong></h1>
				</td>
			</tr>
			<tr>
				<td colspan="5" align="center">
					<h3>
						<strong>Fecha desde:</strong> <?= $fecha1; ?>
						<strong>Fecha Hasta:</strong> <?= $fecha2; ?>
					</h3>
				</td>
			</tr>
			<tr style="font-size:16px; font-weight:bold; background-color:#d5d5d5;">
				<td>Codigo agencia</td>
				<td style="min-width: 150px;">Cliente</td>
				<td>Poliza</td>
				<td style="width:150px">Fecha de Emisión</td>
				<td style="width:220px">Nombre</td>
				<td>Total de venta</td>
				<td>%Com.Aplicada</td>
				<td>%Com. Faltante</td>
				<td>Descuento 10% ISR</td>
				<td>Comision </td>
			</tr>
			<tr>
				<td></td>
				<td style="min-width: 240px;"></td>
				<td></td>
				<td style="width:150px"></td>
				<td style="width:220px"></td>
				<td></td>
				<td>8.11%</td>
				<td>5.89%</td>
				<td>10.00%</td>
				<td></td>
			</tr>
			<tr style="font-size:16px; font-weight:bold; background-color:#9bc2e6;">
				<td></td>
				<td><?= $rutasActiva ?></td>
				<td>No. Poliza</td>
				<td>Fecha de Emisión</td>
				<td>Nombre</td>
				<td>Total de venta</td>
				<td>%Com.Aplicada</td>
				<td>%Com. Faltante</td>
				<td>Descuento 10% ISR</td>
				<td>Comision</td>
			</tr>
			<?
			$array2 = [];
			$totalVentas = 0;
			$totalComAplicada = 0;
			$totalComFaltante = 0;
			$totalDescuentoISR = 0;
			$comision = 0;
			foreach ($clientes['Asignados'] as $clientA) {
				if ($clientA[7] == $rutasActiva) {
					//		echo $clientA[2] .'<br>';


					//----------------------------------------------------------------------------------------------------------

					$query2 = mysql_query("SELECT trans.* FROM seguro_transacciones trans 
					LEFT JOIN seguro_transacciones_reversos rever ON rever.id_trans = trans.id
					WHERE rever.id_trans IS NULL AND trans.monto !='' AND trans.user_id ='" . $_SESSION['dist_id'] . "'  $wFecha $resulAgen AND SUBSTRING(trans.x_id, 1, 6) = $clientA[2] order by id DESC");
					while ($row = mysql_fetch_array($query2)) {

						$Agent = explode("-", $row['x_id']);
						$Agencia = explode("/", AgenciaVia($Agent[0]));
						$varia = array("Ruta ", "RUTA", " ", "-", "/");
						$ruta = str_replace($varia, "", $Agencia[1]);

						$array2[] = $row;
					}
					ksort($array2);
					// echo "<pre>";
					// print_r($array2);
					// echo  "</pre>";

					foreach ($array2 as $key => $val) {

						// foreach ($val as $key => $row) {
						$client2 = explode("|", ClientesVerRemesa($val['id_cliente']));
						$Agent2 = explode("-", $val['x_id']);
						$Agencia2 = explode("/", Agencias($Agent2[0]));

						$consultAgencia = mysql_query("SELECT * FROM agencia_via where num_agencia = " . $Agent2[0]);
						$agenciaUnica = mysql_fetch_row($consultAgencia);

						$varia2 = array("Ruta ", "RUTA", " ", "-", "/");
						$ruta2 = str_replace($varia2, "", $Agencia2[1]);

			?>
						<tr>
							<td><?= $Agent2[0] ?> </td>
							<td><?= strtoupper($Agencia2[0]) ?></td>
							<td><?= GetPrefijo($val['id_aseg']) . "-" . str_pad($val['id_poliza'], 6, "0", STR_PAD_LEFT); ?></td>
							<td><?= FechaListPDF($val['fecha']) ?></td>
							<td><?= $client2[0] . " " . $client2[1] ?></td>
							<!--	<td><? //= strtoupper($Agencia2[1]) 
										?></td> -->
							<td><?= strtoupper($val['monto']) ?></td>
							<? $totalVentas = $totalVentas + $val['monto']; //Acumula para añadir al total
							?>
							<td><? if ($agenciaUnica[15] == '') {
									$ComAplicada = 0;
								} else {
									$ComAplicada = $val['monto'] *  $agenciaUnica[15] / 100;
								}
								echo round($ComAplicada, 2);
								?>
							</td>
							<? $totalComAplicada = $totalComAplicada + $ComAplicada; //Acumula para añadir al total
							?>
							<td><? if ($agenciaUnica[16] == '') {
									$ComFaltante = 0;
								} else {
									$ComFaltante = $val['monto'] * $agenciaUnica[16] / 100;
								}
								echo round($ComFaltante, 2);
								?>
							</td>
							<? $totalComFaltante  = $totalComFaltante  +  $ComFaltante; //Acumula para añadir al total
							?>
							<td><? if ($agenciaUnica[17] == '') {
									$DescuentoISR = 0;
								} else {
									$DescuentoISR = ($ComAplicada + $ComFaltante) * $agenciaUnica[17] / 100;
								}
								echo round($DescuentoISR, 2);
								?>
							</td>
							<? round($totalDescuentoISR = $totalDescuentoISR +  $DescuentoISR, 2); //Acumula para añadir al total 
							?>
							<td><? echo round($ComFaltante - $DescuentoISR, 2)  ?>
							</td> <? round($comision = $comision + ($ComFaltante - $DescuentoISR), 2) ?>
						</tr>

			<?

					}


					//---------------------------------------------------------------------------------------------------------

					//	}
				}
			}
			?>
			<tr style="font-size:16px; background-color:#9bc2e6;">
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>$ <?= round($totalVentas, 2) ?></td>
				<td>$ <?= round($totalComAplicada, 2) ?></td>
				<td>$ <?= round($totalComFaltante, 2) ?></td>
				<td>$ <?= round($totalDescuentoISR, 2) ?></td>
				<td></td>
			</tr>
			<tr style="font-size:18px; font-weight:bold; ">
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>Total Aplicar</td>
				<td>$ <?= round($comision, 2) ?></td>
			</tr>
			<tr></tr>
			<tr></tr>
			<tr></tr>
		</table><?
			}
				?>