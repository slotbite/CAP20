<?php
session_start();

ini_set('post_max_size', '100M');
ini_set('upload_max_filesize', '100M');
ini_set('max_execution_time', '1000');
ini_set('max_input_time', '1000');

include("../default.php");

setlocale(LC_TIME, "spanish");
$fechaActual = htmlentities(strftime("%A %d de %B del %Y"));
$fechaActual = ucfirst($fechaActual);

$nusuario = $_SESSION['usuario'];
$plantilla->setPath('../skins/saam/plantillas/');
$plantilla->setTemplate("header_2");
$plantilla->setVars(array("USUARIO" => " $nusuario ",
    "FECHA" => "$fechaActual"));
echo $plantilla->show();

$clienteId = $_SESSION['clienteId'];
$usuarioModificacion = $_SESSION['usuario'];

if ($clienteId == '') {
    echo "<script>window.location='../';</script>";
}

require('clases/organizaciones.class.php');
$objOrganizacion = new organizaciones;

$consulta = $objOrganizacion->manBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta);
$clienteNombre = $cliente['clienteNombreCompleto'];

if (isset($_POST['submit'])) {

    $organizacionNombre = mb_convert_encoding(trim($_POST['inOrganizacionNombre']), "ISO-8859-1", "UTF-8");
    $organizacionDescripcion = mb_convert_encoding(trim($_POST['inOrganizacionDescripcion']), "ISO-8859-1", "UTF-8");

    $archivo = mb_convert_encoding($_FILES['inOrganizacionLogo']['name'], "ISO-8859-1", "UTF-8");
    $tipo = $_FILES['inOrganizacionLogo']['type'];

    list($ancho, $alto) = getimagesize($_FILES['inOrganizacionLogo']['tmp_name']);

    if (trim($archivo) != "") {

        if (($tipo == "image/png" || $tipo == "image/x-png" || $tipo == "image/jpeg" || $tipo == "image/pjpeg" || $tipo == "image/gif") && ($ancho == 400 && $alto = 200)) {

            //$ruta = "../mantenedores/logos/" . $archivo;
            $error = $_FILES['inOrganizacionLogo']['error'];

            $archivo = str_replace("°", "", $archivo);
            $archivo = str_replace("|", "", $archivo);
            $archivo = str_replace("¬", "", $archivo);
            $archivo = str_replace("!", "", $archivo);
            $archivo = str_replace("#", "", $archivo);
            $archivo = str_replace("$", "", $archivo);
            $archivo = str_replace("%", "", $archivo);
            $archivo = str_replace("&", "", $archivo);
            $archivo = str_replace("/", "", $archivo);
            $archivo = str_replace("(", "", $archivo);
            $archivo = str_replace(")", "", $archivo);
            $archivo = str_replace("=", "", $archivo);
            $archivo = str_replace("?", "", $archivo);
            $archivo = str_replace("\"", "", $archivo);
            $archivo = str_replace("¡", "", $archivo);
            $archivo = str_replace("¿", "", $archivo);
            $archivo = str_replace("¡", "", $archivo);
            $archivo = str_replace("¨", "", $archivo);
            $archivo = str_replace("´", "", $archivo);
            $archivo = str_replace("*", "", $archivo);
            $archivo = str_replace("+", "", $archivo);
            $archivo = str_replace("~", "", $archivo);
            $archivo = str_replace("[", "", $archivo);
            $archivo = str_replace("{", "", $archivo);
            $archivo = str_replace("^", "", $archivo);
            $archivo = str_replace("}", "", $archivo);
            $archivo = str_replace("]", "", $archivo);
            $archivo = str_replace("`", "", $archivo);
            $archivo = str_replace(";", "", $archivo);
            $archivo = str_replace(",", "", $archivo);
            $archivo = str_replace(":", "", $archivo);            

            $organizacionLogo = $archivo;
            $organizacionEstado = "1";
            $resultado = $objOrganizacion->manInsertarOrganizaciones($clienteId, $organizacionNombre, $organizacionLogo, $organizacionDescripcion, $organizacionEstado, $usuarioModificacion);


            $organizacionNombre = str_replace("\'", "'", $organizacionNombre);
            $organizacionNombre = str_replace('\"', '"', $organizacionNombre);

            $organizacionDescripcion = str_replace("\'", "'", $organizacionDescripcion);
            $organizacionDescripcion = str_replace('\"', '"', $organizacionDescripcion);           

            if ($resultado) {
                if ($resultado['estado'] != "0") {

                    $ruta = "../mantenedores/logos/" . $clienteId . "-" . $resultado['estado'] . "-" . $archivo;
                    if ($error == UPLOAD_ERR_OK) {
                        $subido = copy($_FILES['inOrganizacionLogo']['tmp_name'], $ruta);
                    }

                    if ($subido != 1) {
                        ?>                        
                        <script language="JavaScript">       
                            alert("Error al subir el archivo. Inténtelo más tarde.");
                        </script>
                        <?
                    }
                    ?>                        
                    <script language="JavaScript">       
                        location.href = "manOrganizaciones.php";
                    </script>
                    <?
                } else {
                    ?>
                    <script language="JavaScript">       
                        alert("Ya existe una organización con el nombre ingresado.");
                    </script>  
                    <?
                }
            } else {
                echo 'Se produjo un error. Intente nuevamente.';
            }
        } else {

            $organizacionNombre = str_replace("\'", "'", $organizacionNombre);
            $organizacionNombre = str_replace('\"', '"', $organizacionNombre);

            $organizacionDescripcion = str_replace("\'", "'", $organizacionDescripcion);
            $organizacionDescripcion = str_replace('\"', '"', $organizacionDescripcion);
            
            ?>
            <script language="JavaScript">       
                alert("Archivo incorrecto. Sólo se aceptan archivos del tipo PNG, JPEG y GIF (400 x 200 px).");
            </script>
            <?
        }
    } else {

        $organizacionNombre = str_replace("\'", "'", $organizacionNombre);
        $organizacionNombre = str_replace('\"', '"', $organizacionNombre);

        $organizacionDescripcion = str_replace("\'", "'", $organizacionDescripcion);
        $organizacionDescripcion = str_replace('\"', '"', $organizacionDescripcion);      
        ?>
        <script language="JavaScript">       
            alert("No ha seleccionado una imagen.");
        </script>
        <?
    }
}
?>

