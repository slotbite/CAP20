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

$consulta = $objPlanificacion->planBuscarAdministrador($administradorId);
$administrador = mssql_fetch_array($consulta);

$consulta = $objPlanificacion->planBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta);

$consulta = $objPlanificacion->planBuscarDiaCierre($clienteId);
$fecha = mssql_fetch_array($consulta);
?>      

<script type="text/javascript" src="../scripts/tablesort.js"></script>

<script type="text/javascript">
                                                                                                    
    window.addEvent('domready', function() {                  
        
        $('planGrupoNombre').disabled = true;                               
        $('planEvaluacionNombre').disabled = true;            
       
        $('mBoxContainer').setStyle('opacity', '0');         
        $('mBoxContainerOrg').setStyle('opacity', '0'); 
        $('mBoxContainerSec').setStyle('opacity', '0'); 
        $('mBoxContainerAre').setStyle('opacity', '0'); 
        $('mBoxContainerGru').setStyle('opacity', '0'); 
        $('mBoxContainerEva').setStyle('opacity', '0');            
        $('mBoxContainerTema').setStyle('opacity', '0');            
        
        $('lupaGru').setStyle('display', 'none'); 
        $('lupaEva').setStyle('display', 'none');
        
                                                                
        $('aEliminarCap').setStyle('display', 'none'); 
        $('aDescartarUsuarios').setStyle('display', 'none'); 
        $('aAgregarUsuarios').setStyle('display', 'none'); 
        //$('tablaCapsulas').setStyle('display', 'none'); 
        
        $('oUsuarios').setStyle('display', 'none');
        $('oPlanificacion').setStyle('display', 'none');
        $('oCapsulas').setStyle('display', 'none');
                
                                                                            
        new Autocompleter.Request.HTML($('temaNombre'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading',
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'         :   'temaId',
                'nombre_campo'      :   'temaNombre',
                'nombre_tablas'     :   'From Temas t (nolock)',
                'nombre_where'      :   'and clienteId = ' + $('clienteId').get('value') + ' and t.temaEstado = 1'
            }
        });
        
        new Autocompleter.Request.HTML($('planOrganizacionNombre'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading',
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'         :   'organizacionId',
                'nombre_campo'      :   'organizacionNombre',
                'nombre_tablas'     :   'From Organizaciones o (nolock)',
                'nombre_where'      :   'and clienteId = ' + $('clienteId').get('value') + ' and o.organizacionEstado = 1'
            }
        });
        
        new Autocompleter.Request.HTML($('planGrupoNombre'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'         :	'grupoId',
                'nombre_campo'      : 	'grupoNombre',
                'nombre_tablas'     :   'From Grupos g (nolock)',
                'nombre_where'      :   'and clienteId = ' + $('clienteId').get('value') + ' and g.grupoEstado = 1'                                         
            }
        }); 
        
        new Autocompleter.Request.HTML($('planEvaluacionNombre'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'         :	'evaluacionId',
                'nombre_campo'      : 	'evaluacionNombre',
                'nombre_tablas'     :   'From Evaluaciones e (nolock)',
                'nombre_where'      :   'and clienteId = ' + $('clienteId').get('value') + ' and e.evaluacionEstado = 1'                                         
            }
        }); 
                
        $('btnCancelarCapsulas').addEvent('click', function(){

            var property = 'opacity';
            var to = "0";

            $('mBoxContainer').tween(property, to);

        });
                        
        $('btnCancelarOrg').addEvent('click', function(){

            var property = 'opacity';
            var to = "0";

            $('mBoxContainerOrg').tween(property, to);

        });
        
        $('btnCancelarSec').addEvent('click', function(){

            var property = 'opacity';
            var to = "0";

            $('mBoxContainerSec').tween(property, to);

        });
        
        $('btnCancelarAre').addEvent('click', function(){

            var property = 'opacity';
            var to = "0";

            $('mBoxContainerAre').tween(property, to);

        });
        
        $('btnCancelarGru').addEvent('click', function(){

            var property = 'opacity';
            var to = "0";

            $('mBoxContainerGru').tween(property, to);

        });
        
        $('btnCancelarEva').addEvent('click', function(){

            var property = 'opacity';
            var to = "0";

            $('mBoxContainerEva').tween(property, to);

        });
        
        $('btnCancelarTema').addEvent('click', function(){

            var property = 'opacity';
            var to = "0";

            $('mBoxContainerTema').tween(property, to);

        });
        
                                                                                                
    });
                                                                                                                                                                                                
