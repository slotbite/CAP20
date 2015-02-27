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

require('clases/cargos.class.php');
$objCargo = new cargos();

$consulta = $objCargo->manBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta);
$clienteNombre = $cliente['clienteNombreCompleto'];


if (isset($_POST['submit'])) {

    $cargoNombre = mb_convert_encoding(trim($_POST['inCarCargoNombre']), "ISO-8859-1", "UTF-8");
    $cargoDescripcion = mb_convert_encoding(trim($_POST['inCarCargoDescripcion']), "ISO-8859-1", "UTF-8");
    $cargoEstado = "1";

    $resultado = $objCargo->manInsertarCargo($clienteId, $cargoNombre, $cargoDescripcion, $cargoEstado, $usuarioModificacion);

    $cargoNombre = str_replace("\'", "'", $cargoNombre);
    $cargoNombre = str_replace('\"', '"', $cargoNombre);

    $cargoDescripcion = str_replace("\'", "'", $cargoDescripcion);
    $cargoDescripcion = str_replace('\"', '"', $cargoDescripcion);


    if ($resultado) {
        if ($resultado['estado'] == "1") {
            ?>                        
            <script language="JavaScript">       
                location.href = "manCargos.php";
            </script>
            <?
        } else if ($resultado['estado'] == "0") {
            ?>                                                            
            <script language="JavaScript">       
                alert("El cargo ingresado ya existe.");
            </script>                        
            <?
        } else {
            echo 'Se produjo un error. Intente nuevamente.';
        }
    } else {
        echo 'Se produjo un error. Intente nuevamente.';
    }
}
?>

<script type="text/javascript">
                                                                                                    
    window.addEvent('domready', function() {  
        
        $('oCargo').setStyle('display', 'none');               
    });
    
</script>



<table width="100%">
    <tr>
        <td align="right">
            <a href="manCargos.php" class="volver">Volver</a>
        </td>
    </tr>    
</table>
<input type="hidden" name="clienteId" id="clienteId" value="<?php echo htmlentities($clienteId) ?>"/>

<h4>Ingresar Cargo</h4>
<br>

<div class="divElementos">

<div style="min-height: 400px">
    <div id="contenedor">

        <div class="divFormulario">

            <form id="frmCarIngresar" name="frmCarIngresar" method="post" action="manIngresarCargo.php" onKeyPress="return manDisableEnterKey(event)">


                <table>
                    <tr>
                        <td width="100px" style="padding-bottom:6px">Cliente: </td><td style="padding-bottom:6px"><?php echo htmlentities($clienteNombre) ?></td>                    
                    </tr>
                    <tr>
                        <td>Cargo <span class="requerido">&nbsp;*</span>:</td><td><input type="text" name="inCarCargoNombre" id="inCarCargoNombre" class="campo" style="text-transform:uppercase;" maxlength="50" value= "<?php echo htmlentities($cargoNombre) ?>" /> <label id="oCargo" style="color: #FF0000; font-size: 12px">Debe ingresar un nombre para el Cargo</label></td>
                    </tr>                    
                    <tr>
                        <td>Descripción:</td><td><input type="text" name="inCarCargoDescripcion" id="inCarCargoDescripcion" class="campoDescripcion" maxlength="250" value= "<?php echo htmlentities($cargoDescripcion) ?>" /></td>
                    </tr>

                </table>
        </div>                                     
        <p>     
            <br>
            <input type="button" name="button" value="Ingresar" class="btn azul" onclick="manValidarFormularioCargos('inCarCargoNombre','btIngresarCar')" />                                
            <label></label>                                
            <input type="button" name="cancelar" id="cancelar" value="Cancelar" class="btn" onclick="location.href = 'manCargos.php'" />
            <input type="submit" name="submit" id="btIngresarCar" value="Ingresar" style="display: none" />
        </p>

        </form>

    </div>
</div>
    
</div>
    
<?
mssql_free_result($consulta);

$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>