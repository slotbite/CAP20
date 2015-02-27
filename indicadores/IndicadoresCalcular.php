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


$yyyyActual = date("Y");
$mmActual = date("m");
$meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

$selectYyyy = "<select id='yyyy' style='width:60px'>";

for($i = 0; $i < 3; $i++){    
    
    if($yyyyActual == $yyyyActual - $i){
        $selectYyyy .= '<option value=' . ($yyyyActual - $i) . ' selected>'. ($yyyyActual - $i) .'</option>';
    }
    else{
        $selectYyyy .= '<option value=' . ($yyyyActual - $i) . '>'. ($yyyyActual - $i) .'</option>';
    }
        
    
    
    
}

$selectYyyy .= "</select>";

$selectMm = "<select id='mm' style='width:110px'>";

for($i = 0; $i < 12; $i++){    
    
    if($mmActual == $i + 1){
        $selectMm .= '<option value=' . ($i + 1) . ' selected>'. $meses[$i] .'</option>';    
    }
    else{
        $selectMm .= '<option value=' . ($i + 1) . '>'. $meses[$i] .'</option>';    
    }        
}


$selectMm .= "</select>";


?>

<script src="jquery.js" type="text/javascript"></script>
<script src="funcionesIndicadores.js" type="text/javascript"></script>

<script type="text/javascript">

    $(document).ready(function(){
        
        var yyyy = $("#yyyy").val();
        var mm = $("#mm").val();
        
        buscarIndicadoresCAP(yyyy, mm);
        
        
    });
    
    
    function abrirTr(idTr) {
        var clase = ".valor_N2_" + idTr;
        if ($(clase).css("display") == 'none') {
            $(clase).css('display', '');
        }
        else {
            $(clase).css('display', 'none');
        }

    }
    
    
    function recalcularIndicador(){
        
        var yyyy = $("#yyyy").val();
        var mm = $("#mm").val();
        
        recalcularIndicadorCAP(yyyy, mm);
        
    }
    
    
    </script>


<input type="hidden" name="clienteId" id="clienteId" value="<?php echo $clienteId ?>"/>
<input type="hidden" name="administradorId" id="administradorId" value="<?php echo $administradorId ?>"/>


<table width="100%">
    <tr>
        <td align="right">
            <a href="#" class="volver" onclick="location.href = '../indicadores/indicadores.php'">Volver</a>
        </td>
    </tr>
</table>

<h4>Recalcular indicadores (visibles en SGH y reportería)</h4>


<div class="divElementos">

    <div style="min-height: 400px">
        <br>
        <div>

            <div id="tabla" class="divFormulario" style="height:60px;">
                <form name="formConsulta1" id="formConsulta1" method="post" >
                    <table>
                        <tr>
                            <td colspan="3">
                                <b>Seleccione el período:</b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Año:
                            </td>
                            <td>
                                <? echo $selectYyyy; ?>
                            </td>
                            <td width="30px">

                            </td>
                            <td>
                                Mes:
                            </td>
                            <td>
                                <? echo $selectMm; ?>
                            </td>
                            <td width="200px" align="center">
                                <input type="button" name="button" class="btn azul" value="Calcular" onclick="recalcularIndicador()" style=" left: 55px;" />
                            </td>
                            <td valign="middle">
                                <center>
                                    <img id="imgCargando" src="../skins/saam/img/loader_2.gif" alt="" style="display:none; width:25px"><a/>
                                </center>
                            </td>

                        </tr>


                    </table>
                </form>	    

            </div>
            
            <br/>
            
            
            
            <div id="divIndicadorCap">
                
            </div>
            
            <br>

        </div>
        
    </div>
    
</div>
<?
$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>
