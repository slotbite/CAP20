<?php
include ("../../librerias/conexion.php");
require('../clases/registro.class.php');

session_start();

$capsulaId2 = $_POST['capsulaId2'];
$usuarioId2 = $_POST['usuarioId2'];


$capsulaVersion = $_POST['capsulaVersion'];
$capsulaNombre = $_POST['capsulaNombre'];
$usuarioNombre = $_POST['usuarioNombre'];

$tipo1 = $_POST['tipo1'];

$tabla = "";

if ($tipo1 == 1) {
    $tabla = "UsuariosCuestionarios";
} else if ($tipo1 == 2) {
    $tabla = "UsuariosEncuestas";
}

echo $capsulaId;

$objReg = new Registro();

$resultado = $objReg->TraerEnviosCapsula($tabla, $capsulaId2, $capsulaVersion, $usuarioId2);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    </head>

    <div id="ListaUsuarios" style="width:1055px; height: 450px; overflow:hidden;overflow-y:auto;margin:0px; padding: 10px">

        <div style="text-align:left; padding: 10px;">

            <table width="100%">
                <tr>
                    <td>
                        <h3>Datos:</h3>
                    </td>
                    <td align="right">
                        <div onclick="cerrar_formulario()" style="width:20px; float: right; cursor: pointer;">X</div>
                    </td>
                </tr>
            </table>


            <table width="100%">
                <tr>
                    <td width="100px">
                        <b>Cápsula Id:</b>
                    </td>
                    <td>
                        <? echo $capsulaId2; ?>
                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Cápsula versión:</b>
                    </td>
                    <td>
                        <? echo $capsulaVersion; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Cápsula:</b>
                    </td>
                    <td>
                        <? echo $capsulaNombre; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Usuario:</b>
                    </td>
                    <td>
                        <? echo $usuarioNombre; ?>
                    </td>
                </tr>

            </table>


        </div>



        <body>


        <center>
            <form method="post" >
                <table id='ListadeCapsulas' class='tabla' >                        
                    <thead>
                    <th style='width:100px;'></th>
                    <th style='width:60px;'>Envio Id</th>
                    <th style='width:60px;'>N° pregunta</th>
                    <th style='width:370px;'>Pregunta</th>
                    <th style='width:370px;'>Respuesta</th>                        


                    </thead>
                    <?php
                    $i = 1;

                    while ($row = mssql_fetch_assoc($resultado)) {
                        ?>
                        <tr>

                            <?
                            $envioId = $row['envioId'];
                            $capsulaId = $row['capsulaId'];
                            $capsulaVersion = $row['capsulaVersion'];
                            $usuarioId = $row['usuarioId'];
                            $cantidad = $row['cantidad'];
                            ?>
                            <td rowspan="<? if($cantidad == 1){ echo $cantidad;}else{ echo $cantidad + 1;}?>">
                                <input type="button" id="btnEliminar" name="button" class="btn azul" value="Eliminar" onclick="eliminarRegistros(<? echo $envioId ?>,<? echo $capsulaId ?>,<? echo $capsulaVersion ?>,<? echo $usuarioId ?>);" />
                            </td>
                            <td align="center" rowspan="<? if($cantidad == 1){ echo $cantidad;}else {echo $cantidad + 1;}?>">
                                <?php echo $row['envioId'] ?>
                            </td>
                            <? if ($cantidad != 1) { ?>
                            </tr>
                        <? } ?>
                        <?
                        $resultado2 = $objReg->TraerDetalleEnviosCapsula($tabla, $capsulaId, $usuarioId, $envioId);
                        while ($row2 = mssql_fetch_assoc($resultado2)) {
                            ?>
                            <? if ($cantidad != 1) { ?>
                                <tr>
                                <? } ?>
                                <td align="center">
                                    <?php echo htmlentities($row2['preguntaOrden']) ?>
                                </td> 
                                <td>
                                    <?php echo htmlentities($row2['preguntaTexto']) ?>
                                </td>                                   
                                <td>
                                    <?php echo htmlentities($row2['textoRespuesta']) ?>
                                </td>                                
                                <? if ($cantidad != 1) { ?>
                                </tr>
                            <? } ?>
                            <?
                        }
                        if ($cantidad == 1) { ?>
                            </tr>
                        <? }  $i++ ?>
                    <?php } ?>
                </table>    
            </form>
        </center>



    </body> 

</div>
</html>


