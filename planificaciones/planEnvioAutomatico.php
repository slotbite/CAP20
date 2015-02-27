<?php

session_start();
include ("../librerias/conexion.php");
include ("../librerias/crypt.php");
include ("../librerias/config.php");
include ("../librerias/funciones_correo.php");


$fechaActual = date("d-m-Y");

echo "Planificaciones: <br> " . $fechaActual . "<br/><br/>";

//$fechaActual = "12-04-2014";

$queryPlanificaciones = "   Select distinct cp.planificacionId, cp.clienteId, cp.capsulaId, cp.capsulaVersion, cp.fechaCierre, t.temaId, 
                                            CASE WHEN c.capsulaNumero is NULL or LTRIM(RTRIM(c.capsulaNumero)) = '' THEN c.capsulaNombre ELSE LTRIM(RTRIM(c.capsulaNumero)) + '.- ' + c.capsulaNombre END as capsulaNombre, 
                                            t.temaImagen, cp.usuarioCreacion
                            From CapsulasPlanificadas cp (nolock)
                            inner join Planificaciones p (nolock) on cp.planificacionId = p.planificacionId
                            inner join Capsulas c (nolock) on cp.capsulaId = c.capsulaId and cp.capsulaVersion = c.capsulaVersion
                            inner join Temas t (nolock) on c.temaId = t. temaId
                            Where   cp.fechaEnvio = '" . $fechaActual . "'
                            and     p.planificacionEstado = 1";

$planificaciones = $base_datos->sql_query($queryPlanificaciones);
$totalEnvios = 0;


