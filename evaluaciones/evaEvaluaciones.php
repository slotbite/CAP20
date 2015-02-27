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

$consulta = $objEvaluacion->evaBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta);
$clienteNombre = $cliente['clienteNombreCompleto'];

if (isset($_POST['submit'])) {

    $temaNombre = mb_convert_encoding(trim($_POST['evaTemaNombre']), "ISO-8859-1", "UTF-8");
    $evaluacionNombre = mb_convert_encoding(trim($_POST['evaEvaluacionNombre']), "ISO-8859-1", "UTF-8");

    $consulta = $objEvaluacion->evaMostrarEvaluaciones($clienteId, $administradorId, $temaNombre, $evaluacionNombre);
} else {
    $consulta = $objEvaluacion->evaMostrarEvaluaciones($clienteId, $administradorId, '', '');
}
?>

<script type="text/javascript">
                                            
    window.addEvent('domready', function() {
                                                                                                
        new Autocompleter.Request.HTML($('evaTemaNombre'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'         :	'temaId',
                'nombre_campo'      : 	'temaNombre',
                'nombre_tablas'     :   'From Temas t (nolock)',
                'nombre_where'      :   'and clienteId = ' + $('clienteId').get('value')
            }
        });
                                                                                    
		$('btnCancelarTema').addEvent('click', function(){

				var property = 'opacity';
				var to = "0";

				$('mBoxContainerTema').tween(property, to);

		});  
	
	
		$('btnCancelarEva').addEvent('click', function(){

			var property = 'opacity';
			var to = "0";

			$('mBoxContainerEva').tween(property, to);

		});  

	
	});                
        

function evaSeleccionarTema2(){
    var tabla = $('tablaListaTemas');                
    var filas = tabla.rows.length;
    for(i = 0; i < filas; i++){        
        objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
        if(objInput0.checked == true){
        
            objInput1 = tabla.rows[i].cells[1];            
            $('evaTemaNombre').set('value', ((objInput1.innerText != undefined) ? objInput1.innerText : objInput1.textContent ));
            i = filas;
        }           
    }
        
    $('mBoxContainerTema').tween("opacity", 0);                            
    
}

function evaSeleccionarTemas2(){
            
    var property = 'opacity';
    var to = "1";                                           
    
    var elRequest = new Request({
        url         : 'evaFuncionBuscarTemas.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function(datos) {
            if(datos)
            {                
                $('BoxContentTema').set('html',datos);                                                            
                $('mBoxContainerTema').tween(property, to);
                    
            }                        
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");
        }
    });

    elRequest.send();  
}


function evaSeleccionarEva(){
    var tabla = $('tablaListaEva');                
    var filas = tabla.rows.length;
    for(i = 0; i < filas; i++){        
        objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
        if(objInput0.checked == true){
        
            objInput1 = tabla.rows[i].cells[1];            
            $('evaEvaluacionNombre').set('value', ((objInput1.innerText != undefined) ? objInput1.innerText : objInput1.textContent ));
            i = filas;
        }           
    }
        
    $('mBoxContainerEva').tween("opacity", 0);                            
    
}

function evaSeleccionarEvas(){
            
    var property = 'opacity';
    var to = "1";                                           
    
    var elRequest = new Request({
        url         : 'evaFuncionBuscarEvaMant.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function(datos) {
            if(datos)
            {                
                $('BoxContentEva').set('html',datos);                                                            
                $('mBoxContainerEva').tween(property, to);
                    
            }                        
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");
        }
    });

    elRequest.send();  
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

<h4>Evaluaciones</h4>

