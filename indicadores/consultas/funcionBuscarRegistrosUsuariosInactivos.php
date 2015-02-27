
<?php
include ("../../librerias/conexion.php");
require('../clases/registro.class.php');


$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];

$objReg = new Registro();




$resultado = $objReg->seleccionarRegistrosInactivos($fecha_desde, $fecha_hasta);
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
                        <th style='width:60px;'>Envío Id</th>
                        <th style='width: 350px;'>Cápsula Nombre</th>
                        <th style='width: 100px;'>Tipo</th>
                        <th style='width: 230px;'>Usuario Nombre</th>
                        <th style='width: 230px;'>Usuario Apellido</th>
                        <th style='width: 90px;'>Fecha Envío</th>
                        </thead>
                        <?php $i = 1; ?>

                        <?php
                        if ($numFilas > 0) {

                            while ($row = mssql_fetch_assoc($resultado)) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $row['envioId'] ?>
                                    </td>
                                    <td>
                                        <?php echo htmlentities($row['capsulaNombre']) ?>
                                    </td>
                                    <td>
                                        <?php echo $row['tipo'] ?>
                                    </td>
                                    <td>
                                        <?php echo htmlentities($row['usuarioNombres']) ?>
                                    </td>
                                    <td>
                                        <?php echo htmlentities($row['usuarioApellidos']) ?>
                                    </td>
                                    <td>
                                        <?php echo date("d-m-Y", strtotime($row['fechaEnvio'])) ?>
                                    </td>
                                </tr>
                                <?php $i++ ?>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="6" style="color: red">
                                    Búsqueda sin resultados. 
                                </td>
                            </tr>

                        <? } ?>
                    </table>    
                </form>
            </center>

            <? if ($numFilas > 0) { ?>

                <input type="button" class="btn azul" value="Eliminar" onclick="eliminar('<?php echo $fecha_desde ?>', ' <?php echo $fecha_hasta ?>');">

            <? } ?>

            <script>

                function eliminar(fecha_desde, fecha_hasta) {

                    //almacenamos el confirm en una variable
                    var blnRespuesta = confirm('¿Desea eliminar los envios seleccionados?');
                    //comparamos la respuesta del usuario
                    if (blnRespuesta) {
                        $.ajax({
                            type: "POST",
                            url: "consultas/funcionEliminarRegistrosUsuariosInactivos.php",
                            data: {fecha_desde: fecha_desde, fecha_hasta: fecha_hasta},
                            success: function (data) {
                                $('#repo_id').html(data);
                                $('#repo_hold').css('visibility', 'hidden');
                                $('#divTabla').css('visibility', 'hidden');
                                alert('Los envíos fueron eliminados correctamente.');
                            }
                        });                        
                        //alert(fecha_hasta);
                    } 

                }


            </script>
        </div>

    </body> 
</html>

