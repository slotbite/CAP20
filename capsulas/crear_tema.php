<?
session_start();
include("../default.php");

ini_set('post_max_size','100M');
ini_set('upload_max_filesize','100M');
ini_set('max_execution_time','1000');
ini_set('max_input_time','1000');

$nusuario=$_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente=$_SESSION['clienteId'] ? $_SESSION['clienteId'] : 0;
$plantilla->setPath('../skins/saam/plantillas/');
$plantilla->setTemplate("header_3");
//
echo $plantilla->show();
?>
<link rel="stylesheet" type="text/css" href="../skins/saam/plantillas/style.css" media="screen" />
<script type="text/javascript" src="../scripts/Funciones.js"></script>
<script type="text/javascript" type="text/javascript">
function ValidarTema(){
	var valido=true;
	var nombreTema=document.getElementById("nombre_tema").value;
	var descTema=document.getElementById("desc_tema").value;
        var elDiv=document.getElementById("mensaje");
        
        var archivo1=document.getElementById('img_tema');
            if(archivo1.value!=''){
                    var fullName=archivo1.value;
                    var splitName = fullName.split(".");
                    var fileType = splitName[1];
                    fileType = fileType.toLowerCase();
                        if(fileType!='gif'&&fileType!='jpg'&&fileType!='png'){
                                elDiv.innerHTML = "<span style='color:red'><b>Solo se permiten imagenes GIF, JPG y PNG</b></span>";
                                valido=false;       
                        }
            }
        
            if(nombreTema==''||descTema==''){
                    valido=false;
            }
	
        
        
	if(valido==true){
		document.forms["FormTema"].submit();
	}else{
                if(nombreTema==''||descTema==''){
                  elDiv.innerHTML = "<span style='color:red'><b>Debe ingresar el Nombre y la Descripci&oacute;n del Nuevo Tema</b></span>";
                }
	}
	
}

function ErrorImagen(nombre,desc){
    document.getElementById("nombre_tema").value=nombre;
    document.getElementById("desc_tema").value=desc;
    document.getElementById('mensaje').innerHTML="<span style='color:red'><b>ERROR: La imagen debe ser de 700 x 200 pixeles</b></span>";
}

function ErrorTema(){
    document.getElementById('mensaje').innerHTML="<span style='color:red'><b>ERROR: Ya existe un Tema con ese nombre</b></span>";
}
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META HTTP-EQUIV="Expires" CONTENT="Mon, 26 Jul 1980 05:00:00 GMT">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta http-equiv="expires" content="0">
<title>C&aacute;psulas de Conocimiento</title>
<link rel="stylesheet" type="text/css" href="../skins/saam/plantillas/style.css" media="screen" />
</head>
    <body>
        <form method="post" id="FormTema" action="crear_tema.php" enctype="multipart/form-data">
            <table border="0" style="text-align:left;" width="100%">
            <tr>
                    <td>
                    <b>Nombre del Tema</b><span class="requerido">&nbsp;*</span>
                    </td>
            </tr>
            <tr>	
                    <td>
                    <input id="nombre_tema" name="nombre_tema" type="text" class="visible" style="width:300px" maxlength="50" onkeyup="isAlfaNum(this);" />
                    </td>
            </tr>
            <tr>
                    <td>
                    <b>Descripcion del Tema</b><span class="requerido">&nbsp;*</span>
                    </td>
            </tr>
            <tr>	
                    <td>
                    <input id="desc_tema" name="desc_tema" type="text" class="visible" style="width:300px" maxlength="250" onkeyup="isAlfaNum(this);" />
                    </td>
            </tr>
            <tr>
                    <td>
                    <b>Personalizar Encabezado</b>
                    </td>
            </tr>
            <tr>	
                    <td>
                    <input id="img_tema" name="img_tema" type="file" class="visible" style="width:300px" maxlength="250" />
                    </td>
            </tr>    
            <tr>
            <td colspan="2">
            <div id="mensaje" style="color:red;font-size:11px;height:30px;"></div>
            </td>
            </tr>
            <tr>
            <td colspan="2" valign="top" align="left">
                                    <input type="button" value="Crear" onclick="ValidarTema();" class="btn azul"/>
                                    <input type="button" value="Cancelar" onclick="parent.searchBox.close()" class="btn" />
            </td>
            </tr>
            </table>
        </form>
    </body>