<div style="min-height: 400px">
    <br>
    <div id="contenedor">
        <div id="formulario" style="display:none;">
        </div>
        <div id="tabla">

            <form id="frmEvaMostrar" name="frmEvaMostrar" method="post" action="evaEvaluaciones.php" style="align:left">
                <table>
                    <tr>
                        <td width="100px" style="padding-bottom:6px">Cliente: </td><td style="padding-bottom:6px"><?php echo htmlentities($clienteNombre) ?></td>
                    </tr> 
                    <tr>
                        <td>Tema: </td><td display="border: none"><input id="evaTemaNombre" name="evaTemaNombre" type="text" class="campo" value= "<?php echo htmlentities($temaNombre) ?>"/>
						<img id="lupaTema" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="evaSeleccionarTemas2()"/> 
						 <div id ="mBoxContainerTema" class="BoxContainer" style="position: absolute;opacity:0;filter:alpha(opacity=0);z-index: 12;" height="auto">

                        <div class="BoxTitle">Temas</div>    

                        <div id="BoxContentTema" class="BoxContent " style="width: auto;">                        

                        </div>

                        <div class="BoxFooterContainer" align="right">

                            <input id="btnCancelarTema" type="button" name="button" class="btn" value="Cancelar" />   
                            <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="evaSeleccionarTema2()" />   

                        </div>

						</div>        
						
						</td>                    
                    </tr>                        
                    <tr>
                        <td>Evaluación: </td>
						<td>
						<input id="evaEvaluacionNombre" name="evaEvaluacionNombre" type="text" class="campo" onfocus="evaMostrarEvaluaciones()" value= "<?php echo htmlentities($evaluacionNombre) ?>"/>
						<img id="lupaTema" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="evaSeleccionarEvas()"/> 
						 <div id ="mBoxContainerEva" class="BoxContainer" style="position: absolute;opacity:0;filter:alpha(opacity=0);" height="auto">

                        <div class="BoxTitle">Evaluaciones</div>    

                        <div id="BoxContentEva" class="BoxContent " style="width: auto;">                        

                        </div>

                        <div class="BoxFooterContainer" align="right">

                            <input id="btnCancelarEva" type="button" name="button" class="btn" value="Cancelar" />   
                            <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="evaSeleccionarEva()" />   

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
            <span id="nuevo"><a id="nuevo" href="evaIngresarEvaluacion">Ingresar Evaluación</a></span>
            <br><br>
            
            <div align="left">                
                
                <div class="divElementos">
            
            <table id="detalle" class="tabla">
                <tr>
                    <th width="200">Tema</th>                        
                    <th width="200px">Evaluación</th>                        
                    <th width="300px">Descripción</th>                                                    
                    <th width="50px">Estado</th>                        
                    <th width="80px">Fecha creación</th>
                    <th width="40px"></th>
                    <th width="40px"></th>
                    <th width="40px"></th>
                </tr>

                <?
                if ($consulta) {
                    
                    $count = 0;
                    
                    while ($evaluacion = mssql_fetch_array($consulta)) {
                        ?>
                        <tr id="fila-<?php echo $evaluacion['evaluacionId'] ?>">
                            <td><?php echo htmlentities($evaluacion['temaNombre']) ?></td>                                
                            <td><?php echo htmlentities($evaluacion['evaluacionNombre']) ?></td>                                
                            <td><?php echo htmlentities($evaluacion['evaluacionDescripcion']) ?></td>                                
                            <td><?php
                if (htmlentities($evaluacion['evaluacionEstado']) == "1") {
                    echo "Activo";
                    $accion = "Anular";
                } else {
                    echo "Inactivo";
                    $accion = "Activar";
                }
                        ?>
                            </td>                                

                            <td><?php echo date("d-m-Y", strtotime($evaluacion['fechaCreacion'])) ?></td>                                                                                           
                            <td><span><a href="#" onclick='evaCalificaciones(<?php echo $evaluacion['evaluacionId'] ?>)'>Calificaciones</a></span></td>
                            <td><span><a href="#" onclick='evaEditarData(<?php echo $evaluacion['evaluacionId'] ?>)'>Editar</a></span></td>
                            <td><span><a href="#" onClick="evaAnularData(<? echo $evaluacion['evaluacionId'] ?>, 'evaFuncionAnularEvaluacion.php?evaluacionId=', 'evaEvaluaciones.php', <?php echo $evaluacion['evaluacionEstado'] ?>, 'la evaluación seleccionada?' ); return false"><? echo $accion ?></a></span></td>                           
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
                
            <form id="frmEvaActualizar" name="frmEvaActualizar" method="post" action="evaEditarEvaluacion.php" style="align:left; display: none">
                
                <input id="evaluacionId" name="evaluacionId" type="text"/>                
                <input type="submit" name="btnEditarEvaluacion" id="btnEditarEvaluacion" />
               
            </form>
            
            <form id="frmEvaCalificaciones" name="frmEvaCalificaciones" method="post" action="evaVerCalificaciones.php" style="align:left; display: none">
                
                <input id="evaluacionIdCalificaciones" name="evaluacionIdCalificaciones" type="text"/>                
                <input type="submit" name="btnVerCalificaciones" id="btnVerCalificaciones" />
               
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
