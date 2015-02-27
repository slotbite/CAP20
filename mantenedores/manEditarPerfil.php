<?php
session_start();

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

require('clases/perfiles.class.php');
$objPerfil = new perfiles();

if (isset($_POST['submit'])) {

    $perfilId = mb_convert_encoding(trim($_POST['perfilId']), "ISO-8859-1", "UTF-8");
    $perfilEstado = mb_convert_encoding(trim($_POST['perfilEstado']), "ISO-8859-1", "UTF-8");
    $perfilNombre = mb_convert_encoding(trim($_POST['updPerPerfilNombre']), "ISO-8859-1", "UTF-8");
    $perfilDescripcion = mb_convert_encoding(trim($_POST['updPerPerfilDescripcion']), "ISO-8859-1", "UTF-8");

    $resultado = $objPerfil->manEditarPerfil($clienteId, $perfilId, $perfilNombre, $perfilDescripcion, $perfilEstado, $usuarioModificacion);

    $perfilNombre = str_replace("\'", "'", $perfilNombre);
    $perfilNombre = str_replace('\"', '"', $perfilNombre);

    $perfilDescripcion = str_replace("\'", "'", $perfilDescripcion);
    $perfilDescripcion = str_replace('\"', '"', $perfilDescripcion);

    if ($resultado) {
        if ($resultado['estado'] == "1") {
            ?>                        
            <script language="JavaScript">       
                location.href = "manPerfiles.php";
            </script>
            <?
        } else if ($resultado['estado'] == "0") {
            ?>                                                            
            <script language="JavaScript">       
                alert("Ya existe el perfil ingresado.");
            </script>                        
            <?
        } else {
            echo 'Se produjo un error. Intente nuevamente.';
        }
    } else {
        echo 'Se produjo un error. Intente nuevamente.';
    }
} else {

    $perfilId = htmlspecialchars(trim($_POST['perfilId']));

    $consulta = $objPerfil->manMostrarPerfil($perfilId);

    if ($consulta) {

        $perfil = mssql_fetch_array($consulta);

        $perfilNombre = $perfil['perfilNombre'];
        $perfilDescripcion = $perfil['perfilDescripcion'];
        $perfilEstado = $perfil['perfilEstado'];
    }
}
?>

<script type="text/javascript">
                                                                                                    
    window.addEvent('domready', function() {  
        
        $('oPerfil').setStyle('display', 'none');        
    });
    
</script> 



<table width="100%">
    <tr>
        <td align="right">
            <a href="manPerfiles.php" class="volver">Volver</a>
        </td>
    </tr>    
</table>
<input type="hidden" name="clienteId" id="clienteId" value="<?php echo htmlentities($clienteId) ?>"/>

<h4>Editar Perfil</h4>
<br>

<div class="divElementos">

<div style="min-height: 400px">
    <div id="contenedor">

        <div class="divFormulario">

            <form id="frmPerEditar" name="frmPerEditar" method="post" action="manEditarPerfil.php" onKeyPress="return manDisableEnterKey(event)">

                <input type="hidden" name="perfilId" id="perfilId" value="<?php echo htmlentities($perfilId) ?>"/>
                <input type="hidden" name="perfilEstado" id="perfilEstado" value="<?php echo htmlentities($perfilEstado) ?>"/>

                <table>                    
                    <tr>
                        <td width="100px">Perfil:</td><td><input type="text" name="updPerPerfilNombre" id="updPerPerfilNombre" class="campo" style="text-transform:uppercase;" maxlength="50" value= "<?php echo htmlentities($perfilNombre) ?>" /> <label id="oPerfil" style="color: #FF0000; font-size: 12px">(*)</label></td>
                    </tr>
                    <tr>
                        <td>Descripción:</td><td><input type="text" name="updPerPerfilDescripcion" id="updPerPerfilDescripcion" class="campoDescripcion" maxlength="250" value= "<?php echo htmlentities($perfilDescripcion) ?>" /></td>
                    </tr>

                </table>                               
        </div>
        <p>  
            <br>
            <input type="button" name="button" value="Guardar" class="btn azul" onclick="manValidarFormularioPerfilesEdicion('updPerPerfilNombre','btEditarPer')" />                                
            <label></label>                                
            <input type="button" name="cancelar" id="cancelar" value="Cancelar" class="btn" onclick="location.href = 'manPerfiles.php'" />
            <input type="submit" name="submit" id="btEditarPer" value="Ingresar" style="display: none" />
        </p>

        </form>

    </div>
</div>
    
</div>   
 
<?
$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>

