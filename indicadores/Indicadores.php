<?

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

$usuario_id = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';

if ($usuario_id == '') {
    echo "<script>window.location='../index.php';</script>";
    ?>
    <?

} else {
    $plantilla->setTemplate("menu2");
    $fecha = date("d-m-Y");
    $plantilla->setVars(array("USUARIO" => "$usuario_id",
        "FECHA" => "$fecha"
    ));

    echo $plantilla->show();
                
}
?>


<div class="mainC">
    <table width="100%">
        <tr>
            <td align="right">
                <a href="../reportes/adm_reportes.php" class="volver" >Volver</a>
            </td>
        </tr>    
    </table>
    <table border="0" style="padding-top: 50px">
        <tr valign="middle">
            <td style="width:100px;">
                
            </td>
            <td style="width:70px;">
                <img src="indicadores.png" alt="" style="width: 50px"/>
            </td>
            <td style="width:400px;">
                <a href="../indicadores/IndicadoresCalcular.php">Recalcular Indicadores</a>
                <br/>Cálculo de los indicadores, visibles en el SGH.
            </td>
        </tr>
        <tr>
            <td>
                <br/>
            </td>
        </tr>
        <tr valign="middle">
            <td>
                
            </td>
            <td>
                <img src="usuariosInactivos.png" alt="" style="width: 50px"/>
            </td>
            <td>
                <a href="../indicadores/registrosUsuariosInactivos.php">Usuarios inactivos</a>
                <br/>Eliminación de registros de envios asociados a usuarios inactivos.
            </td>
        </tr>
        <tr>
            <td>
                <br/>
            </td>
        </tr>
        <tr valign="middle">
            <td>
                
            </td>
            <td>
                <img src="emails.png" alt="" style="width: 50px"/>
            </td>
            <td style="width:300px;">
                <a href="../indicadores/registrosDuplicados.php">Envios repetidos</a>
                <br/>Eliminación de envíos repetidos.
            </td>
        </tr>
    </table>    
</div>



<?
$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>

