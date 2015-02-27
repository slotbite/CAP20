<?php
include ("../../librerias/conexion.php");
require('../clases/registro.class.php');

session_start();

$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];

$objReg = new Registro();


$resultado = $objReg->ListarCapsulasDuplicadas($fecha_desde, $fecha_hasta);
$numFilas = mssql_num_rows($resultado);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    </head>


    <body>
        <div id="lista1" style="width:100%;height:100%;overflow:hidden;overflow-y:auto;margin:0px;">

            <center>
                <form method="post" >
                    <table id='ListadeCapsulas' class='tabla' 
                           <thead>
                        <th style='width:60px;'>Cápsula Id</th>
                        <th>Versión</th>
                        <th style='width: 270px;'>Cápsula Nombre</th>
                        <th style='width: 100px;'>Tipo</th>
                        <th style='width: 200px;'>Usuario Nombre</th>
                        <th style='width: 200px;'>Usuario Apellido</th>
                        <th style='width: 70px;'>Contador</th>                        
                        <th style='width: 100px;'></th>
                        </thead>
                        <?php $i = 1; ?>
                        <tr>
                            <?php
                            if ($numFilas > 0) {
                                while ($row = mssql_fetch_assoc($resultado)) {
                                    ?>


                                    <td>
                                        <?php echo $row['capsulaId'] ?>
                                    </td>
                                    <td>
                                        <?php echo $row['capsulaVersion'] ?>
                                    </td>
                                    <td>
                                        <?php echo htmlentities($row['capsulaNombre']) ?>
                                    </td>
                                    <td>
                                        <?php echo $row['tipoNombre'] ?>
                                    </td>
                                    <td>
                                        <?php echo $row['usuarioNombres'] ?>
                                    </td>
                                    <td>
                                        <?php echo $row['usuarioApellidos'] ?>
                                    </td>
                                    <td>
                                        <?php echo $row['Contador'] ?>
                                    </td>                                
                                    <td valign="middle" align="center">
                                        <input type="button" id="boton3" name="button" class="btn azul" value="Ver Envios" onclick="cargarDetalle(<?php echo $row['capsulaId'] ?>,<?php echo $row['usuarioId'] ?>,<?php echo $row['tipo'] ?>,<?php echo $row['capsulaVersion'] ?>,'<?php echo htmlentities($row['capsulaNombre'])?>','<?php echo htmlentities($row['usuarioNombres']) . ' ' . htmlentities($row['usuarioApellidos']);?>');" />
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="8" style="color: red">
                                    Búsqueda sin resultados. 
                                </td>
                            </tr>
                            <?
                        }
                        ?>
                    </table>    
                </form>
            </center>

        </div>

    </body> 
</html>

