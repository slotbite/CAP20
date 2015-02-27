<?php
session_start();

include("../default.php");
$clienteId = $_SESSION['clienteId'];
$administradorId = $_SESSION['administradorId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}

require('clases/planificaciones.class.php');
$objPlanificacion = new planificaciones();

$planificacionId = htmlspecialchars(trim($_GET['planificacionId']));


$consulta = $objPlanificacion->planBuscarAdministrador($administradorId);
$administrador = mssql_fetch_array($consulta);

$consulta = $objPlanificacion->planBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta);

$consulta = $objPlanificacion->planMostrarPlanificacion($planificacionId);
$planificacion = mssql_fetch_array($consulta);

$planificacionCapsulas = $objPlanificacion->planMostrarPlanificacionCapsulas($planificacionId);
$planificacionUsuarios = $objPlanificacion->planMostrarPlanificacionUsuarios($planificacionId);

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

                <h3>Planificación</h3>

                <table style="font: 11px Lucida Sans Unicode" width="990px">  
                    <tr>                    
                        <td width="100px" display="border: none">Cliente: </td>
                        <td display="border: none"><?php echo htmlentities($cliente['clienteNombreCompleto']) ?></td>                    
                        <td width="50px">
                            Estado:
                        </td>
                        <td align="left">
                            <?php 
                            if(htmlentities($planificacion['planificacionEstado']) == "0"){
                                echo "<font color='#EB0000'><b>ANULADA</b></font>";
                            }
                            if(htmlentities($planificacion['planificacionEstado']) == "1"){
                                echo "<font color='#00EB18'><b>ACTIVA</b></font>";
                            }
                            if(htmlentities($planificacion['planificacionEstado']) == "2"){
                                echo "<font color='#1F00EB'><b>FINALIZADA</b></font>";
                            }
                                
                            
                            ?>
                        </td>
                    </tr>
                    <tr>                    
                        <td width="100px" display="border: none">Administrador: </td><td display="border: none"><?php echo htmlentities($administrador['administradorNombreCompleto']) ?></td>                    
                    </tr>            
                    <tr>
                        <td width="100px" display="border: none">Planificación: </td><td display="border: none"><?php echo htmlentities($planificacion['planificacionNombre']) ?></td>                    
                    </tr>
                    <tr>
                        <td width="100px" display="border: none">Descripción: </td><td display="border: none"><?php echo htmlentities($planificacion['planificacionDescripcion']) ?></td>                    
                    </tr>                
                </table>          

                <br>

                <h3>Cápsulas</h3>

                <br>

                <table id="detalle" class="tabla">
                    <tr>

                        <th width="250px">Tema</th>                                                                    
                        <th width="300px">Cápsula</th>                                                    
                        <th width="20px">Versión</th>                                            
                        <th width="80px">Fecha Envío</th>
                        <th width="80px">Fecha Cierre</th>
                        <th width="120px">Estado</th>

                    </tr>

                    <?
                    if ($planificacionCapsulas) {

                        while ($planificacionCap = mssql_fetch_array($planificacionCapsulas)) {
                            ?>
                            <tr id="fila-<?php echo $planificacionCap['planificacionId'] ?>">
                                <td><?php echo htmlentities($planificacionCap['temaNombre']) ?></td>                                                                                          
                                <td><?php echo htmlentities($planificacionCap['capsulaNombre']) ?></td>
                                <td><?php echo htmlentities($planificacionCap['capsulaVersion']) ?></td>
                                <td><?php echo date("d-m-Y", strtotime($planificacionCap['fechaEnvio'])) ?></td>
                                <td><?php echo date("d-m-Y", strtotime($planificacionCap['fechaCierre'])) ?></td>
                                <td><?php echo $planificacionCap['estado'] ?></td>                                                                                  
                            </tr>

                            <?php
                        }
                    }
                    ?>

                </table>

                <br>

                <h3>Usuarios</h3>

                <br>

                <table id="detalle" class="tabla">
                    <tr>

                        <th width="180px">Nombre</th>                                                                    
                        <th width="130px">E-Mail</th>                                                    
                        <th width="130px">Organización</th>                                            
                        <th width="130px">Gerencia/Agencia</th>
                        <th width="130px">Área</th>
                        <th width="130px">Cargo</th>

                    </tr>

                    <?
                    if ($planificacionUsuarios) {

                        while ($planificacionUsu = mssql_fetch_array($planificacionUsuarios)) {
                            ?>
                            <tr id="fila-<?php echo $planificacionUsu['usuarioId'] ?>">
                                <td><?php echo htmlentities($planificacionUsu['nombre']) ?></td>                                                                                          
                                <td><?php echo htmlentities($planificacionUsu['usuarioEmail']) ?></td>
                                <td><?php echo htmlentities($planificacionUsu['organizacionNombre']) ?></td>
                                <td><?php echo htmlentities($planificacionUsu['sectorNombre']) ?></td>
                                <td><?php echo htmlentities($planificacionUsu['areaNombre']) ?></td>
                                <td><?php echo htmlentities($planificacionUsu['cargoNombre']) ?></td>                                                                                  
                            </tr>

                            <?php
                        }
                    }
                    ?>

                </table>
                
            </div>







        </body>

    </html>








