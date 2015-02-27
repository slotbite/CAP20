<?php
session_start();

include("../default.php");
$clienteId = $_SESSION['clienteId'];
$administradorId = $_SESSION['administradorId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}

require('clases/organizaciones.class.php');
$objOrganizacion = new organizaciones();

$organizacionId = htmlspecialchars(trim($_GET['organizacionId']));


$consulta = $objOrganizacion->manBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta);

$consulta = $objOrganizacion->manMostrarOrganizacion($organizacionId, $clienteId);
$organizacion = mssql_fetch_array($consulta);

$consulta = $objOrganizacion->manBuscarPersonalizacionOrganizacion($organizacionId, $clienteId);
$personalizacionData = mssql_fetch_array($consulta);
    
    
    $mensajeCorreo = $mensajeCorreo . "<table border='0' style='border:1px solid #4B6C9F; ' CELLSPACING='0' CELLSPADDING='0'><tr>";
    $mensajeCorreo = $mensajeCorreo . "<td width='450px' style='background-color:#4B6C9F; color:white; vertical-align:middle; border-bottom:1px solid #4B6C9F; text-align:center; height:75px;'>";
    $mensajeCorreo = $mensajeCorreo . "<span style='font-weight:bolder;font-size:16px;'>CÁPSULAS DE CONOCIMIENTO</span></td>";
    $mensajeCorreo = $mensajeCorreo . "<td width='150px' style='border-bottom:1px solid #4B6C9F; height:75px;' align='center'><img src='" . trim(mb_convert_encoding(($organizacion['organizacionLogo']), "UTF-8", "ISO-8859-1")) . "' border='0' width='100px'> </td></tr>";       
    $mensajeCorreo = $mensajeCorreo . "<tr><td colspan='2' style='background-color:#ffffff;'>&nbsp;</td></tr>";
    $mensajeCorreo = $mensajeCorreo . "<tr><td colspan='2' style='padding-left:30px;padding-right:30px;text-align:justify;word-wrap: break-word;'>";
    $mensajeCorreo = $mensajeCorreo . "Estimado(a):<br><b>             </b><br><br>";
    $mensajeCorreo = $mensajeCorreo . mb_convert_encoding(($personalizacionData['encabezado']), "UTF-8", "ISO-8859-1") . "<br><br>";
    $mensajeCorreo = $mensajeCorreo . "Para acceder a la cápsula haga click <a href='#'>Aqui</a>";
    $mensajeCorreo = $mensajeCorreo . "<br><br>";
    $mensajeCorreo = $mensajeCorreo . mb_convert_encoding(($personalizacionData['footer']), "UTF-8", "ISO-8859-1");
    $mensajeCorreo = $mensajeCorreo . "</td></tr><tr><td colspan='2' style='background-color:#ffffff;'>&nbsp;</td></tr><tr><td colspan='2' style='background-color:#4B6C9F;'>&nbsp;</td></tr></table>";                    
    
?>      

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />                   
    </head>

    <title>Cápsulas de Conocimiento</title>

    <link rel="stylesheet" type="text/css" href="../skins/saam/plantillas/style.css" media="screen" />

    <html>

        <body>

            <div style="background-color: #fff; width: 990px; min-height: 490px; text-align: left; padding: 5px;">

                <h3>Organización</h3>

                <table style="font: 11px Lucida Sans Unicode" width="990px">  
                    <tr>                    
                        <td width="100px" display="border: none">Cliente: </td><td display="border: none"><?php echo htmlentities($cliente['clienteNombreCompleto']) ?></td>                    
                    </tr>                    
                    <tr>
                        <td width="100px" display="border: none">Organización: </td><td display="border: none"><?php echo htmlentities($organizacion['organizacionNombre']) ?></td>                    
                    </tr>
                    <tr>
                        <td width="100px" display="border: none">Descripción: </td><td display="border: none"><?php echo htmlentities($organizacion['organizacionDescripcion']) ?></td>                    
                    </tr>                
                </table>          

                <br>
                <br>

                <h3>Personalización de E-mail</h3>

                <table style="font: 11px Lucida Sans Unicode" width="990px">  
                    <tr>                    
                        <td width="100px" display="border: none">Asunto: </td><td display="border: none"><?php echo htmlentities($personalizacionData['subject']) ?></td>                    
                    </tr>                                                       
                </table>


                <br>

                <div width="200px">
                
                <?
                echo $mensajeCorreo;
                ?>

                </div>
            </div>







        </body>

    </html>








