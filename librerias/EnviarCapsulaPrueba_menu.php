<?

session_start();
include ("../librerias/conexion.php");
include ("../librerias/crypt.php");
include ("../librerias/config.php");
include ("../librerias/funciones_correo.php");

$nusuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] : 0;
$capsula_id = $_POST['capsula_id'] ? $_POST['capsula_id'] : 0;
$mailenvio = $_POST['mailenvio'] ? $_POST['mailenvio'] : '';
//$capsulaVersion = $_SESSION['capsulaVersion2']? $_SESSION['capsulaVersion2']:0;


if ($capsula_id != 0) {

    $query1 = "select capsulaTipo,MAX(capsulaVersion) as version from capsulas where capsulaId=$capsula_id  group by capsulaTipo";
    $result1 = $base_datos->sql_query($query1);
    $row1 = $base_datos->sql_fetch_assoc($result1);

    //echo $query1;

    $capsulaVersion = $row1['version'] ? $row1['version'] : 1;
    $tipo = $row1['capsulaTipo'];
    
    $usuarioNombre = "USUARIO DE PRUEBA";
    $organizacionNombre = "Cápsula de prueba";
    $organizacionLogo = "#";

    $queryCuestionario="EXEC envCargaTablaCuestionarioPrueba ".$capsula_id.",".$capsulaVersion.",'".$mailenvio."','".$nusuario."'";
    $resultInsert = $base_datos->sql_query($queryCuestionario);
    $rowDev	= $base_datos->sql_fetch_assoc($resultInsert);
    $envioId = $rowDev["envioId"];
    
    
    $real_message = "capsula_id=" . $capsula_id . "&version=" . $capsulaVersion . "&envioid=" . $envioId . "&tipo=" . $tipo;


    $edata2 = enc_dec($real_message);

    $link = $urlprueba . "hash=" . urlencode($edata2);

    
    $queryPersonalizacion = "   Select top 1    
                                        CASE WHEN c.capsulaNumero is NULL or LTRIM(RTRIM(c.capsulaNumero)) = '' THEN c.capsulaNombre ELSE LTRIM(RTRIM(c.capsulaNumero)) + '.- ' + c.capsulaNombre END as capsulaNombre, 
                                        t.temaImagen, 
                                        pm.subject, 
                                        pm.encabezado, 
                                        pm.footer
                                From Capsulas c (nolock)
                                inner join Temas t (nolock) on c.temaId = t.temaId
                                left join PersonalizacionesMails pm (nolock) on c.temaId = pm.temaId
                                Where   c.capsulaId = " . $capsula_id . "
                                and     c.capsulaVersion = " . $capsulaVersion . "";

    $personalizacion = $base_datos->sql_query($queryPersonalizacion);
    $personalizacionData = $base_datos->sql_fetch_assoc($personalizacion);

    $temaImagen = htmlentities($personalizacionData['temaImagen']);             //  27-11-2012      obtenemos la imagen del tema para el mail        
    $capsulaNombre = $personalizacionData['capsulaNombre'];   
    $encabezado = htmlentities($personalizacionData['encabezado']);
    $footer = htmlentities($personalizacionData['footer']);
    $subject = $personalizacionData['subject'];

    if(trim($subject) != ""){                
        $subject = $capsulaNombre . " - " . $subject;
    }
    else{
        $subject = $capsulaNombre;
    }

    $mensajeCorreo = "";

    $mensajeCorreo=$mensajeCorreo."<table border='0' style='border:1px solid #4B6C9F;width:600px;' CELLSPACING='0' CELLSPADDING='0'><tr>";
    //$mensajeCorreo=$mensajeCorreo."<td style='background-color:#4B6C9F;color:white;vertical-align:middle;width:450px;border-bottom:1px solid #4B6C9F;text-align:center;height:75px;' height='75px'>";
    //$mensajeCorreo=$mensajeCorreo."<span style='font-weight:bolder;font-size:16px;'> </span></td>";

    if(trim($temaImagen) == ""){        
        $mensajeCorreo=$mensajeCorreo."<td style='background-color:#4B6C9F;color:white;vertical-align:middle;width:450px;border-bottom:1px solid #4B6C9F;text-align:center;height:75px;' height='75px'>";
        $mensajeCorreo=$mensajeCorreo."<span style='font-weight:bolder;font-size:16px;'>" . htmlentities($capsulaNombre) . "</span></td>";
    }
    else{
        $mensajeCorreo=$mensajeCorreo."<td style='background-color:#FFFFFF; border-right: 1px solid #4B6C9F; color:white;vertical-align:middle;width:450px;border-bottom:1px solid #4B6C9F;text-align:center;height:75px;' height='75px'>";
        $mensajeCorreo=$mensajeCorreo."<img src='..". $temaImagen ."' border='0' width='100%' alt='" . htmlentities($capsulaNombre) . "' title='" . htmlentities($capsulaNombre) . "'></td>";
    } 


    $mensajeCorreo=$mensajeCorreo."<td style='border-bottom:1px solid #4B6C9F;width:150px;height:75px;' align='center'><img src='". $organizacionLogo ."' border='0' alt='". $organizacionNombre ."' title='". $organizacionNombre ."' width='75%' height='75%'> </td></tr>";
    $mensajeCorreo=$mensajeCorreo."<tr><td colspan='2' style='background-color:#ffffff;'>&nbsp;</td></tr>";
    $mensajeCorreo=$mensajeCorreo."<tr><td colspan='2' style='padding-left:30px;padding-right:30px;text-align:justify;word-wrap: break-word;'>";
    $mensajeCorreo=$mensajeCorreo."Estimado(a):<br><b>".mb_convert_encoding($usuarioNombre, "ISO-8859-1", "UTF-8")."</b><br><br>";
    $mensajeCorreo=$mensajeCorreo.stripslashes(mb_convert_encoding($encabezado, "ISO-8859-1", "UTF-8"))."<br><br>";
    $mensajeCorreo=$mensajeCorreo."Para acceder a la c&aacute;psula haga click <a href='".$link."'>Aqui</a>";
    $mensajeCorreo=$mensajeCorreo."<br><br>";
    $mensajeCorreo=$mensajeCorreo.stripslashes(mb_convert_encoding($footer, "ISO-8859-1", "UTF-8"));
    $mensajeCorreo=$mensajeCorreo."</td></tr><tr><td colspan='2' style='background-color:#ffffff;'>&nbsp;</td></tr><tr><td colspan='2' style='background-color:#4B6C9F;'>&nbsp;</td></tr></table>";
	
    /** 22-10-2014 **/
	
    $correoDeNombre = stripslashes(mb_convert_encoding("Cápsula de Prueba", "ISO-8859-1", "UTF-8"));
	
    /****************/
	
    $resultadoEnvio=enviarCorreo($correoDe,$correoDeNombre,$mailenvio, $subject, mb_convert_encoding($mensajeCorreo, "ISO-8859-1", "UTF-8"));

    if ($resultadoEnvio == "") {
        echo "1";
    } else {
        echo "0";
    }

    //echo $resultadoEnvio;
}
?>