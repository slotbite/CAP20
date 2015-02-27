<?php
session_start();


header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Evaluacion.xls");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: public");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Accept-Ranges: bytes");


include("../default.php");
$plantilla->setPath('../skins/saam/plantillas/');

$clienteId = $_SESSION['clienteId'];
$administradorId = $_SESSION['administradorId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}

require('clases/evaluaciones.class.php');
$objEvaluacion = new evaluaciones();

$evaluacionId = htmlspecialchars(trim($_POST['evaluacionId']));

$consulta = $objEvaluacion->evaBuscarAdministrador($administradorId);
$administrador = mssql_fetch_array($consulta);

$consulta = $objEvaluacion->evaBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta);

$consulta = $objEvaluacion->evaBuscarTipoEvaluacion($clienteId);

if ($consulta) {

    $tipoEva = mssql_fetch_array($consulta);
    $tipoEvaluacion = $tipoEva['escalaId'];
}


$consulta = $objEvaluacion->evaMostrarEvaluacion($evaluacionId);
$evaluacion = mssql_fetch_array($consulta);

$evaluacionCapsulas = $objEvaluacion->evaMostrarEvaluacionCapsulas($evaluacionId);
$evaluacionPracticas = $objEvaluacion->evaMostrarEvaluacionPracticas($evaluacionId);
?>      


<script type="text/javascript" src="../scripts/funciones.js"></script>

<div style="min-height: 400px">

    <h4>Evaluaci&oacute;n</h4>

    <div class="divFormulario">
        <table>  
            <tr>                    
                <td width="100px" display="border: none">Cliente: </td><td display="border: none"><?php echo $cliente['clienteNombreCompleto'] ?></td>                    
            </tr>
            <tr>                    
                <td width="100px" display="border: none">Administrador: </td><td display="border: none"><?php echo $administrador['administradorNombreCompleto'] ?></td>                    
            </tr>
            <tr>
                <td width="100px" display="border: none">Tema: </td><td display="border: none"><?php echo $evaluacion['temaNombre'] ?></td>                   
            </tr>  
            <tr>
                <td width="100px" display="border: none">Evaluaci&oacute;n: </td><td display="border: none"><?php echo $evaluacion['evaluacionNombre'] ?></td>                    
            </tr>
            <tr>
                <td width="100px" display="border: none">Descripci&oacute;n: </td><td display="border: none" colspan="10"><?php  echo $evaluacion['evaluacionDescripcion'] ?></td>                    
            </tr>                
        </table>          

    </div>


    <?
    $count = 1;
    $count2 = 1;
    $notasC = array();
    $notasP = array();
    $arregloEvaluaciones = array();

    if ($evaluacionCapsulas) {

        while ($resultado = mssql_fetch_array($evaluacionCapsulas)) {
            $notasC[$count - 1][0] = "C" . $count;
            //$notasC[$count - 1][1] = $resultado['ponderacion'];
            $arregloEvaluaciones[$count - 1][0] = "C" . "-" . $resultado['capsulaId'] . "-" . $resultado['capsulaVersion'];
            $arregloEvaluaciones[$count - 1][1] = $resultado['ponderacion'];
            $count = $count + 1;
        }
    }

    if (mssql_num_rows($evaluacionPracticas) > 0) {

        while ($resultado = mssql_fetch_array($evaluacionPracticas)) {

            $notasP[$count2 - 1][0] = "C" . $count;
            //$notasP[$count2 - 1][1] = $resultado['ponderacion'];
            $arregloEvaluaciones[$count - 1][0] = "P" . "-" . $resultado['practicaId'];
            $arregloEvaluaciones[$count - 1][1] = $resultado['ponderacion'];
            $count = $count + 1;
            $count2 = $count2 + 1;
        }
    }
    ?>

    <h4>Notas</h4>


    <?
    $parametro = "";

    for ($i = 0; $i < count($arregloEvaluaciones); $i++) {
        $parametro = $parametro . "[" . $arregloEvaluaciones[$i][0] . "],";
    }

    $parametro = substr($parametro, 0, strlen($parametro) - 1);

    $consulta = $objEvaluacion->evaMostrarNotas($evaluacionId, $parametro);
    ?>

    <table style="border: 1px solid #000">
        <tr>
            <th width="5px" align="left" style="border: 1px solid #000">
                N&ordm;
            </th>
            <th width="80px" align="left" style="border: 1px solid #000">
                RUT
            </th>
            <th width="300px" align="left" style="border: 1px solid #000">
                Nombre
            </th>

            <?
            for ($i = 0; $i < count($notasC); $i++) {
                ?>
                <th style="width:200px" align="right" style="border: 1px solid #000">
                    <?
                    echo $notasC[$i][0];
                    ?>                    
                </th>                                
                <?
            }
            for ($i = 0; $i < count($notasP); $i++) {
                ?>
                <th width="200px" align="right" style="border: 1px solid #000">
                    <?
                    echo $notasP[$i][0];
                    ?>                    
                </th>                                
                <?
            }
            ?>
            <th style="border: 1px solid #000">
                NF
            </th>
            <?
            if ($consulta) {

                $x = 1;
                $notaFinal = 0;

                while ($notas = mssql_fetch_array($consulta)) {
                    
                    $notaFinal = 0;
                    
                    ?>
                <tr>
                    <td align="left" style="border: 1px solid #000">
                        <?
                        echo $x;
                        ?>                        
                    </td>                                 
                    </td>
                    <td align="left" style="border: 1px solid #000">
                        <? echo htmlentities($notas[1]) ?>
                    </td>
                    <td align="left" style="border: 1px solid #000">
                        <? echo htmlentities($notas[2]) ?>
                    </td>                        
                    <?
                    for ($y = 0; $y < count($arregloEvaluaciones); $y++) {

                        if (substr($arregloEvaluaciones[$y][0], 0, 1) == "C") {
                            ?>                                                        
                            <td align="right" style="border: 1px solid #000">
                                <?  if ($tipoEvaluacion == "1"){echo number_format($notas[$y + 3], 1, '.', '.');} else{echo number_format($notas[$y + 3], 0, '.', '.');} ?>
                            </td>                                                                                                                                            
                            <?
                            $notaFinal = $notaFinal + ($notas[$y + 3] * ($arregloEvaluaciones[$y][1] / 100));
                        }

                        if (substr($arregloEvaluaciones[$y][0], 0, 1) == "P") {                            
                            ?>                                                        
                            <td align="right" style="border: 1px solid #000">
                                <?  if ($tipoEvaluacion == "1"){echo number_format($notas[$y + 3], 1, '.', '.');} else{echo number_format($notas[$y + 3], 0, '.', '.');} ?>
                            </td>                                                        
                            <?
                            $notaFinal = $notaFinal + ($notas[$y + 3] * ($arregloEvaluaciones[$y][1] / 100));
                        }
                    }
                    ?>
                    <td align="right" style="border: 1px solid #000">                        
                        <?  if ($tipoEvaluacion == "1"){echo number_format($notaFinal, 1, '.', '.');} else{echo number_format($notaFinal, 0, '.', '.');} ?>
                    </td>                                  
                     
                </tr>                    
                <?
                $x = $x + 1;
            }
        }
        ?>
        </tr>

    </table>  

</div>

<script type="text/javascript">
    
    evaCalcularNotaFinal();
    
</script>

<?
mssql_free_result($consulta);
mssql_free_result($evaluacionCapsulas);

;
?>