</html>

<?
if (($_POST["nombre_tema"] != "")&&($_POST["desc_tema"] != "")) {
                                $errorimagen=0;
                                $imagen=$_POST["img_tema"]? $_POST["img_tema"] :"";
                                if (isset($_FILES['img_tema']['tmp_name'])) {
                                    $uploaddir = "../mantenedores/encabezados/";
                                        if (file_exists($uploaddir)==false) {
                                            mkdir($uploaddir, 0700);
                                        }
                                    
                                    list($ancho, $alto) = getimagesize($_FILES['img_tema']['tmp_name']);
                                                                       
                                    if($ancho==700 && $alto <=200){
                                        $nombreimagen=mb_convert_encoding($_FILES['img_tema']['name'], "ISO-8859-1", "UTF-8");
                                        $arreglo1=explode(".",$nombreimagen);
                                        $extension=$arreglo1[1];
                                        
                                        //maximo tema_id +1
                                        $queryL = "select isnull(max(temaid),0)+1 as temaid from temas where clienteId=".$cliente."";
                                        $resultL = $base_datos->sql_query($queryL);
                                        $rowL	= $base_datos->sql_fetch_assoc($resultL);
                                        $temaTemp=$rowL['temaid'] ? $rowL['temaid']:'';
                                        
                                        $nombrefinal=$cliente."_".$temaTemp.".".$extension;

                                        $uploadfile = $uploaddir . basename($nombrefinal);
                                        $error = $_FILES['img_tema']['error'];
                                        $subido = false;
                                            if($error==UPLOAD_ERR_OK) { 
                                                $subido = copy($_FILES['img_tema']['tmp_name'], $uploadfile); 
                                            }
                                    }else{
                                        
                                     $errorimagen=1;   
                                    }
                                    if(trim($imagen)==""){
                                      $errorimagen=0;    
                                    }
                                }
                                
                                if($errorimagen==0){
                                    $archivo="";
                                    if(isset($uploadfile)){
                                        $archivo=substr($uploadfile,2,strlen($uploadfile));
                                    }
                                    
                                    $administrador_id=$_SESSION['administradorId'];
                                    $nombre_tema=mb_convert_encoding($_POST["nombre_tema"], "ISO-8859-1", "UTF-8");
                                    $desc_tema=mb_convert_encoding($_POST["desc_tema"], "ISO-8859-1", "UTF-8");

                                    $query 	= "EXEC capInsertaTema '".$nombre_tema."','".$desc_tema."',".$cliente.",'".$administrador_id."','".$archivo."'";

                                    $result = $base_datos->sql_query($query);

                                    $row	= $base_datos->sql_fetch_assoc($result);

                                    $errorIns = $row["errorIns"] ? $row["errorIns"] : 0;
                                    $id = $row["temaId"] ? $row["temaId"] : 0;

                                        if($errorIns ==0){
                                                $nombre_tema=mb_convert_encoding($nombre_tema,"UTF-8","ISO-8859-1");
                                                echo "<script>parent.document.getElementById('nombre_tema').value='".$nombre_tema."'</script>";
                                                echo "<script>parent.document.getElementById('tema_id').value='".$id."'</script>";
                                                echo "<script>parent.searchBox.close()</script>";

                                        }else{
                                            //echo "<script>ErrorTema();</script>";
                                        }
                                }else{
                                    echo "<script>ErrorImagen('".$_POST["nombre_tema"]."','".$_POST["desc_tema"]."');</script>";
                                    
                                }
				
    }
?>
