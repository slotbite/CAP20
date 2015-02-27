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

require('clases/planificaciones.class.php');
$objPlanificacion = new planificaciones();

$consulta = $objPlanificacion->planBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta);
$clienteNombre = $cliente['clienteNombreCompleto'];

if (isset($_POST['submit'])) {

    $planificacionNombre = mb_convert_encoding(trim($_POST['planPlanificacionNombre']), "ISO-8859-1", "UTF-8");
    $consulta = $objPlanificacion->planMostrarPlanificaciones($clienteId, $administradorId, $planificacionNombre);
} else {

    $consulta = $objPlanificacion->planMostrarPlanificaciones($clienteId, $administradorId, '');
}
?>

<script type="text/javascript" src="../scripts/mediabox/mediaboxAdv-1.2.5b.js"></script>
<link rel="stylesheet" type="text/css" href="../scripts/mediabox/mediaboxAdvBlack21.css"/>

<input type="hidden" name="clienteId" id="clienteId" value="<?php echo $clienteId ?>"/>

<script type="text/javascript">
                                            
    window.addEvent('domready', function() {
              
              
        new Autocompleter.Request.HTML($('planPlanificacionNombre'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'         :	'planificacionId',
                'nombre_campo'      : 	'planificacionNombre',
                'nombre_tablas'     :   'From Planificaciones t (nolock)',
                'nombre_where'      :   'and clienteId = ' + $('clienteId').get('value') + 'and administradorId = ' + $('administradorId').get('value')
            }
        });                                    
                                                                                    
    });                
        

    function SeleccionarPlanificaciones(){
        var property = 'opacity';
        var to = "1";                                           
    
        var elRequest = new Request({
            url         : 'planFuncionBuscarPlan.php',
            method      : 'post',
            async       : false,
        
            onSuccess   : function(datos) {
                if(datos)
                {                
                    $('BoxContentPlan').set('html',datos);                                                            
                    $('mBoxContainerPlan').tween(property, to);
                    
                }                        
            },
            //Si Falla
            onFailure: function() {
                alert("Se ha producido un error. Por favor, inténtelo más tarde.");
            }
        });

        elRequest.send();  

    }

    function SeleccionarPlan(){
        var tabla = $('tablaListaPlan');                
        var filas = tabla.rows.length;
        for(i = 0; i < filas; i++){        
            objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
            if(objInput0.checked == true){
        
                objInput1 = tabla.rows[i].cells[1];            
                $('planPlanificacionNombre').set('value', ((objInput1.innerText != undefined) ? objInput1.innerText : objInput1.textContent ));
                i = filas;
            }           
        }
        
        $('mBoxContainerPlan').tween("opacity", 0);  
    }


		
</script>    

<input type="hidden" name="clienteId" id="clienteId" value="<?php echo $clienteId ?>"/>
<input type="hidden" name="administradorId" id="administradorId" value="<?php echo $administradorId ?>"/>

<table width="100%">
    <tr>
        <td align="right">
            <a href="#" class="volver" onclick="location.href = '../index.php'">Volver</a>
        </td>
    </tr>    
</table>

<h4>Planificaciones</h4>

