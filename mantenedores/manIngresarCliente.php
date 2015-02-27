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

if ($usuarioModificacion == '') {
    echo "<script>window.location='../';</script>";
}


if (isset($_POST['submit'])) {
    require('clases/clientes.class.php');
    $objCliente = new clientes();

    $clienteNombres = mb_convert_encoding(trim($_POST['inCliClienteNombres']), "ISO-8859-1", "UTF-8");
    $clienteApellidos = mb_convert_encoding(trim($_POST['inCliClienteApellidos']), "ISO-8859-1", "UTF-8");
    $clienteEmail = mb_convert_encoding(trim($_POST['inCliClienteEmail']), "ISO-8859-1", "UTF-8");
    $clienteDireccion = mb_convert_encoding(trim($_POST['inCliClienteDireccion']), "ISO-8859-1", "UTF-8");
    $clienteFono = mb_convert_encoding(trim($_POST['inCliClienteFono']), "ISO-8859-1", "UTF-8");
    $clienteMultimedia = mb_convert_encoding(trim($_POST['inCliClienteMultimedia']), "ISO-8859-1", "UTF-8");
    $clienteEstado = "1";

    $resultado = $objCliente->manInsertarCliente($clienteNombres, $clienteApellidos, $clienteEmail, $clienteDireccion, $clienteFono, $clienteMultimedia, $clienteEstado, $usuarioModificacion);

    $clienteNombres = str_replace("\'", "'", $clienteNombres);
    $clienteNombres = str_replace('\"', '"', $clienteNombres);

    $clienteApellidos = str_replace("\'", "'", $clienteApellidos);
    $clienteApellidos = str_replace('\"', '"', $clienteApellidos);

    $clienteEmail = str_replace("\'", "'", $clienteEmail);
    $clienteEmail = str_replace('\"', '"', $clienteEmail);

    $clienteDireccion = str_replace("\'", "'", $clienteDireccion);
    $clienteDireccion = str_replace('\"', '"', $clienteDireccion);

    $clienteFono = str_replace("\'", "'", $clienteFono);
    $clienteFono = str_replace('\"', '"', $clienteFono);


    if ($resultado) {
        if ($resultado['estado'] == "1") {
            ?>                        
            <script language="JavaScript">       
                location.href = "manClientes.php";
            </script>
            <?
        } else if ($resultado['estado'] == "0") {
            ?>                                                            
            <script language="JavaScript">       
                alert("Ya existe el cliente con los datos ingresados.");
            </script>                        
            <?
        } else {
            echo 'Se produjo un error. Intente nuevamente.';
        }
    } else {
        echo 'Se produjo un error. Intente nuevamente.';
    }
} else {
    $clienteMultimedia = "No";
}
?>

<script type="text/javascript">
                                                                                                    
    window.addEvent('domready', function() {  
        
        $('oNombres').setStyle('display', 'none');
        $('oApellidos').setStyle('display', 'none');        
        $('oEmail').setStyle('display', 'none'); 
        $('oEmail2').setStyle('display', 'none'); 
                
    });
    
</script>            


<table width="100%">
    <tr>
        <td align="right">
            <a href="manClientes.php" class="volver">Volver</a>
        </td>
    </tr>    
</table>
<input type="hidden" name="clienteId" id="clienteId" value="<?php echo htmlentities($clienteId) ?>"/>

<h4>Ingresar Cliente</h4>
<br>

<div class="divElementos">

    <div style="min-height: 400px">
        <div id="contenedor">

            <div class="divFormulario">

                <form id="frmCliIngresar" name="frmCliIngresar" method="post" action="manIngresarCliente.php" onKeyPress="return manDisableEnterKey(event)">

                    <input type="hidden" name="clienteId" id="clienteId" value="<?php echo htmlentities($clienteId) ?>"/>                            

                    <table>                      
                        <tr>
                            <td width="100px">Nombres:</td><td><input type="text" name="inCliClienteNombres" id="inCliClienteNombres" class="campo" style="text-transform:uppercase;" maxlength="50" value= "<?php echo htmlentities($clienteNombres) ?>" /> <label id="oNombres" style="color: #FF0000; font-size: 12px">(*)</label></td>
                        </tr>
                        <tr>
                            <td>Apellidos:</td><td><input type="text" name="inCliClienteApellidos" id="inCliClienteApellidos" class="campo" style="text-transform:uppercase;" maxlength="50" value= "<?php echo htmlentities($clienteApellidos) ?>" /> <label id="oApellidos" style="color: #FF0000; font-size: 12px">(*)</label></td>
                        </tr>
                        <tr>
                            <td>Email:</td><td><input type="text" name="inCliClienteEmail" id="inCliClienteEmail" class="campo" maxlength="50" value= "<?php echo htmlentities($clienteEmail) ?>" /> <label id="oEmail" style="color: #FF0000; font-size: 12px">(*)</label> <label id="oEmail2" style="color: #FF0000; font-size: 12px">Formato incorrecto.</label></td>
                        </tr>
                        <tr>
                            <td>Fono:</td><td><input type="text" name="inCliClienteFono" id="inCliClienteFono" class="campo" maxlength="50" value= "<?php echo htmlentities($clienteFono) ?>" /></td>
                        </tr>
                        <tr>
                            <td>Dirección:</td><td><input type="text" name="inCliClienteDireccion" id="inCliClienteDireccion" class="campoDescripcion" maxlength="250" value= "<?php echo htmlentities($clienteDireccion) ?>" /></td>
                        </tr>
                        <tr>
                            <td>Multimedia:</td>
                            <td>

                                <input type="radio" name="inCliClienteMultimedia" id="estado_1" value="Si" <?php if (htmlentities($clienteMultimedia) == "Si") echo "checked=\"checked\"" ?> />Si
                                <input type="radio" name="inCliClienteMultimedia" id="estado_0" value="No" <?php if (htmlentities($clienteMultimedia) == "No") echo "checked=\"checked\"" ?> />No                                                           
                            </td>
                        </tr>

                    </table>                                                                                                
            </div> 
            <p>   
                <br>
                <input type="button" name="button" value="Ingresar" class="btn azul" onclick="manValidarFormularioClientes('inCliClienteNombres', 'inCliClienteApellidos', 'inCliClienteEmail','btIngresarCli')" />                                
                <label></label>                                
                <input type="button" name="cancelar" id="cancelar" value="Cancelar" class="btn" onclick="location.href = 'manClientes.php'" />
                <input type="submit" name="submit" id="btIngresarCli" value="Actualizar" style="display: none" /> 
            </p>

            </form>

        </div>
    </div>

</div>

<?
$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>