if ($planificaciones) {

    while ($planificacionesData = mssql_fetch_array($planificaciones)) {

        /** Se ejecuta la tarea por cada una de las cápsulas planificadas por el día actual * */
        $planificacionId = htmlentities($planificacionesData['planificacionId']);
        $clienteId = htmlentities($planificacionesData['clienteId']);
        $capsulaId = htmlentities($planificacionesData['capsulaId']);
        $capsulaVersion = htmlentities($planificacionesData['capsulaVersion']);
        $fechaCierre = date("d-m-Y", strtotime($planificacionesData['fechaCierre']));
        $temaId = htmlentities($planificacionesData['temaId']);                     //  27-11-2012      obtenemos el temaId para la personalizacion
        $temaImagen = htmlentities($planificacionesData['temaImagen']);             //  27-11-2012      obtenemos la imagen del tema para el mail
        $capsulaNombre = $planificacionesData['capsulaNombre'];                     //  27-11-2012      obtenemos el nombre de la capsula para la cabecera del email
        $usuarioCreacion = $planificacionesData['usuarioCreacion'];                 //  11-04-2014      


         /** Obtenemos la personalización **/
        $queryPersonalizacion = "EXEC capListarPersonalizacion " . $clienteId . "," . $temaId;      // 27-11-2012 se cambia la personalizacion: de organizacion a tema

        $personalizacion = $base_datos->sql_query($queryPersonalizacion);
        $personalizacionData = $base_datos->sql_fetch_assoc($personalizacion);

        $encabezado = htmlentities($personalizacionData['encabezado']) ? htmlentities($personalizacionData['encabezado']) : '';
        $footer = htmlentities($personalizacionData['footer']) ? htmlentities($personalizacionData['footer']) : '';
        $subject = $personalizacionData['subject'] ? $personalizacionData['subject'] : '';

        if(trim($subject) != ""){
            $subject = $capsulaNombre . " - " . $subject;
        }
        else{
            $subject = $capsulaNombre;
        }


        /** Obtenemos el MAX id de la tabla Envios * */
        $queryMaxEnvioId = "  Select (MAX(e.envioId) + 1) as 'envioId' From Envios e (nolock) Where e.clienteId = " . $clienteId;
        $envios = $base_datos->sql_query($queryMaxEnvioId);
        $envioData = mssql_fetch_array($envios);

        $envioId = htmlentities($envioData['envioId']);


        /** Obtenemos la lista de usuario por planificación - cliente - capsula - fecha actual * */
        $queryUsuarios = "  Select   cp.planificacionId, cp.clienteId, cp.capsulaId, cp.capsulaVersion, up.usuarioId, ISNULL(u.organizacionId, 0) as 'organizacionId', ISNULL(u.sectorId, 0) as 'sectorId', ISNULL(u.areaId, 0) as 'areaId', (u.usuarioNombres + ' ' + u.usuarioApellidos) as 'nombre', u.usuarioEmail
                            From CapsulasPlanificadas cp (nolock)
                            inner join UsuariosPlanificados up (nolock) on cp.planificacionId = up.planificacionId
                            inner join Usuarios u (nolock) on up.usuarioId = u.usuarioId
                            Where   cp.planificacionId = " . $planificacionId . "
                            and     cp.clienteId = " . $clienteId . "
                            and     cp.capsulaId = " . $capsulaId . "
                            and     cp.capsulaVersion = " . $capsulaVersion . "
                            and     cp.fechaEnvio = '" . $fechaActual . "' 
                            and     u.usuarioEstado = 1 
                            and     not exists (Select 1 From Envios e (nolock) Where e.capsulaId = cp.capsulaId and e.capsulaVersion = cp.capsulaVersion and e.usuarioId = up.usuarioId and e.envioEstado = 1 and e.planificacionId = cp.planificacionId and e.fechaEnvio BETWEEN '" . $fechaActual . " 00:00:00' and '" . $fechaActual . " 23:59:59')";

        $usuarios = $base_datos->sql_query($queryUsuarios);


        if ($usuarios) {

            while ($usuariosData = mssql_fetch_array($usuarios)) {

                $usuarioId = htmlentities($usuariosData['usuarioId']);
                $organizacionId = htmlentities($usuariosData['organizacionId']);
                $sectorId = htmlentities($usuariosData['sectorId']);
                $areaId = htmlentities($usuariosData['areaId']);
                $usuarioNombre = htmlentities($usuariosData['nombre']);
                $usuarioEmail = htmlentities($usuariosData['usuarioEmail']);

                /************************************/

                $pre_url = "capsula_id=" . $capsulaId . "&version=" . $capsulaVersion . "&usuarioid=" . $usuarioId . "&envioid=" . $envioId;
                $cod_1 = enc_dec($pre_url);
                $link = $url . "hash=" . urlencode($cod_1);


                /** Guardamos en la tabla Envio **/
                $queryInsertarEnvio = "EXEC envCrearEnvio " . $envioId . "," . $capsulaId . "," . $capsulaVersion . "," . $usuarioId . ",'" . $link . "'," . $organizacionId . "," . $sectorId . "," . $areaId . ",0, '" . $usuarioCreacion . "','" . $fechaCierre . "'," . $clienteId . "," . $planificacionId . ",0";                
                $dataEnvio = $base_datos->sql_query($queryInsertarEnvio);
                $contenidoDataEnvio = $base_datos->sql_fetch_assoc($dataEnvio);
                                                
                if($contenidoDataEnvio['estado'] == "OK"){
                
                    /** Guardamos en la tabla Cuestionario **/
                    $queryInsertarCuestionario = "EXEC envCargaTablaCuestionario " . $envioId . "," . $capsulaId . "," . $capsulaVersion . "," . $usuarioId . ",'" . $usuarioCreacion . "'";
                    $base_datos->sql_query($queryInsertarCuestionario);

                    
                    /** Obtenemos el logo de la organización por cada usuario **/
                    $queryContenidoLogo = "EXEC envTraeLogoUsuario " . $usuarioId . " ";
                    $contenidoLogo = $base_datos->sql_query($queryContenidoLogo);
                    $contenidoLogoData = $base_datos->sql_fetch_assoc($contenidoLogo);

                    $organizacionLogo = htmlentities($contenidoLogoData['organizacionLogo']) ? htmlentities($contenidoLogoData['organizacionLogo']) : '';
                    $organizacionNombre = htmlentities($contenidoLogoData['organizacionNombre']) ? htmlentities($contenidoLogoData['organizacionNombre']) : '';

                    //  $direccion = $organizacionLogo;
                    //	$arreglo1 = explode("/",$direccion);
                    //	$organizacionLogo = $arreglo1[3];

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


                    $mensajeCorreo=$mensajeCorreo."<td style='border-bottom:1px solid #4B6C9F;width:150px;height:75px;' align='center'><img src='". $organizacionLogo ."' border='0' alt='".mb_convert_encoding($organizacionNombre, "ISO-8859-1", "UTF-8") . "' title='".mb_convert_encoding($organizacionNombre, "ISO-8859-1", "UTF-8") . "' width='75%' height='75%'> </td></tr>";
                    $mensajeCorreo=$mensajeCorreo."<tr><td colspan='2' style='background-color:#ffffff;'>&nbsp;</td></tr>";
                    $mensajeCorreo=$mensajeCorreo."<tr><td colspan='2' style='padding-left:30px;padding-right:30px;text-align:justify;word-wrap: break-word;'>";
                    $mensajeCorreo=$mensajeCorreo."Estimado(a):<br><b>".mb_convert_encoding($usuarioNombre, "ISO-8859-1", "UTF-8")."</b><br><br>";
                    $mensajeCorreo=$mensajeCorreo.stripslashes(mb_convert_encoding($encabezado, "ISO-8859-1", "UTF-8"))."<br><br>";
                    $mensajeCorreo=$mensajeCorreo."Para acceder a la c&aacute;psula haga click <a href='".$link."'>Aqui</a>";
                    $mensajeCorreo=$mensajeCorreo."<br><br>";
                    $mensajeCorreo=$mensajeCorreo.stripslashes(mb_convert_encoding($footer, "ISO-8859-1", "UTF-8"));
                    $mensajeCorreo=$mensajeCorreo."</td></tr><tr><td colspan='2' style='background-color:#ffffff;'>&nbsp;</td></tr><tr><td colspan='2' style='background-color:#4B6C9F;'>&nbsp;</td></tr></table>";

                    //echo $mensajeCorreo;

                    $correoDeNombre = $organizacionNombre;  // 25-10-2012     Se reemplaza el remitente ('Administrador') por el nombre de la empresa.

                    //$resultadoEnvio = "";
                    $resultadoEnvio = enviarCorreo($correoDe, $correoDeNombre, $usuarioEmail, $subject, mb_convert_encoding($mensajeCorreo, "ISO-8859-1", "UTF-8"));


                    $estadoEnvio = "";

                    if ($resultadoEnvio == "") {
                        $estadoEnvio = 1;
                    } else {
                        $estadoEnvio = 2;
                    }

                    $stmt = mssql_init("envActualizaEstado");

                    mssql_bind($stmt, '@envioId', $envioId, SQLINT4);
                    mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
                    mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);
                    mssql_bind($stmt, '@usuarioId', $usuarioId, SQLINT4);
                    mssql_bind($stmt, '@estado', $estadoEnvio, SQLINT2);
                    mssql_bind($stmt, '@mensajeEnvio', $resultadoEnvio, SQLTEXT);

                    $base_datos->sql_ejecutar_sp($stmt);

                    mssql_free_result($contenidoLogo);                

                    $totalEnvios = $totalEnvios + 1;

                    echo "Envío n°: " . $totalEnvios;
                    echo "<br/>";
                    
                    if (($totalEnvios % 25) == 0) {
                        sleep(60);
                    }

                }

                

            }
            
            mssql_free_result($usuarios);
            
        }

        mssql_free_result($personalizacion);
        mssql_free_result($envios);
        
    }

}

