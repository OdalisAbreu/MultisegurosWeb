<?
	set_time_limit(0);
	session_start();
	ini_set('display_errors',1);
	$login ='7bhoi';
	include("../../incluidos/conexion_inc.php");
	include("../../incluidos/nombres.func.php");
	Conectarse();
?>
    
    <div class="col-lg-12">
        <div class="panel panel-default">
                <div class="panel-heading">
                    Registros actualmente 
         </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-hover">
                      <thead>
                          <tr>
                            <th>Fecha</th>
                            <th>Descrip</th>
                            <th>Codigo</th>
                            <th>&nbsp;</th>
                          </tr>
                      </thead>
                      <tbody>
  <? 

	$res= mysql_query("SELECT * FROM auditoria WHERE user_id = '".$_SESSION['user_id']."' AND codigo IN (100,101,102)  ORDER BY id DESC LIMIT 5"); 
	while($row=mysql_fetch_array($res)){
		
if($row['codigo']=='100'){
		$codigo = 'OK';
}else if($row['codigo']=='101'){
		$codigo = 'ERROR';
}else if($row['codigo']=='102'){
		$codigo = 'ERROR_ARCH';
}
?>
<tr style="font-size:13px !important" >
    <td><?=FechaHoraSubidaExcel($row['fecha'])?></td>
    <td><?=$row['descrip']?><br>
    <b>IP: <?=$row['ip']?></b></td>
    <td><?=$codigo?></td>
    <td>
    	 <a href="javascript:void(0)" class="btn btn-success"  onclick="location.replace('Admin/SubirArchivos/Descargar.php?nombre=<?=$row['descrip']?>');"> 
         <i class="fa fa-download fa-2" style="font-size: 15px;"></i>  </a>
    </td>
  </tr>
  <? }  ?>
  
    </tbody>
</table>
 </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>