<script type="text/javascript">
                                                                                                    
    window.addEvent('domready', function() {  
        
        $('oOrganizacion').setStyle('display', 'none');        
    });
    
</script>



<table width="100%">
    <tr>
        <td align="right">
            <a href="manOrganizaciones.php" class="volver">Volver</a>
        </td>
    </tr>    
</table>
<input type="hidden" name="clienteId" id="clienteId" value="<?php echo $clienteId ?>"/>

<h4>Ingresar Organización</h4>
<br>

<div class="divElementos">

<div style="min-height: 400px">
    <div id="contenedor">        

        <div class="divFormulario">

            <form id="frmOrgIngresar" name="frmOrgIngresar" method="post" action="manIngresarOrganizacion.php" enctype="multipart/form-data" onKeyPress="return manDisableEnterKey(event)">


                <table>
                    <tr>
                        <td width="100px" style="padding-bottom:6px">Cliente: </td><td style="padding-bottom:6px"><?php echo htmlentities($clienteNombre) ?></td>                    
                    </tr>
                    <tr>
                        <td width="90px">Nombre <span class="requerido">&nbsp;*</span>:</td><td><input type="text" name="inOrganizacionNombre" id="inOrganizacionNombre" class="campo" style="text-transform:uppercase;" maxlength="50" value= "<?php echo htmlentities($organizacionNombre) ?>" /> <label id="oOrganizacion" style="color: #FF0000; font-size: 12px">Debe ingresar un nombre para la Organizaci&oacute;n</label></td>
                    </tr>
                    <tr>
                        <td>Logo:</td><td><input type="file" name="inOrganizacionLogo" id="inOrganizacionLogo" class="campo" value= "<?php echo htmlentities($organizacionLogo) ?>" /></td>
                    </tr>
                    <tr>
                        <td>Descripción:</td><td><input type="text" name="inOrganizacionDescripcion" id="inOrganizacionDescripcion" class="campoDescripcion" maxlength="250" value= "<?php echo htmlentities($organizacionDescripcion) ?>" /></td>
                    </tr>

                </table>
        </div>
        <br>
        <p>    
            <br>
            <input type="button" name="button" value="Ingresar" class="btn azul" onclick="manValidarFormularioOrganizaciones('inOrganizacionNombre', 'btIngresarOrg')" />                                
            <label></label>                                
            <input type="button" name="cancelar" id="cancelar" value="Cancelar" class="btn" onclick="location.href = 'manOrganizaciones.php'" />
            <input type="submit" name="submit" id="btIngresarOrg" value="Ingresar" style="display: none" />
        </p>

        </form>

    </div>
</div>
<br>

</div>

<?
mssql_free_result($consulta);


$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>



