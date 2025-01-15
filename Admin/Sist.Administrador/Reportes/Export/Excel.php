<?php ini_set('display_errors', 1);
$fecha1 = $_GET['fecha1'];
$fecha2 = $_GET['fecha2'];

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header(
	"content-disposition: attachment;filename=Reporte desde - " .
		$fecha1 .
		" - hasta - " .
		$fecha2 .
		".xls"
);

session_start();

include "../../../../incluidos/conexion_inc.php";
include "../../../../incluidos/fechas.func.php";
include "../../../../incluidos/nombres.func.php";
Conectarse();

if ($_GET['aseguradora'] != '1aseg') {
	$aseg = "AND id_aseg='" . $_GET['aseguradora'] . "' ";
	$nombre = NombreSeguroS($_GET['aseguradora']);
	$clase = "1";
	$columna = "22";
	$colspan = "8";
	$colspan2 = "14";
	$calt = "17";
} else {
	$nombre = "TODAS LAS ASEGURADORAS";
	$columna = "23";
	$clase = "0";
	$colspan = "9";
	$colspan2 = "13";
	$calt = "18";
}

function CiudadRep($id)
{
	$query = mysql_query(
		"SELECT * FROM  seguro_clientes WHERE id='" . $id . "' LIMIT 1"
	);
	$row = mysql_fetch_array($query);

	$queryp1 = mysql_query(
		"SELECT * FROM  ciudad WHERE id='" . $row['ciudad'] . "' LIMIT 1"
	);
	$rowp1 = mysql_fetch_array($queryp1);

	$queryp2 = mysql_query(
		"SELECT * FROM  municipio WHERE id='" . $rowp1['id_muni'] . "' LIMIT 1"
	);
	$rowp2 = mysql_fetch_array($queryp2);

	$queryp3 = mysql_query(
		"SELECT * FROM   provincia WHERE id='" . $rowp2['id_prov'] . "' LIMIT 1"
	);
	$rowp3 = mysql_fetch_array($queryp3);

	return $rowp3['descrip'];
}
?>

