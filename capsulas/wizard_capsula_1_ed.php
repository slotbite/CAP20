<?
session_start();
include("../default.php");
$plantilla->setPath('../skins/saam/plantillas/');

$nusuario=$_SESSION['usuario'] ? $_SESSION['usuario'] : '';

$plantilla->setTemplate("header_3");
//


echo $plantilla->show();

$nusuario       = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$capsula_id     = $_GET['capsula'] ? $_GET['capsula']:0;
$tema_id        = $_GET['tema'] ? $_GET['tema']:0;

$capsulaVersion = $_GET['version']? $_GET['version']:0;
$cliente_id		= $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$perfil_id=$_SESSION['perfilId'];

//echo 'cap:'.$capsula_id."tema:".$tema_id;
if(isset($_SESSION['capsulaId2'])){
	$capsula_id=$_SESSION['capsulaId2'];
	$capsulaVersion=$_SESSION['capsulaVersion2'];
}else{
$queryE="EXEC capPreparaDatosEdicion $capsula_id,$capsulaVersion,$cliente_id,'$perfil_id'";
$resultE = $base_datos->sql_query($queryE);
$rowE	= $base_datos->sql_fetch_assoc($resultE);
$_SESSION['capsulaId2']=$rowE["capsulaId"];
$_SESSION['capsulaVersion2']=$rowE["capsulaVersion"];
$_SESSION['Tema2']=$tema_id ;
$capsula_id=$_SESSION['capsulaId2'];
$capsulaVersion=$_SESSION['capsulaVersion2'];
}

$queryTipo 	= "EXEC capVerCapsula ".$cliente_id.",".$capsula_id.",".$capsulaVersion." ";
	$result = $base_datos->sql_query($queryTipo);
	$row	= $base_datos->sql_fetch_assoc($result);
	$Capnombre = htmlentities($row["capsulaNombre"]);
	$capsulaTipo = $row["capsulaTipo"];
	$temaNombre = htmlentities($row["temaNombre"]);
	$temaId=$row["temaId"];
	$desc=htmlentities($row["capsulaDescripcion"]);
	//echo $queryTipo;
	
?>

<script type="text/javascript" src="../scripts/Funciones.js"></script>

<script type="text/javascript" src="../scripts/overlay.js"></script>
<script type="text/javascript" src="../scripts/multiBox.js"></script>
 <link type="text/css" rel="stylesheet" href="../skins/saam/plantillas/multiBox.css" />
<script type="text/javascript" type="text/javascript">


function siguiente(){

	$('ErrorNombre').set('html', '');
	
	$('irwizard2').click();

}

function guardar1(){
valido=true;
nombretema=$('nombre_tema').get('value');
temaid=$('tema_id').get('value');
clienteid=$('cliente_id').get('value');
nombrecapsula=$('nombre_capsula').get('value');
descripcion=$('desc_capsula').get('value');

tipo=$$('input[name=TipoCapsulaG]:checked').get('value');

	if(nombrecapsula==""){
		valido=false;
		$('ErrorNombre').set('html', "<span style='color:red'><b>Debe ingresar un nombre de C&aacute;psula</b></span>");
	}
		
	
	if(valido==true){
		//guardar en base de datos:
		//y de ahi hacer el redirect a la siguiente pantalla
		
		var elRequest = new Request({
						url		: '../librerias/editar_capsula.php', 
						method  :'post',
						onSuccess: function(html) {
							$('ErrorNombre').set('html', '');
							alert('Los Datos de la C\u00e1psula han sido guardados Exitosamente');
						},
						//Si Falla
						onFailure: function() {
							$('ErrorNombre').set('html', 'Error de conexi&oacute;n');
						}
					});
					elRequest.send(	"cliente_id=" 		+ clienteid + 
									"&nombre_capsula=" 	+ nombrecapsula +
									"&descripcion=" 	+ descripcion +
									"&capsula_id="      + $('capsula_id').get('value')+
									"&capsula_ver="     + $('capsula_ver').get('value')
									);
		
	}
	
}

function cancelar(){
		if (confirm('Perder\u00e1 todos los cambios realizados a la C\u00e1psula\nEst\u00e1 Seguro?')==true){
			CancelarCreacionCapsula();
		}
}


function guardaysale(){
if(confirm('Desea Cerrar el Asistente?')==true){
	CerrarWizard();
	parent.searchBoxPpal.close();
	}
}

</script>

