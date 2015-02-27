<?php
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



$clienteId = $_SESSION['clienteId'];
$administradorId = $_SESSION['administradorId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}

require('clases/evaluaciones.class.php');
$objEvaluacion = new evaluaciones();

$evaluacionId = htmlspecialchars(trim($_POST['evaluacionIdCalificaciones']));

$consulta = $objEvaluacion->evaCalcularNotas($evaluacionId, $clienteId);

$consulta = $objEvaluacion->evaBuscarTipoEvaluacion($clienteId);

if ($consulta) {

    $tipoEva = mssql_fetch_array($consulta);
    $tipoEvaluacion = $tipoEva['escalaId'];
}

$consulta = $objEvaluacion->evaBuscarAdministrador($administradorId);
$administrador = mssql_fetch_array($consulta);

$consulta = $objEvaluacion->evaBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta);

$consulta = $objEvaluacion->evaMostrarEvaluacion($evaluacionId);
$evaluacion = mssql_fetch_array($consulta);

$evaluacionCapsulas = $objEvaluacion->evaMostrarEvaluacionCapsulas($evaluacionId);
$evaluacionPracticas = $objEvaluacion->evaMostrarEvaluacionPracticas($evaluacionId);
?>      


<input type="hidden" name="clienteId" id="clienteId" value="<?php echo $clienteId ?>"/>
<input type="hidden" name="administradorId" id="administradorId" value="<?php echo $administradorId ?>"/>