<table>
	<tr>
		<td colspan="<?= $columna ?>">&nbsp;</td>
	</tr>

	<tr>
		<td colspan="<?= $colspan ?>">

			<b style="font-size: 70px; color: #d9261c;">Multi</b><b style="font-size: 70px; color: #828282;">Seguros</b>
		</td>
		<td colspan="<?= $colspan2 ?>" align="center">
			<b style="font-size:23px">REPORTE DIARIO DE VENTAS 1</b>
			<b style="font-size:18px"><br><?= $nombre ?>
				<br><b>Desde:</b>&nbsp;&nbsp;<?= $fecha1 ?>&nbsp;&nbsp;&nbsp;<b>Hasta:</b>&nbsp;&nbsp;<?= $fecha2 ?>
			</b>
		</td>
	</tr>

	<tr style="font-size:16px; color:#FFFFFF; font-weight:bold">
		<td style="background-color:#B1070A;">#</td>

		<td style="background-color:#B1070A;">No.Poliza</td>
		<?php if ($clase == '0') { ?>
			<td style="background-color:#B1070A;">Aseguradora</td>
		<?php } ?>
		<td style="background-color:#B1070A;">Nombres</td>
		<td style="background-color:#B1070A;">Apellidos</td>
		<td style="background-color:#B1070A;">C&eacute;dula</td>
		<td style="background-color:#B1070A;">Direcci&oacute;n</td>
		<td style="background-color:#B1070A;">Ciudad</td>
		<td style="background-color:#B1070A;">Tel&eacute;fono</td>
		<td style="background-color:#B1070A;">Tipo</td>
		<td style="background-color:#B1070A;">Marca</td>
		<td style="background-color:#B1070A;">Modelo</td>
		<td style="background-color:#B1070A;">A&ntilde;o</td>
		<td style="background-color:#B1070A;">Chassis</td>
		<td style="background-color:#B1070A;">Placa</td>
		<td style="background-color:#B1070A;">Fecha Emisi&oacute;n</td>
		<td style="background-color:#B1070A;">Inicio Vigencia</td>
		<td style="background-color:#B1070A;">Fin Vigencia</td>
		<td style="background-color:#B1070A;">DPA</td>
		<td style="background-color:#B1070A;">AP</td>
		<td style="background-color:#B1070A;">RC</td>
		<td style="background-color:#B1070A;">RC2</td>
		<td style="background-color:#B1070A;">FJ</td>
		<td style="background-color:#B1070A;">Prima</td>
	</tr>


	<?php
	$fd1 = explode('/', $fecha1);
	$fh1 = explode('/', $fecha2);
	$fDesde = $fd1[2] . '-' . $fd1[1] . '-' . $fd1[0];
	$fHasta = $fh1[2] . '-' . $fh1[1] . '-' . $fh1[0];
	$wFecha = "fecha >= '$fDesde 00:00:00' AND fecha <= '$fHasta 23:59:59'";

	$qR = mysql_query("SELECT * FROM seguro_transacciones_reversos");
	while ($rev = mysql_fetch_array($qR)) {
		$reversadas .= "[" . $rev['id_trans'] . "]";
	}

	$query = mysql_query(
		"SELECT * FROM seguro_transacciones WHERE $wFecha $aseg order by id ASC"
	);
	while ($u = mysql_fetch_array($query)) {
		if (substr_count($reversadas, "[" . $u['id'] . "]") > 0) {
		} else {

			$t++;
			$RepMontoSeguro = RepMontoSeguro($u['id']);
			$veh = explode("|", CrearVehiculo($u['id_vehiculo']));
			$ServMonto = RepMontoServ($u['id'], $u['serv_adc']);
			$precio = $RepMontoSeguro + $ServMonto;
			$Tprecio += $precio;
			$precio = substr(formatDinero($precio), 0, -3);
			$tipo = explode("|", RepTipo($veh[0]));
			$Nombretipo = $tipo[0];
			$id = $u['id'];
			/*
	echo "ServMonto: ".$ServMonto."<br>";
	echo "RepMontoSeguro: ".$RepMontoSeguro."<br>";*/

			$dd = explode("|", VerVariable($u['serv_adc']));
			$dpa_1 = $dd[0];
			$ap_1 = $dd[1];
			$rc_1 = $dd[2];
			$rc2_1 = $dd[3];
			$fj_1 = $dd[4];

			if ($dpa_1 > 0) {
				$dpa = substr(formatDinero($dpa_1), 0, -3);
			} else {
				$dpa = substr(formatDinero($tipo[1]), 0, -3);
			}

			if ($ap_1 > 0) {
				$ap = substr(formatDinero($ap_1), 0, -3);
			} else {
				$ap = substr(formatDinero($tipo[2]), 0, -3);
			}

			if ($rc_1 > 0) {
				$rc = substr(formatDinero($rc_1), 0, -3);
			} else {
				$rc = substr(formatDinero($tipo[3]), 0, -3);
			}

			if ($rc2_1 > 0) {
				$rc2 = substr(formatDinero($rc2_1), 0, -3);
			} else {
				$rc2 = substr(formatDinero($tipo[4]), 0, -3);
			}

			if ($fj_1 > 0) {
				$fj = substr(formatDinero($fj_1), 0, -3);
			} else {
				$fj = substr(formatDinero($tipo[5]), 0, -3);
			}

			$marca = VehiculoMarca($veh[1]);
			$modelo = VehiculoModelos($veh[2]);
			$cliente = explode("|", Clientes($u['id_cliente']));
			$pref = GetPrefijo($u['id_aseg']);
			$idseg = str_pad($u['id_poliza'], 6, "0", STR_PAD_LEFT);
			$prefi = $pref . "-" . $idseg;
	?>

			<tr style="font-size:12px; text-align:left">
				<td><b><?= $u['id'] ?></b></td>
				<td><?= $prefi . ' | ' . $u['id_poliza'] ?></td>
				<?php if ($clase == '0') { ?>
					<td style=" <?= $clase ?>"><?= NombreSeguroS($row['id_aseg']) ?></td>
				<?php } ?>
				<td><?= $cliente[0] ?></td>
				<td><?= $cliente[1] ?></td>
				<td><?= CrearCedula($cliente[2]) ?></td>
				<td><?= $cliente[3] ?></td>
				<td><?= Ciudad($cliente[4]) != null ? Ciudad($cliente[4]) : CiudadVia($u['id']) ?> </td>
				<td><?= CrearTelefono($cliente[5]) ?></td>
				<td><?= $Nombretipo ?></td>
				<td><?= $marca ?></td>
				<td><?= $modelo ?></td>
				<td><?= $veh[3] ?></td>
				<td><?= $veh[5] ?></td>
				<td align="right"><?= $veh[4] ?></td>
				<td align="center" style="width:150px"><?= $u['fecha'] ?></td>
				<td align="center" style="width:150px"><?= FechaReporte(
															$u['fecha_inicio']
														) ?></td>
				<td align="center" style="width:150px"><?= FechaReporte(
															$u['fecha_fin']
														) ?></td>
				<td><?= $dpa ?></td>
				<td><?= $ap ?></td>
				<td><?= $rc ?></td>
				<td><?= $rc2 ?></td>
				<td><?= $fj ?></td>
				<td align="right"><?= $precio ?></td>
			</tr>
	<?php
		}
	}
	?>

	<tr>
		<td colspan="<?= $calt ?>"></td>
		<td colspan="4" align="right">
			<h4>Total de primas&nbsp;</h4>
		</td>
		<td>
			<h4><?= formatDinero($Tprecio) ?></h4>
		</td>
	</tr>

</table>