<?
session_start();
include("../default.php");
$plantilla->setPath('../skins/saam/plantillas/');

$nusuario=$_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id= $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;
$envioId=$_GET['envioId'] ? $_GET['envioId'] : 0;
$plantilla->setTemplate("header_3");
//
echo $plantilla->show();?>
<script type="text/javascript" src="../scripts/overlay.js"></script>
<script type="text/javascript" src="../scripts/multiBox.js"></script>
<script type="text/javascript" src="../scripts/tablesort.js"></script>
<link type="text/css" rel="stylesheet" href="../skins/saam/plantillas/multiBox.css" />
<div>
<?
$usuario_id = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
			
			if($usuario_id ==''){
			echo "<script>window.location='../index.php';</script>";
?>
<?
}else{

$queryE="EXEC envDatosEnvio $cliente_id,$envioId";
$resultE = $base_datos->sql_query($queryE);
$rowE	= $base_datos->sql_fetch_assoc($resultE);
$nombre=htmlentities($rowE["capsulaNombre"]);

$plantilla->setTemplate("resetearEnvio");
$plantilla->setVars(array(	"NOMBRE"		=>"$nombre",
							"ENVIO"	=>	"$envioId"
							));
echo $plantilla->show();

}
?>
</div>

<script>
// function Envio(){
	// window.location='../reportes/ListaEnvios_00.php';
// }
function Cerrar(){
	parent.searchBoxPpal.close();
}

window.addEvent('domready', function (){	
				
				var elRequest1 = new Request({
						url		: '../librerias/listar_usuarios_reseteo.php', 
						method  :'post',
						onSuccess: function(html) {
							$('Busqueda').set('html','');
							//Limpiar el Div y recargar la lista!!!
							$('Busqueda').set('html',html);
							 fdTableSort.init('ListadeUsuarios');
							
						},
						//Si Falla
						onFailure: function() {
							$('Busqueda').set('html', 'Error de conexi&oacute;n');
						}
					});
					elRequest1.send("envioId=" + <? echo $envioId; ?> );
	});	
	
function SeleccionarTodo(valor){
		
	    var tabla=$('ListadeUsuarios');
		var largo=tabla.rows.length;	
		for (i=1;i<largo;i++){
			tabla.rows[i].cells[0].getElementsByTagName('input')[0].checked=valor;
		}
	}	
	
function Resetear(){
	var chequeados=0;
	var tabla=$('ListadeUsuarios');
	var largo=tabla.rows.length;	
	$('ErrorBusqueda').set('html','');
		for (i=1;i<largo;i++){
			if(tabla.rows[i].cells[0].getElementsByTagName('input')[0].checked==true){
				chequeados=chequeados+1;
			}
		}
	if(chequeados==0){
		$('ErrorBusqueda').set('html',"<span style='color:red'><b>Debe seleccionar al menos un Usuario</b></span>");
	}else{
		
		for (i=1;i<largo;i++){
			if(tabla.rows[i].cells[0].getElementsByTagName('input')[0].checked==true){
			var usuarioID=tabla.rows[i].cells[1].getElementsByTagName('input')[0].value;
						var elRequest1 = new Request({
							url		: '../librerias/reseteo_encuesta.php', 
							method  :'post',
							link: 'chain',
							onSuccess: function(html) {
															
							}
						});
						elRequest1.send("envioId=" + <? echo $envioId; ?>+
										"&usuarioId="+usuarioID
						);
			}
		}
		alert('Se han reseteado las respuestas de los Ususarios Seleccionados');
	}
}
</script>