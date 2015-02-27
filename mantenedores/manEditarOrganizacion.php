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

    $organizacionId = mb_convert_encoding(trim($_POST['organizacionId']), "ISO-8859-1", "UTF-8");
    $organizacionEstado = mb_convert_encoding(trim($_POST['organizacionEstado']), "ISO-8859-1", "UTF-8");
    $organizacionNombre = mb_convert_encoding(trim($_POST['updOrganizacionNombre']), "ISO-8859-1", "UTF-8");
    $organizacionDescripcion = mb_convert_encoding(trim($_POST['updOrganizacionDescripcion']), "ISO-8859-1", "UTF-8");    

    $archivo = mb_convert_encoding($_FILES['updOrganizacionLogo']['name'], "ISO-8859-1", "UTF-8");
    $tipo = $_FILES['updOrganizacionLogo']['type'];

    list($ancho, $alto) = getimagesize($_FILES['updOrganizacionLogo']['tmp_name']);

    if (trim($archivo) != "") {

        if (($tipo == "image/png" || $tipo == "image/x-png" || $tipo == "image/jpeg" || $tipo == "image/pjpeg" || $tipo == "image/gif") && ($ancho == 400 && $alto = 200)) {

            $consulta = $objOrganizacion->manMostrarOrganizacion($organizacionId, $clienteId);
            $resultado = mssql_fetch_array($consulta);

            $ruta_anterior = $resultado['organizacionLogo'];

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

            $edicion = "SI";
        } else {
            $edicion = "NO";
            ?>
            <script language="JavaScript">
                alert("Archivo incorrecto. Sólo se aceptan archivos del tipo PNG, JPEG y GIF (400 x 200 px).");
            </script>
            <?
        }
    } else {
        $organizacionLogo = "";
    }

    if ($edicion != "NO") {

        $resultado = $objOrganizacion->manEditarOrganizaciones($clienteId, $organizacionId, $organizacionNombre, $organizacionLogo, $organizacionDescripcion, $organizacionEstado, $usuarioModificacion);

        if ($resultado) {
            $estado = $resultado['estado'];

            if ($estado == "1") {



                if ($organizacionLogo != "") {

                    $ruta = "../mantenedores/logos/" . $clienteId . "-" . $organizacionId . "-" . $archivo;

                    if (file_exists($ruta_anterior)) {
                        unlink($ruta_anterior);
                    }

                    if ($error == UPLOAD_ERR_OK) {
                        $subido = copy($_FILES['updOrganizacionLogo']['tmp_name'], $ruta);
                    }
                } else {
                    $subido = 1;
                    $consulta = $objOrganizacion->manMostrarOrganizacion($organizacionId, $clienteId);

                    if ($consulta) {

                        $organizacion = mssql_fetch_array($consulta);
                        $organizacionLogo = $organizacion['organizacionLogo'];
                    }
                }

                if ($subido != 1) {
                    ?>
                    <script language="JavaScript">
                        alert("Error al subir el archivo. Inténtelo más tarde.");
                    </script>
                    <?
                } else {
                    ?>
                    <script language="JavaScript">
                        location.href = "manOrganizaciones.php";
                    </script>
                    <?
                }
            } else {

                $consulta = $objOrganizacion->manMostrarOrganizacion($organizacionId, $clienteId);

                if ($consulta) {

                    $organizacion = mssql_fetch_array($consulta);
                    $organizacionLogo = $organizacion['organizacionLogo'];
                }
                ?>

                <script language="JavaScript">
                    alert("Ya existe una organización con el nombre ingresado.");
                </script>
                <?
            }
        } else {
            echo 'Se produjo un error. Intente nuevamente';
        }
    }

    $consulta = $objOrganizacion->manMostrarOrganizacion($organizacionId, $clienteId);

    if ($consulta) {

        $organizacion = mssql_fetch_array($consulta);
        $organizacionLogo = $organizacion['organizacionLogo'];
    }

    $organizacionNombre = str_replace("\'", "'", $organizacionNombre);
    $organizacionNombre = str_replace('\"', '"', $organizacionNombre);

    $organizacionDescripcion = str_replace("\'", "'", $organizacionDescripcion);
    $organizacionDescripcion = str_replace('\"', '"', $organizacionDescripcion);
    
} else {

    $organizacionId = htmlspecialchars(trim($_POST['organizacionId']));

    $consulta = $objOrganizacion->manMostrarOrganizacion($organizacionId, $clienteId);

    if ($consulta) {

        $organizacion = mssql_fetch_array($consulta);

        $organizacionNombre = $organizacion['organizacionNombre'];
        $organizacionLogo = $organizacion['organizacionLogo'];
        $organizacionDescripcion = $organizacion['organizacionDescripcion'];
        $organizacionEstado = $organizacion['organizacionEstado'];
       
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

<h4>Editar Organización</h4>
<br>

<div class="divElementos">

<div style="min-height: 400px">
    <div id="contenedor">

        <div class="divFormulario">

            <form id="frmOrgEditar" name="frmOrgEditar" method="post" action="manEditarOrganizacion.php" enctype="multipart/form-data" onKeyPress="return manDisableEnterKey(event)">

                <input type="hidden" name="organizacionId" id="organizacionId" value="<?php echo $organizacionId ?>"/>
                <input type="hidden" name="organizacionEstado" id="organizacionEstado" value="<?php echo $organizacionEstado ?>"/>

                <table>
                    <tr>
                        <td width="100px" style="padding-bottom:6px">Cliente: </td><td style="padding-bottom:6px"><?php echo htmlentities($clienteNombre) ?></td>
                    </tr>
                    <tr>
                        <td width="90px">Nombre <span class="requerido">&nbsp;*</span>:</td><td><input type="text" name="updOrganizacionNombre" id="updOrganizacionNombre" class="campo" style="text-transform:uppercase;" maxlength="50" value= "<?php echo htmlentities($organizacionNombre) ?>" /> <label id="oOrganizacion" style="color: #FF0000; font-size: 12px">Debe ingresar un nombre para la Organizaci&oacute;n</label></td>
                    </tr>
                    <tr>
                        <td valign="top">Logo:</td><td><input type="file" name="updOrganizacionLogo" id="updOrganizacionLogo" class="campo" value= "<?php echo htmlentities($organizacionLogo) ?>" />
                            <br><br>
                            <?
                            if (trim(htmlentities($organizacion['organizacionLogo'])) != "") {
                                ?>
                                <img src="<?php echo htmlentities($organizacion['organizacionLogo']) ?>" alt="Logo" width="200" height="100" /></a>
                                <?
                            }
                            ?>
                            <br><br>
                        </td>
                    </tr>
                    <tr>
                        <td>Descripción:</td><td><input type="text" name="updOrganizacionDescripcion" id="updOrganizacionDescripcion" class="campoDescripcion" maxlength="250" value= "<?php echo htmlentities($organizacionDescripcion) ?>" /></td>
                    </tr>

                </table>

        </div>
        <br>
        
            <p> 
                <br>
                <input type="button" name="button" value="Guardar" class="btn azul" onclick="manValidarFormularioOrganizacionesEdicion('updOrganizacionNombre', 'btEditarOrg')" />
                <label></label>
                <input type="button" name="cancelar" id="cancelar" value="Cancelar" class="btn" onclick="location.href = 'manOrganizaciones.php'" />
                <input type="submit" name="submit" id="btEditarOrg" value="Ingresar" style="display: none" />
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
