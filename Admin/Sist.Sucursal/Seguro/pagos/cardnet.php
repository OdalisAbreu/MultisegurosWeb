<?php

session_start();
ini_set("display_errors", 1);
include("../../inc/conexion_inc.php");
Conectarse();
include('../../inc/bd_manejos.php');
include('../../inc/Func/Marcas_func.php');
include('../../inc/fechas.func.php');
include('../../inc/Func/Modelos_func.php');
include("../../idiomas/" . $_SESSION['idioma'] . ".php");

?>

<body>
    <h1>Entro aqui</h1>
</body>