<div style="min-height: 400px">

    <table width="100%">
        <tr>
            <td align="right">
                <a href="#" class="volver" onclick="location.href = '../evaluaciones/evaEvaluaciones.php'">Volver</a>
            </td>
        </tr>    
    </table>

    <h4>Calificaciones</h4>
    
    <br>
    
    <div class="divElementos">

    <div class="divFormulario">
        <table>  
            <tr>                    
                <td width="100px" display="border: none">Cliente: </td><td display="border: none"><?php echo htmlentities($cliente['clienteNombreCompleto']) ?></td>                    
            </tr>
            <tr>                    
                <td width="100px" display="border: none">Administrador: </td><td display="border: none"><?php echo htmlentities($administrador['administradorNombreCompleto']) ?></td>                    
            </tr>
            <tr>
                <td width="100px" display="border: none">Tema: </td><td display="border: none"><?php echo htmlentities($evaluacion['temaNombre']) ?></td>                   
            </tr>  
            <tr>
                <td width="100px" display="border: none">Evaluación: </td><td display="border: none"><?php echo htmlentities($evaluacion['evaluacionNombre']) ?></td>                    
            </tr>
            <tr>
                <td width="100px" display="border: none">Descripción: </td><td display="border: none"><?php echo htmlentities($evaluacion['evaluacionDescripcion']) ?></td>                    
            </tr>                
        </table>          

    </div>

    <br><br>

    <h4>Ponderaciones</h4>

    <br>

    <table>
        <tr>
            <td width="500px" valign="top">   


                <table class="tabla">
                    <tr>
                        <th>

                        </th>
                        <th width="250px">
                            Cápsula
                        </th>
                        <th width="10px">
                            Versión
                        </th>
                        <th width="40px">
                            Ponderación
                        </th>
                    </tr>


                    <?
                    $count = 1;
                    $count2 = 1;
                    $notasC = array();
                    $notasP = array();
                    $arregloEvaluaciones = array();

                    if ($evaluacionCapsulas) {

                        while ($resultado = mssql_fetch_array($evaluacionCapsulas)) {
                            ?>
                            <tr>
                                <td>
                                    <?
                                    echo "C" . $count;
                                    $notasC[$count - 1][0] = "C" . $count;
                                    $notasC[$count - 1][1] = $resultado['ponderacion'];
                                    $arregloEvaluaciones[] = "C" . "-" . $resultado['capsulaId'] . "-" . $resultado['capsulaVersion'];
                                    ?>
                                </td>
                                <td>
                                    <?
                                    echo htmlentities($resultado['capsulaNombre']);
                                    ?>
                                </td>
                                <td>
                                    <?
                                    echo htmlentities($resultado['capsulaVersion']);
                                    ?>
                                </td>
                                <td align="center">
                                    <?
                                    echo htmlentities($resultado['ponderacion']) . " %";
                                    ?>
                                </td>                                                            
                            </tr>
                            <?
                            $count = $count + 1;
                        }
                    }
                    ?>

                </table>

            </td>
            <td width="500px" valign="top">   
                <table class="tabla">


                    <?
                    if (mssql_num_rows($evaluacionPracticas) > 0) {
                        ?>

                        <tr>
                            <th>

                            </th>
                            <th width="250px">
                                Prácticas
                            </th>                        
                            <th width="40px">
                                Ponderación
                            </th>
                        </tr>
                        <?
                        while ($resultado = mssql_fetch_array($evaluacionPracticas)) {
                            ?>
                            <tr>
                                <td>
                                    <?
                                    echo "C" . $count;
                                    $notasP[$count2 - 1][0] = "C" . $count;
                                    $notasP[$count2 - 1][1] = $resultado['ponderacion'];
                                    $arregloEvaluaciones[] = "P" . "-" . $resultado['practicaId'];
                                    ?>
                                </td>
                                <td>
                                    <?
                                    echo htmlentities($resultado['practicaNombre']);
                                    ?>
                                </td>                                
                                <td align="center">
                                    <?
                                    echo htmlentities($resultado['ponderacion']) . " %";
                                    ?>
                                </td>                                                            
                            </tr>
                            <?
                            $count = $count + 1;
                            $count2 = $count2 + 1;
                        }
                    }
                    ?>

                </table>
            </td>
        </tr>    
    </table>


    <br><br>

    <h4>Notas</h4>

    <br> 

    <input id="nColumnas" name="nColumnas" type="text" style="display:none" value="<? echo count($notasC) + count($notasP) ?>" />
    <input id="nColumnasC" name="nColumnasC" type="text" style="display:none" value="<? echo count($notasC) ?>" />

    <?
    $parametro = "";

    for ($i = 0; $i < count($arregloEvaluaciones); $i++) {
        $parametro = $parametro . "[" . $arregloEvaluaciones[$i] . "],";
    }

    $parametro = substr($parametro, 0, strlen($parametro) - 1);

    $consulta = $objEvaluacion->evaMostrarNotas($evaluacionId, $parametro);
    ?>

    <table class="tabla" id="notasFinales" name="notasFinales">
        <tr>
            <th width="5px">
                N°
            </th>
            <th width="80px">
                RUT
            </th>
            <th width="300px">
                Nombre
            </th>

            <?
            for ($i = 0; $i < count($notasC); $i++) {
                ?>
                <th width="35px">
                    <?
                    echo $notasC[$i][0];
                    ?>
                    <input type="text" style="display:none" value="<? echo $notasC[$i][1] / 100 ?>" />
                </th>                                
                <?
            }
            for ($i = 0; $i < count($notasP); $i++) {
                ?>
                <th width="35px">
                    <?
                    echo $notasP[$i][0];
                    ?>
                    <input type="text" style="display:none" value="<? echo $notasP[$i][1] / 100 ?>" />
                </th>                                
                <?
            }
            ?>
            <th>
                NF
            </th>
            <?
            if ($consulta) {

                $x = 1;

                while ($notas = mssql_fetch_array($consulta)) {
                    ?>
                <tr>
                    <td>
                        <?
                        echo $x;
                        ?>
                        <input type="text" style="display:none" value="<? echo htmlentities($notas[0]) ?>" />
                        <input id="<? echo $x ?>" type="text" style="display:none" value=""/>
                    </td>                                 
                    </td>
                    <td>
                        <? echo htmlentities($notas[1]) ?>
                    </td>
                    <td>
                        <? echo htmlentities($notas[2]) ?>
                    </td>                        
                    <?
                    for ($y = 0; $y < count($arregloEvaluaciones); $y++) {

                        if (substr($arregloEvaluaciones[$y], 0, 1) == "C") {
                            ?>                                                        
                            <td>
                                <input type="text" style="width: 25px; font-size: 11px; text-align:right; background-color: #EAECEE;" readonly="readonly" maxlength="3" value="<?
                if ($tipoEvaluacion == "1") {

                    if (htmlentities($notas[$y + 3]) == "") {
                        echo "";
                    } else {
                        echo number_format($notas[$y + 3], 1, '.', '.');
                    }
                } else {

                    if (htmlentities($notas[$y + 3]) == "") {
                        echo "";
                    } else {
                        echo number_format($notas[$y + 3], 0, '.', '.');
                    }
                }
                            ?>" />
                            </td>                                                        
                                <?
                            }

                            if (substr($arregloEvaluaciones[$y], 0, 1) == "P") {
                                $practica = split("-", $arregloEvaluaciones[$y]);
                                ?>                                                        
                            <td>
                                <input id="<? echo "I-" . $x . "-" . $practica[1] ?>" type="text" style="width: 25px; font-size: 11px; text-align:right" maxlength="3" onclick="evaSeleccionar(this.id)" value="<?
                if ($tipoEvaluacion == "1") {

                    if (htmlentities($notas[$y + 3]) == "") {
                        echo "";
                    } else {
                        echo number_format($notas[$y + 3], 1, '.', '.');
                    }
                } else {
                    if (htmlentities($notas[$y + 3]) == "") {
                        echo "";
                    } else {
                        echo number_format($notas[$y + 3], 0, '.', '.');
                    }
                }
                                ?>" onchange="evaNumeroDecimal(this.id, <? echo $tipoEvaluacion ?>)" <? if ($tipoEvaluacion == "1") { ?> onkeyup="evaMascaraNotas(this,'.',true)" <? } ?> onblur="evaAplicarFormato(this.id, <? echo $tipoEvaluacion ?>); evaCalcularNotaFinalFila(<? echo $x ?>, <? echo $tipoEvaluacion ?>)"  />
                            </td>                                                        
                                <?
                            }
                        }
                        ?>
                    <td>
                        <input id="<? echo "nf-" . $x ?>" type="text" style="width: 25px; font-size: 11px; text-align:right; background-color: #F3F5F7; border:none" readonly="readonly" maxlength="4" />
                    </td>                                  
                </tr>                    
                    <?
                    $x = $x + 1;
                }
            }
            ?>
        </tr>        

    </table>  

    <br>

    <span><a id="nuevo" href="#" onclick="evaExportarEvaluacionExcel()">Exportar a Excel</a></span>

    <br>


    <form id="frmExportarCalificaciones" name="frmExportarCalificaciones" method="post" action="evaFuncionExportarEvaluacion.php" style="align:left; display: none">

        <input type="hidden" name="evaluacionId" id="evaluacionId" value="<?php echo $evaluacionId ?>"/>               
        <input type="submit" name="btnExportarCalificaciones" id="btnExportarCalificaciones" />

    </form>

    <br><br>
    
    <table>
        <tr>
            <td width="400px">
                <p>
                    <br>
                    <input id="btnGuardar" type="button" name="button" class="btn azul" value="Guardar" onclick="evaGuardarNotasEvaluacion()" />
                    <label></label>                                
                    <input type="button" name="cancelar" class="btn" id="cancelar" value="Cancelar" onclick="location.href = 'evaEvaluaciones.php'" />
                    <input type="submit" name="submit" id="btnCalificacionesEva" style="display: none"/>
                </p>

            </td>
            <td>
                <table id="tablaCargando" style="display: none">
                    <tr>
                        <td width="40px">
                            <img src="../skins/saam/img/loader.gif" alt="Guardando" />
                        </td>
                        <td>
                            <font color="#0000FF"><i>Guardando</i></font> 
                        </td>
                    </tr>
                </table>
            </td>
        </tr>    
    </table>
    <br>
    
    </div>

</div>

<script type="text/javascript">
    
    evaCalcularNotaFinal(<? echo $tipoEvaluacion ?>);
    
</script>

<?
mssql_free_result($consulta);
mssql_free_result($evaluacionCapsulas);



$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>


