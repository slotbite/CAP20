<?php
//session_start();

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
?>

<script src="jquery.js" type="text/javascript"></script>
<script src="funcionesIndicadores.js" type="text/javascript"></script>

<input type="hidden" name="clienteId" id="clienteId" value="<?php echo $clienteId ?>"/>
<input type="hidden" name="administradorId" id="administradorId" value="<?php echo $administradorId ?>"/>




<table width="100%">
    <tr>
        <td align="right">
            <a href="#" class="volver" onclick="location.href = '../indicadores/indicadores.php'">Volver</a>
        </td>
    </tr>
</table>

<h4>Registro de envíos a usuarios inactivos</h4>


<div class="divElementos">

    <div style="min-height: 400px">
        <br>
        <div>

            <div class="divFormulario" style="height:60px;">
                <form name="formConsulta1" id="formConsulta1" method="post" >
                    <table>
                        <tr>
                            <td valign="middle">
                                Fecha desde:
                            </td>
                            <td valign="middle">
                                <input id="fecha_desde" name="fecha_desde" type="text" style="width:70px; text-align:right" readonly="readonly" />
                                <button id='btn-1' class='btn invisible' style="padding:0px"><img src='../skins/saam/img/calendar.png'/></button>
                                <script type="text/javascript">

                                    var myCal1 = Calendar.setup({
                                        inputField: "fecha_desde",
                                        trigger: "btn-1",
                                        onSelect: function () {
                                            this.hide();
                                        },
                                        showTime: 12,
                                        dateFormat: "%d-%m-%Y"
                                    });
                                    myCal1.setLanguage('es');
                                </script>

                            </td>
                            <td width="30px">

                            </td>
                            <td valign="middle">
                                Fecha hasta:
                            </td>
                            <td valign="middle">
                                <input id="fecha_hasta" name="fecha_hasta" type="text" style="width:70px; text-align:right" readonly="readonly" />
                                <button id='btn-2' class='btn invisible' style="padding:0px"><img src='../skins/saam/img/calendar.png'/></button>
                                <script type="text/javascript">

                                    var myCal1 = Calendar.setup({
                                        inputField: "fecha_hasta",
                                        trigger: "btn-2",
                                        onSelect: function () {
                                            this.hide();
                                        },
                                        showTime: 12,
                                        dateFormat: "%d-%m-%Y"
                                    });
                                    myCal1.setLanguage('es');
                                </script>                                
                            </td>
                            <td>
                                <input type="button" id="boton1" name="button" class="btn azul" value="Buscar" onclick="seleccionarUsuarios();" style="position: relative; left: 14px;"/>
                            </td>
                        </tr>
                    </table>

                </form>	

            </div>
            <br>
            <div style="min-height: 280px">
                <div id="divTabla" class="divFormulario" style="width:98%;visibility:hidden;">

                    <!--- Formulario que muestra las capsulas que tienen envios duplicados --->
                    <div id="listado">

                        <div id="ListaUsuarios" >


                        </div>

                    </div>


                </div>	
            </div>

            <div id="repo_hold" style="position: fixed; top: 0px; left: 0px; width: 100%; height: 100%;text-align: center; visibility: hidden;background-color:rgba(0,0,0,0.8);  ">
                <div><br><br><br>
                </div>
                <div style="border: solid 1px #000;display: inline-block; width: 80%; height: 80%; margin: 0px; vertical-align: bottom; background-color: #FFF;" id="repo_id">




                </div>



            </div>


        </div>
        <?
        $plantilla->setTemplate("footer_2");
        echo $plantilla->show();
        ?>