<div style="min-height: 400px;">

    <br>

    <div id="contenedor">

        <div id="formulario" style="display:none;">
        </div>

        <div id="tabla">

            <form id="frmPlanMostrar" name="frmPlanMostrar" method="post" action="planPlanificaciones.php" style="align:left">
                <table>
                    <tr>
                        <td width="100px" style="padding-bottom:6px">Cliente: </td><td style="padding-bottom:6px"><?php echo htmlentities($clienteNombre) ?></td>
                    </tr> 
                    <tr>
                        <td>Planificación: </td><td display="border: none">
                            <input id="planPlanificacionNombre" name="planPlanificacionNombre" type="text" class="campo" value= "<?php echo htmlentities($planificacionNombre) ?>"/>
                            <img id="lupaEva" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="SeleccionarPlanificaciones()"/>  

                            <div id ="mBoxContainerPlan" class="BoxContainer" style="position: absolute;opacity:0;filter:alpha(opacity=0);" height="auto">

                                <div class="BoxTitle">Planificaciones</div>    

                                <div id="BoxContentPlan" class="BoxContent " style="width: auto;">                        

                                </div>

                                <div class="BoxFooterContainer" align="right">

                                    <input id="btnCancelarPlan" type="button" name="button" class="btn" value="Cancelar" />   
                                    <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="SeleccionarPlan()" />   

                                </div>

                            </div>     

                        </td>                    
                    </tr>                        
                </table>
                <br>
                <br>

                <input type="submit" name="submit" class="btn" value="Buscar" />

                <br>

            </form>

            <br>

            <span id="nuevo"><a id="nuevo" href="planIngresarPlanificacion.php">Ingresar Planificación</a></span>

            <br><br><br>

            <div align="left">

                <div class="divElementos">

                    <table id="detalle" class="tabla">
                        <tr>
                            <th width="300px">Planificación</th>                                                                    
                            <th width="400px">Descripción</th>                                                    
                            <th width="80px">Estado</th>                        
                            <th width="80px">Fecha creación</th>
                            <th width="80px">Fecha edición</th>
                            <th width="25px"></th>
                            <th width="40px"></th>
                            <th width="40px"></th>
                        </tr>

                        <?
                        if ($consulta) {

                            $count = 0;

                            while ($planificacion = mssql_fetch_array($consulta)) {
                                ?>
                                <tr id="fila-<?php echo $planificacion['planificacionId'] ?>">
                                    <td><?php echo htmlentities($planificacion['planificacionNombre']) ?></td>                                                                                          
                                    <td><?php echo htmlentities($planificacion['planificacionDescripcion']) ?></td>                                
                                    <td><?php
                        if ($planificacion['planificacionEstado'] == "2") {
                            echo "Finalizada";
                            $accion = "";
                        }

                        if ($planificacion['planificacionEstado'] == "1") {
                            echo "Activa";
                            $accion = "Anular";
                        }

                        if ($planificacion['planificacionEstado'] == "0") {
                            echo "Anulada";
                            $accion = "Activar";
                        }
                                ?>
                                    </td>                                

                                    <td><?php echo date("d-m-Y", strtotime($planificacion['fechaCreacion'])) ?></td>                                                                                           
                                    <td><?php echo date("d-m-Y", strtotime($planificacion['fechaModificacion'])) ?></td>                                                                                           
                                    <td><span><a rel="lightbox[url 1018 500]" href="../planificaciones/planVerPlanificacion.php?planificacionId=<?php echo $planificacion['planificacionId'] ?>" target="_blank">Ver</a></span></td>                           
                                    <td><span><a href="#" onclick="planEditarData(<? echo $planificacion['planificacionId'] ?>, 'planificacionId', 'btnEditarPlanificacion')">Editar</a></span></td>
                                    <td><span><a href="#" onClick="planAnularData(<? echo $planificacion['planificacionId'] ?>, 'planFuncionAnularPlanificacion.php?planificacionId=', 'planPlanificaciones.php', <?php echo $planificacion['planificacionEstado'] ?>, 'la planificación seleccionada?' ); return false"><? echo $accion ?></a></span></td>
                                </tr>

        <?php
        $count = $count + 1;
    }
}
?>

                    </table>
<?
if ($count == 0) {
    echo "<label style='color: #FF0000; font-size: 12px'>Búsqueda sin resultados.</label>";
}
?>

                </div>

            </div>

            <form id="frmPlanActualizar" name="frmPlanActualizar" method="post" action="planEditarPlanificacion.php" style="align:left; display: none">

                <input id="planificacionId" name="planificacionId" type="text"/>                
                <input type="submit" name="btnEditarPlanificacion" id="btnEditarPlanificacion" />

            </form>                        

            <br>

        </div>

    </div>

</div>

<?
mssql_free_result($consulta);

$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>