</script>   

<input type="hidden" name="clienteId" id="clienteId" value="<?php echo $clienteId ?>"/>
<input type="hidden" name="administradorId" id="administradorId" value="<?php echo $administradorId ?>"/>
<input type="hidden" name="planificacionId" id="planificacionId" />
<input type="hidden" name="diasCierre" id="diasCierre" value="<?php echo $fecha['plazoDias'] ?>"/>

<div style="min-height: 400px">

    <table width="100%">
        <tr>
            <td align="right">
                <a href="#" class="volver" onclick="location.href = '../planificaciones/planPlanificaciones.php'">Volver</a>
            </td>
        </tr>    
    </table>

    <h4>Ingresar Planificación</h4>

    <br>
    
    <div class="divElementos">

    <div class="divFormulario">
        <table>  
            <tr height="20px">                    
                <td width="100px" display="border: none">Cliente: </td><td display="border: none"><?php echo htmlentities($cliente['clienteNombreCompleto']) ?></td>                    
            </tr>
            <tr height="20px">                    
                <td width="100px" display="border: none">Administrador: </td><td display="border: none"><?php echo htmlentities($administrador['administradorNombreCompleto']) ?></td>                    
            </tr>            
            <tr>
                <td width="100px" display="border: none">Planificación <span class="requerido">&nbsp;*</span>: </td><td display="border: none"><input id="inPlanificacionNombre" name="inPlanificacionNombre" type="text" class="campo" style="text-transform:uppercase;" maxlength="50"/> <label id="oPlanificacion" style="color: #FF0000; font-size: 12px">Debe ingresar un nombre</label></td>                    
            </tr>
            <tr>
                <td width="100px" display="border: none">Descripción: </td><td display="border: none"><input id="inPlanificacionDescripcion" name="inPlanificacionDescripcion" type="text" class="campoDescripcion" maxlength="250"/></td>                    
            </tr>                
        </table>          

    </div>

    <br>
    <div class="divFormulario">

        <font color="#003366">Cápsulas</font> <label id="oCapsulas" style="color: #FF0000; font-size: 12px; font-weight: normal">Debe Agregar al menos una C&aacute;psula</label>

        <br><br>

        <table>
            <tr>
                <td width="70px">
                    Tema:
                </td>
                <td width="250px">
                    <input id="temaNombre" name="temaNombre" type="text" class="campo"/>
                </td>
                <td width="20px">
                    <img id="lupaTema" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="planSeleccionarTemas()"/>
                </td>
                <td valign="top">
                    <div id ="mBoxContainerTema" class="BoxContainer" style="position: absolute;opacity:0;filter:alpha(opacity=0);" height="auto">

                        <div class="BoxTitle">Temas</div>    

                        <div id="BoxContentTema" class="BoxContent">                        

                        </div>

                        <div class="BoxFooterContainer" align="right">

                            <input id="btnCancelarTema" type="button" name="button" class="btn" value="Cancelar" />   
                            <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="planSeleccionarTema()" />   

                        </div>


                    </div>
                </td>
            </tr>
            <tr>
                <td>

                </td>
                <td>

                </td>        
                <td valign="top">
                    <div id ="mBoxContainer" class="BoxContainer" style="position: absolute;opacity:0;filter:alpha(opacity=0);" height="auto">

                        <div class="BoxTitle">Cápsulas</div>    

                        <div id="BoxContent" class="BoxContent" style="min-height: 250px; min-width: 350px">                        

                        </div>

                        <div class="BoxFooterContainer" align="right">

                            <input id="btnCancelarCapsulas" type="button" name="button" class="btn" value="Cancelar" />   
                            <input id="btnAgregarCapsulas" type="button" name="button" class="btn verde" value="Seleccionar" onclick="planAgregarCapsulas()" />   

                        </div>


                    </div>
                </td>
            </tr>
        </table>

        <br>

        <table width="1000px">
            <tr>
                <td width="500px"><a id="aIngresarCapsula" onclick="planSeleccionarCapsula()" style="cursor:pointer; font-size: 11px">Agregar</a>
                    <br>
                    <br>
                </td>                        
            </tr> 

            <tr>
                <td valign="top">
                    <table id="tablaCapsulas" name="tablaCapsulas" class="tabla" >
                        <tr>
                            <th> 
                                <input type="checkbox" onclick="planSeleccionarTodo(this.checked, 'tablaCapsulas')" title="Seleccionar Todo"/>
                            </th>
                            <th width="200px">
                                Tema
                            </th>
                            <th width="250px">
                                Cápsula
                            </th>
                            <th align="center">
                                Fecha envío
                            </th>
                            <th>

                            </th>
                            <th style="display:none" align="center">
                                Fecha cierre
                            </th>
                        </tr>
                    </table>
                </td>            
            </tr>

            <tr>
                <td>
                    <a id="aEliminarCap" onclick="planEliminarFila('tablaCapsulas')" style="cursor:pointer; font-size: 11px">Eliminar</a>
                </td>            
            </tr>

        </table>               

    </div>
    <br>
    <div class="divFormulario">
        <font color="#003366">Usuarios seleccionados</font> <label id="oUsuarios" style="color: #FF0000; font-size: 12px">Debe ingresar al menos un Usuario</label>

        <br>
        <br>                                                   

        <div>
            <table border=0" class="tabla" style="width:990px;cursor:default; margin:0px">
                <tr>
                    <th style="width:1px;">
                        <input type="checkbox" onclick="planSeleccionarTodo(this.checked, 'listaUsuariosSeleccionados')" title="Seleccionar Todo"/>
                    </th>
                    <th onclick="fdTableSort.jsWrapper('listaUsuariosSeleccionados', [1])" style="width:100px;">
                        Nombre
                    </th>                        
                    <th onclick="fdTableSort.jsWrapper('listaUsuariosSeleccionados', [2])" style="width:100px;">
                        Email
                    </th>                        
                    <th onclick="fdTableSort.jsWrapper('listaUsuariosSeleccionados', [3])" style="width:100px;">
                        Organización
                    </th>
                    <th onclick="fdTableSort.jsWrapper('listaUsuariosSeleccionados', [4])"style="width:100px;">
                        Gerencia/Agencia
                    </th>
                    <th onclick="fdTableSort.jsWrapper('listaUsuariosSeleccionados', [5])" style="width:100px;">
                        Área
                    </th>
                    <th onclick="fdTableSort.jsWrapper('listaUsuariosSeleccionados', [6])" style="width:100px;">
                        Cargo
                    </th>
                </tr>
            </table> 

            <div style="max-height:300px;width:1010px;overflow:hidden;overflow-y:auto;">
                <table id="listaUsuariosSeleccionados" border=0" class="tabla" style="width:990px;cursor:default; margin:0px">
                    <thead style="display:none">
                        <tr>
                            <th></th>
                            <th class='sortable'>
                                Nombre
                            </th>                        
                            <th class='sortable'>
                                Email
                            </th>                        
                            <th class='sortable'>
                                Organización
                            </th>
                            <th class='sortable'>
                                Gerencia/Agencia
                            </th>
                            <th class='sortable'>
                                Área
                            </th>
                            <th class='sortable'>
                                Cargo
                            </th>
                        </tr>
                    </thead>
                    <tbody id="listaUsuariosSeleccionadosBody">

                    </tbody>
                </table> 

            </div>
            <br>
            <a id="aDescartarUsuarios" onclick="planDescartarUsuarios()" style="cursor:pointer; font-size: 11px">Descartar usuarios</a>

        </div>
    </div>
    <br>
    <div class="divFormulario">
        <font color="#003366">Agregar usuarios</font>
        <br><br>
        <table>          
            <tr>
                <td valign="top"><input id="radioOrg" type="radio" name="tipoBusqueda" checked="checked" onclick="planBusquedaOrg()"></td>
                <td width="100px" display="border: none">Organización: </td>
                <td display="border: none"><input id="planOrganizacionNombre" name="planOrganizacionNombre" type="text" onchange="planLimpiarInputs('planSectorNombre', 'planAreaNombre', '')" class="campo"/></td>
                <td width="20px"><img id="lupaOrg" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="planSeleccionarOrganizaciones()"/></td>
                <td valign="top">
                    <div id ="mBoxContainerOrg" class="BoxContainer" style="position: absolute;opacity:0;filter:alpha(opacity=0);" height="auto">

                        <div class="BoxTitle">Organizaciones</div>    

                        <div id="BoxContentOrg" class="BoxContent ">                        

                        </div>

                        <div class="BoxFooterContainer" align="right">

                            <input id="btnCancelarOrg" type="button" name="button" class="btn" value="Cancelar" />   
                            <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="planSeleccionarOrganizacion()" />   

                        </div>

                    </div>
                </td>



                <td valign="top"></td>
                <td width="100px" valign="top" align="right"><input id="radioGru" type="radio" name="tipoBusqueda" onclick="planBusquedaGru()"></td>
                <td width="70px" display="border: none" align="left">Grupo: </td>
                <td display="border: none"><input id="planGrupoNombre" name="planGrupoNombre" type="text" class="campo"/></td>
                <td width="20px"><img id="lupaGru" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="planSeleccionarGrupos()"/></td>
                <td valign="top">
                    <div id ="mBoxContainerGru" class="BoxContainer" style="position: absolute; left:700px;opacity:0;filter:alpha(opacity=0);" height="auto">

                        <div class="BoxTitle">Grupos</div>    

                        <div id="BoxContentGru" class="BoxContent">                        

                        </div>

                        <div class="BoxFooterContainer" align="right">

                            <input id="btnCancelarGru" type="button" name="button" class="btn" value="Cancelar" />
                            <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="planSeleccionarGrupo()" />

                        </div>

                    </div>
                </td>

            </tr>  
            <tr>
                <td></td>
                <td width="100px" display="border: none">Gerencia/Agencia: </td>
                <td display="border: none"><input id="planSectorNombre" name="planSectorNombre" type="text" onfocus="planMostrarSectores('planOrganizacionNombre', 'planSectorNombre')" onchange="planLimpiarInputs('planAreaNombre', '', '')" class="campo" maxlength="50"/></td>
                <td width="20px"><img id="lupaSec" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="planSeleccionarSectores()"/></td>
                <td valign="top">
                    <div id ="mBoxContainerSec" class="BoxContainer" style="position: absolute;opacity:0;filter:alpha(opacity=0);" height="auto">

                        <div class="BoxTitle">Gerencias/Agencias</div>    

                        <div id="BoxContentSec" class="BoxContent">                        

                        </div>

                        <div class="BoxFooterContainer" align="right">

                            <input id="btnCancelarSec" type="button" name="button" class="btn" value="Cancelar" />
                            <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="planSeleccionarSector()" />

                        </div>

                    </div>
                </td>

                <td valign="top"></td><td width="100px" valign="top" align="right"><input id="radioEva" type="radio" name="tipoBusqueda" onclick="planBusquedaEva()"></td>
                <td width="70px" display="border: none" align="left">Evaluación: </td>
                <td display="border: none"><input id="planEvaluacionNombre" name="planEvaluacionNombre" type="text" class="campo"/></td>
                <td width="20px"><img id="lupaEva" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="planSeleccionarEvaluaciones()"/></td>
                <td valign="top">
                    <div id ="mBoxContainerEva" class="BoxContainer" style="position: absolute; left:700px;opacity:0;filter:alpha(opacity=0);" height="auto">

                        <div class="BoxTitle">Evaluaciones</div>    

                        <div id="BoxContentEva" class="BoxContent">                        

                        </div>

                        <div class="BoxFooterContainer" align="right">

                            <input id="btnCancelarEva" type="button" name="button" class="btn" value="Cancelar" />
                            <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="planSeleccionarEvaluacion()" />

                        </div>

                    </div>
                </td>

            </tr>
            <tr>
                <td></td>
                <td width="100px" display="border: none">Área: </td>
                <td display="border: none"><input id="planAreaNombre" name="planAreaNombre" type="text" onfocus="planMostrarAreas('planOrganizacionNombre', 'planSectorNombre', 'planAreaNombre')" class="campo" maxlength="250"/></td>
                <td width="20px"><img id="lupaArea" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="planSeleccionarAreas()"/></td>
                <td valign="top">
                    <div id ="mBoxContainerAre" class="BoxContainer" style="position: absolute;opacity:0;filter:alpha(opacity=0);" height="auto">

                        <div class="BoxTitle">Áreas</div>    

                        <div id="BoxContentAre" class="BoxContent">                        

                        </div>

                        <div class="BoxFooterContainer" align="right">

                            <input id="btnCancelarAre" type="button" name="button" class="btn" value="Cancelar" />
                            <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="planSeleccionarArea()" />

                        </div>

                    </div>
                </td>
            </tr> 
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>                
                <td>
                    <input type="button" name="evaBuscar" class="btn" value="Buscar" onclick="planBuscarUsuarios()" />
                </td>
                <td>
                    <table id="tablaBuscarCarga" style="display:none">
                        <tr>
                            <td>
                                <font color="#003366"><i>Buscando...</i></font> 
                            </td>
                            <td width="20px">
                                <img src="../skins/saam/img/loader_2.gif" alt="Buscando" height="20px" />
                            </td>
                        </tr>
                    </table>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>

            </tr>

        </table>            
        <br>
        <div id="listado">
            <table border=0" class="tabla" style="width:990px;cursor:default; margin: 0px">
                <tr>
                    <th style="width:16px;">
                        <input type="checkbox" onclick="planSeleccionarTodo(this.checked, 'ListadeUsuarios')" title="Seleccionar Todo"/>
                    </th>
                    <th onclick="fdTableSort.jsWrapper('ListadeUsuarios', [1])" style="width:150px;">
                        Nombre
                    </th>
                    <th onclick="fdTableSort.jsWrapper('ListadeUsuarios', [2])" style="width:150px;">
                        E-Mail
                    </th>
                    <th onclick="fdTableSort.jsWrapper('ListadeUsuarios', [3])" style="width:150px;">
                        Organización
                    </th>
                    <th onclick="fdTableSort.jsWrapper('ListadeUsuarios', [4])" style="width:150px;">
                        Gerencia/Agencia
                    </th>
                    <th onclick="fdTableSort.jsWrapper('ListadeUsuarios', [5])" style="width:150px;">
                        Área
                    </th>
                    <th onclick="fdTableSort.jsWrapper('ListadeUsuarios', [6])" style="width:150px;">
                        Cargo
                    </th>
                </tr>
            </table>
            <div id="ListaUsuarios" style="max-height:250px;width:1010px;overflow:hidden;overflow-y:auto;">

            </div>
            <br>
            <a id="aAgregarUsuarios" onclick="planSeleccionarUsuarios()" style="cursor:pointer; font-size: 11px">Agregar usuarios</a>
        </div>
    </div>
        
    
    <br>

    <table>
        <tr>
            <td width="400px">
                <p>
                    <br>
                    <input type="button" name="button" class="btn azul" value="Guardar" onclick="planValidarFormulario()" />                                
                    <label></label>                                
                    <input type="button" name="cancelar" class="btn" id="cancelar" value="Cancelar" onclick="location.href = 'planPlanificaciones.php'" />
                    <input type="submit" name="submit" id="btIngresarPlan" style="display: none" onclick="planGuardarPlanificacion()" />
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
    
    </div>
    
    <br>
</div>
<?
mssql_free_result($consulta);

$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>


