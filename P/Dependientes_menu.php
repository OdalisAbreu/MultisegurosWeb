<? 
ini_set('display_errors',1);
ini_set('session.cache_expire','3000');

if($_SESSION["funcion_id"] =='37'){ ?>

<li>
	<a href="#" onClick="CargarAjax2('Admin/SubirArchivos/List.php','','GET','cargaajax');">
    	<i class="fa fa-tag fa-fw"></i> Subir Agencias
     </a>
</li>



<li>
    <a href="#" onClick="CargarAjax2('Admin/Sist.Dependientes/Revisiones/Imprimir/listado.php','','GET','cargaajax');">Consulta poliza
    </a>
 </li>
 <li>
 	<a href="#" onClick="CargarAjax2('Admin/Sist.Dependientes/Reportes/listado_trans.php','','GET','cargaajax');">
    	<i class="fa fa-tag fa-fw"></i> Transacciones
    </a>
 </li>
 
 <li>
 	<a href="#" onClick="CargarAjax2('Admin/Sist.Dependientes/Reportes/listado.polizs.remitidas.php','','GET','cargaajax');">
    <i class="fa fa-tag fa-fw"></i> Rep. Polizas remitidas
    </a>
</li>
<li>
    <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Revisiones<span class="fa arrow"></span></a>
    <ul class="nav nav-second-level">
      <li>
        <a href="#" onClick="CargarAjax2('Admin/Sist.Distribuidor/Revisiones/Ticket/listado.php','','GET','cargaajax');">Modificar Poliza</a>
      </li>
    </ul>
    <!-- /.nav-second-level -->
  </li>

  



<? } ?>