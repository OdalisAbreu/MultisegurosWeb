 <style>
 .card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 solid rgba(0,0,0,.125);
    border-radius: .25rem;
}

.callout, .card, .info-box, .mb-3, .my-3, .small-box {
    margin-bottom: 1rem!important;
}

.card {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
}

*, ::after, ::before {
    box-sizing: border-box;
}

:root {
    --blue: #007bff;
    --indigo: #6610f2;
    --purple: #6f42c1;
    --pink: #e83e8c;
    --red: #dc3545;
    --orange: #fd7e14;
    --yellow: #ffc107;
    --green: #28a745;
    --teal: #20c997;
    --cyan: #17a2b8;
    --white: #ffffff;
    --gray: #6c757d;
    --gray-dark: #343a40;
    --primary: #007bff;
    --secondary: #6c757d;
    --success: #28a745;
    --info: #17a2b8;
    --warning: #ffc107;
    --danger: #dc3545;
    --light: #f8f9fa;
    --dark: #343a40;
    --breakpoint-xs: 0;
    --breakpoint-sm: 576px;
    --breakpoint-md: 768px;
    --breakpoint-lg: 992px;
    --breakpoint-xl: 1200px;
    --font-family-sans-serif: "Source Sans Pro",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
    --font-family-monospace: SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;
}

.card-info:not(.card-outline) .card-header, .card-info:not(.card-outline) .card-header a {
    color: #fff;
}

.card-info:not(.card-outline) .card-header {
    background-color: #dc3545;
    border-bottom: 0;
}

.card-header {
    position: relative;
    background-color: transparent;
    border-bottom: 1px solid rgba(0,0,0,.125);
    border-top-left-radius: .25rem;
    border-top-right-radius: .25rem;
}

.card-header {
    padding: .75rem 1.25rem;
    margin-bottom: 0;
    background-color: rgba(0,0,0,.03);
    border-bottom: 0 solid rgba(0,0,0,.125);
}
.card-title {
    font-size: 1.25rem;
    font-weight: 400;
    margin: 0;
}

.btn:not(:disabled):not(.disabled) {
    cursor: pointer;
}

.btn-info {
    color: #fff;
    background-color: #dc3545;
    border-color: #B02835;
    box-shadow: 0 1px 1px rgba(0,0,0,.075);
}

.btn {
    display: inline-block;
    font-weight: 400;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    user-select: none;
    border: 1px solid transparent;
    padding: .375rem .75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: .25rem;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
 </style>
 <script>
   //form Submit
   $("form").submit(function(evt){
	   
	   	 $("#btn").fadeOut(0);
		 $("#btn2").fadeIn(0);
			  
      evt.preventDefault();
      var formData = new FormData($(this)[0]);
	  
   $.ajax({
	   
       url: 'Admin/SubirArchivos/ListResp.php',
       type: 'POST',
       data: formData,
       async: false,
       cache: false,
       contentType: false,
       enctype: 'multipart/form-data',
       processData: false,
       success: function (response) {
		 var data = response;
		var arr = data.split('/');
	
		//alert(arr[0]);
		
		 if(arr[1]=="100"){
			 $("#10").fadeIn(0);
			 $("#11").fadeOut(0);
			 $("#12").fadeOut(0);
			 CargarAjax2('Admin/SubirArchivos/ListadoSubida.php','','GET','manejo');
			 	//alert(arr[1]); 
		 }else
		 if(arr[1]=='101'){
			 $("#10").fadeOut(0); 
			 $("#11").fadeIn(0);
			 $("#12").fadeOut(0);
			 CargarAjax2('Admin/SubirArchivos/ListadoSubida.php','','GET','manejo'); 
		 }else
		 if(arr[1]=='102'){
			 $("#10").fadeOut(0);
			 $("#11").fadeOut(0);
			 $("#15").fadeIn(0);
			 CargarAjax2('Admin/SubirArchivos/ListadoSubida.php','','GET','manejo'); 
		 }
		
		$("#btn").fadeIn(0);
		$("#btn2").fadeOut(0);
		
	
		
       }
   });
  //}
   return false;
 });
</script>

<section class="content" style="padding-top: 3%;">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-4">
		<div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Subir Archivo 2 </h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal">
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-offset-0 col-sm-4 control-label" style="margin-left: 15px;">Archivo debe ser: .XLS <font style="color:#F6060A; font-size:10px; display:inline;">(Obligatoro)</font></label>
                  </div>
                  <div class="form-group" style="margin-left: 0px;">
                    <label for="inputPassword3" class="col-sm-offset-0 col-sm-10 control-label"><input id="excel" name="excel" type="file" /></label>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer col-sm-offset-1" style="margin-bottom: 20px; padding-top: 10px;" id="btn">
                  <button type="submit" class="btn btn-info" >Subir Archivo</button> 
                </div>
                <div class="card-footer col-sm-offset-1" style="margin-bottom: 20px; padding-top: 10px; display:none" id="btn2">
                  <button type="button" class="btn btn-warning" id="btn">Subiendo Archivo</button> 
               </div>
                <!-- /.card-footer -->
              </form>
            </div>
            </div>
            
<div class="col-md-8">
	<!--mostrando los archivos subidos-->
    	<div id="manejo"></div>	
		<script>
            $( document ).ready(function() {
              CargarAjax2('Admin/SubirArchivos/ListadoSubida.php','','GET','manejo'); 
            });
        </script>
    <!--mostrando los archivos subidos-->
</div>
            
          </div>
          <!--RESPUESTAS-->
          <div class="row">
      		<div class="col-md-4">
            <div id="10" style="display:none;"> 
	<div class="card-body">
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h5><i class="icon fa fa-check"></i> Alerta!</h5>
          Archivo Cargado Con Exito
        </div>
     </div> 
</div>
<div id="11" style="display:none;">
	<div class="card-body">
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h5><i class="icon fa fa-ban"></i> Alerta!</h5>
          Error Al Cargar el Archivo, verifique que la extension sea NombreArchivo<b>.xls</b>
        </div>
     </div> 
</div>
<div id="15" style="display:none;">
	<div class="card-body">
        <div class="alert alert-warning alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h5><i class="icon fa fa-warning"></i> Alerta!</h5>
           Error Al Cargar el Archivo, Primero debes cargar el archivo con extencion .xls
        </div>
     </div> 
</div>
<div id="13" style="display:none;">
	<div class="card-body">
        <div class="alert alert-warning alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h5><i class="icon fa fa-warning"></i> Alerta!</h5>
           Error Al Cargar el Archivo, Cargue el archivo con extensiones .xls unicamente.
        </div>
     </div> 
</div>
            </div>
          </div>
          <!--RESPUESTAS-->
        </div>
     </section>