echo "<br/>Envíos a administradores:<br/>";


/** Enviamos el email al administrador, indicando los datos del log de envío * */
$queryListaAdministradores = "  Select distinct p.administradorId, a.administradorNombres, a.administradorApellidos, a.administradorEmail
                                From CapsulasPlanificadas cp (nolock)
                                inner join Planificaciones p (nolock) on cp.planificacionId = p.planificacionId
                                inner join Administradores a (nolock) on p.administradorId = a.administradorId
                                inner join Envios e (nolock) on cp.capsulaId = e.capsulaId and cp.capsulaVersion = e.capsulaVersion and cp.clienteId = e.clienteId
                                Where   cp.planificacionId = e.planificacionId
                                and     cp.fechaEnvio = '" . $fechaActual . "'                                
                                and     e.fechaEnvio BETWEEN '" . $fechaActual . " 00:00:00' and '" . $fechaActual . " 23:59:59'";

$administradores = $base_datos->sql_query($queryListaAdministradores);
$totalEnviosAdm = 0;

if ($administradores) {

    while ($administradoresData = mssql_fetch_array($administradores)) {

        $admId = htmlentities($administradoresData['administradorId']);
        $admEmail = htmlentities($administradoresData['administradorEmail']);
        $admNombre = trim(htmlentities($administradoresData['administradorNombres'])) . ' ' . trim(htmlentities($administradoresData['administradorApellidos']));

        $mensajeCorreo = "";
        
        $mensajeCorreo=$mensajeCorreo."<table border='0' style='border:1px solid #4B6C9F;width:600px;' CELLSPACING='0' CELLSPADDING='0'><tr>";
        $mensajeCorreo=$mensajeCorreo."<td style='background-color:#4B6C9F;color:white;vertical-align:middle;width:450px;border-bottom:1px solid #4B6C9F;text-align:left;height:75px; padding-left:25px' height='75px'>";
        $mensajeCorreo=$mensajeCorreo."<span style='font-weight:bolder;font-size:16px;'>C&Aacute;PSULAS DE CONOCIMIENTO</span></td></tr>";
        $mensajeCorreo=$mensajeCorreo."<tr><td style='background-color:#ffffff;'>&nbsp;</td></tr>";
        $mensajeCorreo=$mensajeCorreo."<tr><td style='padding-left:30px;padding-right:30px;text-align:justify;word-wrap: break-word;'>";
        $mensajeCorreo=$mensajeCorreo."Estimado(a):<br><b>".mb_convert_encoding($admNombre, "ISO-8859-1", "UTF-8")."</b><br><br>";
        $mensajeCorreo=$mensajeCorreo."Se han enviado las siguientes cápsulas:";
        $mensajeCorreo=$mensajeCorreo."<br><br>";

        $mensajeCorreo = $mensajeCorreo . "<table border='0' cellspacing = '0' style='border:1px solid #5F95EF; font-size: 14px'><tr><td width='80px' style='border-bottom:1px solid #5F95EF;'>N° envío</td><td width='250px' style='border-bottom:1px solid #5F95EF;'>Tema</td><td width='350px' style='border-bottom:1px solid #5F95EF;'>Cápsula</td><td width='50px' style='border-bottom:1px solid #5F95EF;' align='center'>Versión</td></tr>";


        $queryPlanificacionesEnviadas = "   Select distinct p.administradorId, a.administradorNombres, a.administradorApellidos, a.administradorEmail, 
                                            CASE WHEN c.capsulaNumero is NULL or LTRIM(RTRIM(c.capsulaNumero)) = '' THEN c.capsulaNombre ELSE LTRIM(RTRIM(c.capsulaNumero)) + '.- ' + c.capsulaNombre END as capsulaNombre, 
                                            c.capsulaVersion, t.temaNombre, e.envioId, a.login
                                            From CapsulasPlanificadas cp (nolock)
                                            inner join Planificaciones p (nolock) on cp.planificacionId = p.planificacionId
                                            inner join Administradores a (nolock) on p.administradorId = a.administradorId
                                            inner join Capsulas c (nolock) on cp.capsulaId = c.capsulaId and cp.capsulaVersion = c.capsulaVersion
                                            inner join Temas t (nolock) on c.temaId = t.temaId
                                            inner join Envios e (nolock) on cp.capsulaId = e.capsulaId and cp.capsulaVersion = e.capsulaVersion and cp.clienteId = e.clienteId and cp.planificacionId = e.planificacionId
                                            Where   e.envioEstado = 1
                                            and     cp.fechaEnvio = '" . $fechaActual . "'
                                            and     e.fechaEnvio BETWEEN '" . $fechaActual . " 00:00:00' and '" . $fechaActual . " 23:59:59'
                                            and     a.administradorId = " . $admId . "";

        $planificacionesEnviadas = $base_datos->sql_query($queryPlanificacionesEnviadas);
               
        /** 30-08-2013 **/
        
        //$loginAdmin = "";
        //$listaEnvios = "0";
        
        /****/

        if ($planificacionesEnviadas) {

            while ($planificacionesEnviadasData = mssql_fetch_array($planificacionesEnviadas)) {

                $envioId = htmlentities($planificacionesEnviadasData['envioId']);
                $temaNombre = trim(htmlentities($planificacionesEnviadasData['temaNombre']));
                $capsulaNombre = trim(htmlentities($planificacionesEnviadasData['capsulaNombre']));
                $capsulaVersion = htmlentities($planificacionesEnviadasData['capsulaVersion']);

                $mensajeCorreo = $mensajeCorreo . "<tr><td align='center'>" . $envioId . "</td><td>" . $temaNombre . "</td><td>" . $capsulaNombre . "</td><td align='center'>" . $capsulaVersion . "</td></tr>";
                
                //$listaEnvios = $listaEnvios . ", " . $envioId;
                //$loginAdmin = htmlentities($planificacionesEnviadasData['login']);
                
            }
        }              
        
        
        //mssql_free_result($planificacionesEnviadas);

        $mensajeCorreo = $mensajeCorreo . "</table>";
        $mensajeCorreo = $mensajeCorreo . "<br><br></td></tr></table>";

        $subject = "Planificación de Envíos";

        $resultadoEnvio = enviarCorreo($correoDe, mb_convert_encoding('Cápsulas de Conocimiento', "ISO-8859-1", "UTF-8"), $admEmail, mb_convert_encoding($subject, "ISO-8859-1", "UTF-8"), mb_convert_encoding($mensajeCorreo, "ISO-8859-1", "UTF-8"));

        $totalEnviosAdm = $totalEnviosAdm + 1;

        echo "Envío n°: " . $totalEnviosAdm;
        echo "<br/>";
        
        echo $mensajeCorreo;
        
        
        mssql_free_result($planificacionesEnviadas);
        
       
    }
}


mssql_free_result($planificaciones);
mssql_free_result($administradores);
?>
