<?
session_start();
include("../default.php");

$nusuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente = $_SESSION['clienteId'] ? $_SESSION['clienteId'] : 0;
$tema = $_GET['tema'] ? $_GET['tema'] : 0;

$logo = "../images/EnvioPrueba.jpg";

if ($tema != 0) {

    $query = "EXEC CapTraeTema " . $tema . "," . $cliente . "";
    $result = $base_datos->sql_query($query);
    $row = $base_datos->sql_fetch_assoc($result);
    $ntema = $row['temaNombre'] ? $row['temaNombre'] : '';
    $dtema = $row['temaDescripcion'] ? $row['temaDescripcion'] : '';
    $itema = $row['temaImagen'] ? $row['temaImagen'] : ' ';

    $queryL = "Select LTRIM(RTRIM(clienteNombres)) + ' ' + LTRIM(RTRIM(clienteApellidos)) as 'clienteNombreCompleto'
                        From Clientes c (nolock)                        
                        Where c.clienteId = " . $cliente . "";


    $resultL = $base_datos->sql_query($queryL);
    $rowL = $base_datos->sql_fetch_assoc($resultL);
    $clienteNombre = $rowL['clienteNombreCompleto'] ? $rowL['clienteNombreCompleto'] : '';


    $queryPersonalizacion = "EXEC capListarPersonalizacion " . $clienteId . "," . $tema;


    $personalizacion = $base_datos->sql_query($queryPersonalizacion);
    $personalizacionData = $base_datos->sql_fetch_assoc($personalizacion);

    $encabezado = htmlentities($personalizacionData['encabezado']) ? htmlentities($personalizacionData['encabezado']) : '';
    $footer = htmlentities($personalizacionData['footer']) ? htmlentities($personalizacionData['footer']) : '';
    $subject = $personalizacionData['subject'] ? $personalizacionData['subject'] : '';

    $mensajeCorreo = "";


    $mensajeCorreo = $mensajeCorreo . "<table border='0' style='border:1px solid #4B6C9F;width:600px;' CELLSPACING='0' CELLSPADDING='0'><tr>";

    if ($itema == " ") {
        $mensajeCorreo = $mensajeCorreo . "<td style='background-color:#4B6C9F;color:white;vertical-align:middle;width:450px;border-bottom:1px solid #4B6C9F;text-align:center;height:75px;' height='75px'>";
        $mensajeCorreo = $mensajeCorreo . "<span style='font-weight:bolder;font-size:16px;'> </span></td>";
    } else {
        $mensajeCorreo = $mensajeCorreo . "<td style='background-color:#FFFFFF; border-right: 1px solid #4B6C9F; color:white;vertical-align:middle;width:450px;border-bottom:1px solid #4B6C9F;text-align:center;height:75px;' height='75px'>";
        $mensajeCorreo = $mensajeCorreo . "<img src='.." . $itema . "' border='0' width='100%'></td>";
    }

    $mensajeCorreo = $mensajeCorreo . "<td style='border-bottom:1px solid #4B6C9F;width:150px;height:75px;' align='center'><img src='" . $logo . "' border='0' width='75%' height='75%'> </td></tr>";
    $mensajeCorreo = $mensajeCorreo . "<tr><td colspan='2' style='background-color:#ffffff;'>&nbsp;</td></tr>";
    $mensajeCorreo = $mensajeCorreo . "<tr><td colspan='2' style='padding-left:30px;padding-right:30px;text-align:justify;word-wrap: break-word;'>";
    $mensajeCorreo = $mensajeCorreo . "Estimado(a):<br><b>Nombre Usuario</b><br><br>";
    $mensajeCorreo = $mensajeCorreo . stripslashes(mb_convert_encoding($encabezado, "ISO-8859-1", "UTF-8")) . "<br><br>";
    $mensajeCorreo = $mensajeCorreo . "Para acceder a la cápsula haga click <a href='#'>Aqui</a>";
    $mensajeCorreo = $mensajeCorreo . "<br><br>";
    $mensajeCorreo = $mensajeCorreo . stripslashes(mb_convert_encoding($footer, "ISO-8859-1", "UTF-8"));
    $mensajeCorreo = $mensajeCorreo . "</td></tr><tr><td colspan='2' style='background-color:#ffffff;'>&nbsp;</td></tr><tr><td colspan='2' style='background-color:#4B6C9F;'>&nbsp;</td></tr></table>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    
    
    <style>
        
        .tablaTema{
            border:1px solid #4B6C9F; 
            margin:0px;
            border-collapse:collapse;    
        }


        .tdCabeceraTemaA{
            background-color:#4B6C9F;
            color:white;
            vertical-align:middle;
            width:700px;
            border-bottom:1px solid #4B6C9F;
            text-align:center;
            height:150px;   
        }

        .tdCabeceraTemaB{
            width:700px;
            height:150px;
            border:1px solid #4B6C9F;
            padding: 0px;   
        }


        
    </style>
    

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <META HTTP-EQUIV="Expires" CONTENT="Mon, 26 Jul 1980 05:00:00 GMT">
            <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
                <meta http-equiv="expires" content="0">
                    <title>C&aacute;psulas de Conocimiento</title>
                    <link rel="stylesheet" type="text/css" href="../skins/saam/plantillas/style.css" media="screen" />
                    </head>

                    <body style="background-color: #fff; width: 1130px; height: auto">

                        <div style="width: 916px; text-align: left; padding: 5px;">

                            <table border="0" align="left" style="text-align:left;">
                                <tr>
                                    <td colspan="2">
                                        <h4>Ver Tema</h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="width:100px;">Cliente</td>
                                    <td><? echo $clienteNombre; ?></td>
                                </tr>
                                <tr>
                                    <td>
                                        Tema
                                    </td>
                                    <td>
                                        <? echo htmlentities($ntema); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Descripci&oacute;n
                                    </td>
                                    <td>
                                        <? echo htmlentities($dtema); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <h3>Vista Previa Encabezado</h3>                    
                                    </td>
                                </tr>                                
                                <tr>
                                    <td colspan="2">
                                        <table class="tablaTema" CELLSPACING='0' CELLSPADDING='0'>
                                            <tr>
                                                
                                                <? if(trim($itema) == ""){ ?>                                                
                                                <td id="tdCabeceraTema1" class="tdCabeceraTemaA">
                                                    <span style='font-weight:bolder;font-size:16px;'>C&Aacute;PSULAS DE CONOCIMIENTO</span>
                                                </td>
                                                <?}
                                                else{?>
                                                <td id="tdCabeceraTema2" class="tdCabeceraTemaB">
                                                    <img id="temaUrl" src='..<? echo $itema; ?>' border='0' height='150px' width="700px" alt="<? echo htmlentities($ntema) ?>" title="<? echo htmlentities($ntema) ?>"/>
                                                </td>
                                                <?}?>
                                                <td style='width:250px;border:1px solid #4B6C9F;' align='center'>
                                                    <img src='../css/imagenes/headerFooter/logo.png' border='0'>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 100px" colspan="2" align="left">
                                                    <h3>Bienvenido(a)</h3>
                                                    <b>Nombre de usuario</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td id="tdTituloCapsula" align="center" colspan="2">
                                                    <h1>NOMBRE CÁPSULA</h1>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>                                                                        
                                </tr>
                                <tr>
                                    <td colspan="2" valign="top" align="left">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <h3>Vista Previa Email</h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">Asunto: Nombre Cápsula <? echo htmlentities($subject) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <br>
                                            <? echo $mensajeCorreo; ?>
                                    </td>
                                </tr>

                            </table>

                        </div>

                    </body>
                    </html>