<?
$Cliente_id = $_SESSION["clienteId"] ? $_SESSION["clienteId"] : 0;
$usuario_id = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
			
			if($usuario_id ==''){
			echo "<script>window.location='../index.php';</script>";
?>
<?
}else{

// $plantilla->setTemplate("menu2");
// $fecha=date("d-m-Y");
// $plantilla->setVars(array(	"USUARIO"		=>"$usuario_id",
							// "FECHA"	=>	"$fecha"
							// ));


// echo $plantilla->show();

$plantilla->setTemplate("wizard_capsula_1_ed");
$plantilla->setVars(array(	"USUARIO"		=>"$usuario_id",
							"CLIENTE"	=>	"$Cliente_id",
							"TEMANOMBRE"=>"$temaNombre",
							"TEMAID"=>"$tema_id",
							"CAPNOMBRE"=>"$Capnombre",
							"TIPOCAP"=>"$capsulaTipo",
							"CAPSULAID"=>"$capsula_id",
							"CAPSULAVER"=>"$capsulaVersion",
							"DESCRIPCION"=>"$desc"
							));
echo $plantilla->show();

}
?>

<?
// $plantilla->setTemplate("footer_2");
// echo $plantilla->show();
?>

<script type="text/javascript">
window.addEvent('domready',function(){
	var tipo=<? echo $capsulaTipo; ?>;
	 if(tipo==1){
		$('TipoCapsulaG1').checked=true;
		$('cuestionario').setStyle('display', 'block');
		$('encuesta').setStyle('display', 'none');
		$('contenido').setStyle('display', 'none');
	 }
	 
	if(tipo==2){
		$('TipoCapsulaG2').checked=true
		$('cuestionario').setStyle('display', 'none');
		$('encuesta').setStyle('display', 'block');
		$('contenido').setStyle('display', 'none');
	 }
	 
	 if(tipo==3){
		$('TipoCapsulaG3').checked=true
		$('cuestionario').setStyle('display', 'none');
		$('encuesta').setStyle('display', 'none');
		$('contenido').setStyle('display', 'block');
	 }

	
	$('nombre_capsula').addEvent('change', function(){
		var elRequest = new Request({
						url		: '../librerias/verificar_nombre_capsula_ed.php', 
						method  :'post',
						onSuccess: function(html) {
							//Limpiar el Div 
							$('ErrorNombre').set('html', '');
							$('ErrorNombre').set('html', html);
						},
						//Si Falla
						onFailure: function() {
							$('ErrorNombre').set('html', 'Error de conexi&oacute;n');
						}
					});
					elRequest.send(	"nombre_capsula=" 		+ $('nombre_capsula').get('value') + 
									"&cliente_id=" 	+ $('cliente_id').get('value')+
									"&capsula_id=" + $('capsula_id').get('value')
									
									);
	});
	
		
});

function CancelarCreacionCapsula(){

/*
Pregunto si es nulo el control donde guardo el capsulaId y la versión,
porque en la primera pantalla del wizard aun no se han asignado esos 
valores ni creado los controles, por lo tanto no es necesario ir a la 
base de datos con el Request.
*/

	capsulaId=0;
	capsulaVersion =0;
	
	if( $('capsula_id')!=null){
	capsulaId=$('capsula_id').get('value');
	}
	
	if($('capsula_ver')!=null){
	capsulaVersion = $('capsula_ver').get('value');
	}
	
	
	if(capsulaId != 0 && capsulaVersion!= 0){
	/*
	Este Request va a la Base de Datos y elimina todos los datos ingresados de la cápsula.
	Además limpia las variables de sesión donde se guarda el capsulaId y la versión
	*/
		if(capsulaVersion!=1){

		var elRequest = new Request({
						url		: '../librerias/cancelar_creacion_capsula.php', 
						method  :'post',
						onSuccess: function(html) {
							
						},
						//Si Falla
						onFailure: function() {
							//alert('error al conectarse a la base de datos');
						}
					});
					elRequest.send(	"capsulaId=" 		        + capsulaId + 
									"&capsulaVersion=" 	        + capsulaVersion
									);
		}
	}else{
		var elRequest = new Request({
			url		: '../librerias/cancelar_envio_capsula.php', 
			method  :'post',
			async:'false',
			onSuccess: function(html) {
				
			},
			//Si Falla
			onFailure: function() {
				//alert('error al conectarse a la base de datos');
			}
		});
		elRequest.send();
	
	}
	
	//window.location='adm_capsulas.php';
	parent.searchBoxPpal.close();
	
}
</script>