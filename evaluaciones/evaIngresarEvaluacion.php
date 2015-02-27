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

$consulta = $objEvaluacion->evaBuscarAdministrador($administradorId);
$administrador = mssql_fetch_array($consulta);

$consulta = $objEvaluacion->evaBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta)
?>      

<script type="text/javascript" src="../scripts/tablesort.js"></script>

<script type="text/javascript">
                                                                                                    
    window.addEvent('domready', function() {
                
         
        $('oTema').setStyle('display', 'none');
        $('oEvaluacion').setStyle('display', 'none');
        $('oCapsulas').setStyle('display', 'none');
        $('oPracticas').setStyle('display', 'none');  
        $('oUsuarios').setStyle('display', 'none');                               
        $('oTotal').setStyle('display', 'none');  
        
        $('aAgregarUsuarios').setStyle('display', 'none');                       
        $('aDescartarUsuarios').setStyle('display', 'none');                                               
        
        $('aEliminarCap').setStyle('display', 'none');                       
        $('aEliminarPrac').setStyle('display', 'none');                                               
                       
        $('tablaCapsulas').setStyle('display', ''); 
        $('tablaPracticas').setStyle('display', ''); 
                      
        $('mBoxContainerTema').setStyle('opacity', '0');
        $('mBoxContainer').setStyle('opacity', '0');                                                                                                    
        $('mBoxContainerOrg').setStyle('opacity', '0'); 
        $('mBoxContainerSec').setStyle('opacity', '0'); 
        $('mBoxContainerAre').setStyle('opacity', '0'); 
        $('mBoxContainerGru').setStyle('opacity', '0');         
                         
        $('lupaGru').setStyle('display', 'none');                                              
                         
                         
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
                'nombre_where'      :   'and clienteId = ' + $('clienteId').get('value')
            }
        });
        
        
        new Autocompleter.Request.HTML($('evaOrganizacionNombre'), '../librerias/autocompleterMantenedores.php', {
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
        
        new Autocompleter.Request.HTML($('evaGrupoNombre'), '../librerias/autocompleterMantenedores.php', {
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
                        
                        
        $('btnCancelarTema').addEvent('click', function(){

            var property = 'opacity';
            var to = "0";

            $('mBoxContainerTema').tween(property, to);

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
                        
        
    });                
                                                                                                                                                                                                
</script>   

<input type="hidden" name="clienteId" id="clienteId" value="<?php echo $clienteId ?>"/>
<input type="hidden" name="administradorId" id="administradorId" value="<?php echo $administradorId ?>"/>
<input type="hidden" name="evaluacionId" id="evaluacionId"/>

<div style="min-height: 400px">

    <table width="100%">
        <tr>
            <td align="right">
                <a href="#" class="volver" onclick="location.href = '../evaluaciones/evaEvaluaciones.php'">Volver</a>
            </td>
        </tr>    
    </table>

    <h4>Ingresar Evaluación</h4>

    <br>
    
    <div class="divElementos">
    
    <div class="divFormulario">
        <table>  
            <tr height="20px">                    
                <td width="100px">Cliente: </td>
                <td width="200px"><?php echo htmlentities($cliente['clienteNombreCompleto']) ?></td>                         
                <td width="20px"></td>
                <td width="200px"></td>
            </tr>
            <tr height="20px">                    
                <td>Administrador: </td>
                <td><?php echo htmlentities($administrador['administradorNombreCompleto']) ?></td>                    
            </tr>
            <tr>
                <td>Tema <span class="requerido">&nbsp;*</span>: </td>
                <td><input id="temaNombre" name="temaNombre" type="text" class="campo"/></td>                   
                <td>
                    <img id="lupaTema" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="evaSeleccionarTemas()"/>                
                </td>
                <td Valign="top">
                    <label id="oTema" style="color: #FF0000; font-size: 12px">Debe seleccionar un tema</label>
                    <div id ="mBoxContainerTema" class="BoxContainer" style="position: absolute;opacity:0;filter:alpha(opacity=0);" height="auto">

                        <div class="BoxTitle">Temas</div>    

                        <div id="BoxContentTema" class="BoxContent " style="width: auto;">                        

                        </div>

                        <div class="BoxFooterContainer" align="right">

                            <input id="btnCancelarTema" type="button" name="button" class="btn" value="Cancelar" />   
                            <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="evaSeleccionarTema()" />   

                        </div>

                    </div>                
                </td>
            </tr>  
            <tr>
                <td>Evaluación <span class="requerido">&nbsp;*</span>: </td>
                <td><input id="evaluacionNombre" name="evaluacionNombre" type="text" class="campo" style="text-transform:uppercase;" maxlength="50"/> </td>                    
                <td></td>
                <td><label id="oEvaluacion" style="color: #FF0000; font-size: 12px">Debe escribir un nombre para la Evaluaci&oacute;n</label></td>
            </tr>
            <tr>
                <td>Descripción: </td>
                <td colspan="3"><input id="evaluacionDescripcion" name="evaluacionDescripcion" type="text" class="campoDescripcion" maxlength="250"/></td>  
                <td></td>
                <td></td>            
            </tr>                
        </table>          

    </div>

    <br>    

    <div class="divFormulario">    

        <table width="1024px">
            <tr>
                <td width="500px" valign="top">
                    <font color="#003366">Cápsulas</font> <label id="oCapsulas" style="color: #FF0000; font-size: 12px; font-weight: normal">Debe agregar al menos una c&aacute;psula</label>   
                    <br><br>
                </td>
                <td width="500px" valign="top">                

                    <div id ="mBoxContainer" class="BoxContainer" style="position: absolute; left: 400px;opacity:0;filter:alpha(opacity=0);" height="auto">

                        <div class="BoxTitle">Cápsulas</div>    

                        <div id="BoxContent" class="BoxContent " style="min-height: 250px; min-width: 350px">                        

                        </div>

                        <div class="BoxFooterContainer" align="right">

                            <input id="btnCancelarCapsulas" type="button" name="button" class="btn" value="Cancelar" />   
                            <input id="btnAgregarCapsulas" type="button" name="button" class="btn verde" value="Seleccionar" onclick="evaAgregarCapsulas()" />   

                        </div>


                    </div>

                    <font color="#003366">Prácticas</font> <label id="oPracticas" style="color: #FF0000; font-size: 12px; font-weight: normal">(*)</label>
                    <br><br>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <a id="aIngresarCapsula" onclick="evaSeleccionarCapsula()" style="cursor:pointer; font-size: 11px">Agregar</a>
                    <br>
                    <br>
                </td>
                <td>
                    <a id="aIngresarCapsula" onclick="evaAgregarPractica()" style="cursor:pointer; font-size: 11px">Agregar</a> 
                    <br>
                    <br>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <table id="tablaCapsulas" name="tablaCapsulas" class="tabla" >
                        <tr>
                            <th width="16px"> 
                                <input type="checkbox" onclick="evaSeleccionarTodo(this.checked, 'tablaCapsulas')" title="Seleccionar Todo"/>
                            </th>                        
                            <th width="350px">
                                Cápsula
                            </th>
                            <th width="40px" align="center">
                                Ponderación
                            </th>                        
                        </tr>
                        <tr></tr>
                    </table>
                </td>            
                <td valign="top">
                    <table id="tablaPracticas" name="tablaPracticas" class="tabla" >
                        <tr>
                            <th width="16px"> 
                                <input type="checkbox" onclick="evaSeleccionarTodo(this.checked, 'tablaPracticas')" title="Seleccionar Todo"/>
                            </th>                        
                            <th width="350px">
                                Práctica
                            </th>
                            <th width="40px" align="center">
                                Ponderación
                            </th>                        
                        </tr>
                    </table>
                </td> 
            </tr>                

            <tr>
                <td>
                    <a id="aEliminarCap" onclick="evaEliminarFila('tablaCapsulas')" style="cursor:pointer; font-size: 11px">Eliminar</a>
                </td>  
                <td>
                    <a id="aEliminarPrac" onclick="evaEliminarFila('tablaPracticas')" style="cursor:pointer; font-size: 11px">Eliminar</a>
                </td>
            </tr>

        </table>  
        <br>                 

        <table id="tablaTotal">                

            <tr>                    
                <td>                
                    <font color="#003366">Total</font> 
                </td>
                <td>
                    <div style="border: 1px solid #5B74A8; width: 45px">
                        <input id="totalPorcentaje" name="totalPorcentaje" type="text" style="width: 30px; border:none; font-size: 12px; text-align: right; background-color: #EEEEEE" readonly="readonly" value="0"/> % 
                    </div>
                </td>
                <td>
                    <label id="oTotal" style="color: #FF0000; font-size: 12px">La suma total no corresponde al 100%.</label>
                </td>
            </tr>
        </table>

    </div>
    <br>
    <div class="divFormulario">
        <font color="#003366">Usuarios seleccionados</font> <label id="oUsuarios" style="color: #FF0000; font-size: 12px">Debe agregar al menos un usuario</label>

        <br>
        <br>                                                   

        <div>
            <table border=0" class="tabla" style="width:990px;cursor:default; margin:0px">
                <tr>
                    <th style="width:1px;">
                        <input type="checkbox" onclick="evaSeleccionarTodo(this.checked, 'listaUsuariosSeleccionados')" title="Seleccionar Todo"/>
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
            <a id="aDescartarUsuarios" onclick="evaDescartarUsuarios()" style="cursor:pointer; font-size: 11px">Descartar usuarios</a>

        </div>
    </div>
    <br>
    <div class="divFormulario">
        <font color="#003366">Agregar usuarios</font>
        <br><br>                
            <table>          
                <tr>
                    <td valign="top"><input id="radioOrg" type="radio" name="tipoBusqueda" checked="checked" onclick="evaBusquedaOrg()"></td>
                    <td width="100px" display="border: none">Organización: </td>
                    <td display="border: none"><input id="evaOrganizacionNombre" name="evaOrganizacionNombre" type="text" onchange="evaLimpiarInputs('evaSectorNombre', 'evaAreaNombre', '')" class="campo"/></td>
                    <td width="20px"><img id="lupaOrg" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="evaSeleccionarOrganizaciones()"/></td>
                    <td valign="top">
                        <div id ="mBoxContainerOrg" class="BoxContainer" style="position: absolute;opacity:0;filter:alpha(opacity=0);" height="auto">

                            <div class="BoxTitle">Organizaciones</div>    

                            <div id="BoxContentOrg" class="BoxContent " style="width: auto;">                        

                            </div>

                            <div class="BoxFooterContainer" align="right">

                                <input id="btnCancelarOrg" type="button" name="button" class="btn" value="Cancelar" />   
                                <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="evaSeleccionarOrganizacion()" />   

                            </div>

                        </div>
                    </td>



                    <td valign="top"></td>
                    <td width="100px" valign="top" align="right"><input id="radioGru" type="radio" name="tipoBusqueda" onclick="evaBusquedaGru()"></td>
                    <td width="70px" display="border: none" align="left">Grupo: </td>
                    <td display="border: none"><input id="evaGrupoNombre" name="evaGrupoNombre" type="text" class="campo"/></td>
                    <td width="20px"><img id="lupaGru" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="evaSeleccionarGrupos()"/></td>
                    <td valign="top">
                        <div id ="mBoxContainerGru" class="BoxContainer" style="position: absolute; left:700px;opacity:0;filter:alpha(opacity=0);" height="auto">

                            <div class="BoxTitle">Grupos</div>    

                            <div id="BoxContentGru" class="BoxContent " style="width: auto;">                        

                            </div>

                            <div class="BoxFooterContainer" align="right">

                                <input id="btnCancelarGru" type="button" name="button" class="btn" value="Cancelar" />
                                <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="evaSeleccionarGrupo()" />

                            </div>

                        </div>
                    </td>

                </tr>  
                <tr>
                    <td></td>
                    <td width="100px" display="border: none">Gerencia/Agencia: </td>
                    <td display="border: none"><input id="evaSectorNombre" name="evaSectorNombre" type="text" onfocus="evaMostrarSectores('evaOrganizacionNombre', 'evaSectorNombre')" onchange="evaLimpiarInputs('evaAreaNombre', '', '')" class="campo" maxlength="50"/></td>
                    <td width="20px"><img id="lupaSec" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="evaSeleccionarSectores()"/></td>
                    <td valign="top">
                        <div id ="mBoxContainerSec" class="BoxContainer" style="position: absolute;opacity:0;filter:alpha(opacity=0);" height="auto">

                            <div class="BoxTitle">Gerencias/Agencias</div>    

                            <div id="BoxContentSec" class="BoxContent " style="width: auto;">                        

                            </div>

                            <div class="BoxFooterContainer" align="right">

                                <input id="btnCancelarSec" type="button" name="button" class="btn" value="Cancelar" />
                                <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="evaSeleccionarSector()" />

                            </div>

                        </div>
                    </td>               

                </tr>
                <tr>
                    <td></td>
                    <td width="100px" display="border: none">Área: </td>
                    <td display="border: none"><input id="evaAreaNombre" name="evaAreaNombre" type="text" onfocus="evaMostrarAreas('evaOrganizacionNombre', 'evaSectorNombre', 'evaAreaNombre')" class="campo" maxlength="250"/></td>
                    <td width="20px"><img id="lupaArea" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="evaSeleccionarAreas()"/></td>
                    <td valign="top">
                        <div id ="mBoxContainerAre" class="BoxContainer" style="position: absolute;opacity:0;filter:alpha(opacity=0);" height="auto">

                            <div class="BoxTitle">Áreas</div>    

                            <div id="BoxContentAre" class="BoxContent " style="width: auto;">                        

                            </div>

                            <div class="BoxFooterContainer" align="right">

                                <input id="btnCancelarAre" type="button" name="button" class="btn" value="Cancelar" />
                                <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="evaSeleccionarArea()" />

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
                        <input type="button" name="evaBuscar" class="btn" value="Buscar" onclick="evaBuscarUsuarios()" />
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
                </tr>

            </table>            
        

        <br>

        <div id="listado">
            <table border=0" class="tabla" style="width:990px;cursor:default; margin: 0px">
                <tr>
                    <th style="width:16px;">
                        <input type="checkbox" onclick="evaSeleccionarTodo(this.checked, 'ListadeUsuarios')" title="Seleccionar Todo"/>
                    </th>
                    <th onclick="fdTableSort.jsWrapper('ListadeUsuarios', [1])" style="width:100px;">
                        Nombre
                    </th>
                    <th onclick="fdTableSort.jsWrapper('ListadeUsuarios', [2])" style="width:100px;">
                        E-Mail
                    </th>
                    <th onclick="fdTableSort.jsWrapper('ListadeUsuarios', [3])" style="width:100px;">
                        Organización
                    </th>
                    <th onclick="fdTableSort.jsWrapper('ListadeUsuarios', [4])" style="width:100px;">
                        Gerencia/Agencia
                    </th>
                    <th onclick="fdTableSort.jsWrapper('ListadeUsuarios', [5])" style="width:100px;">
                        Área
                    </th>
                    <th onclick="fdTableSort.jsWrapper('ListadeUsuarios', [6])" style="width:100px;">
                        Cargo
                    </th>
                </tr>
            </table>
            <div id="ListaUsuarios" style="max-height:250px;width:1010px;overflow:hidden;overflow-y:auto;">

            </div>
            <br>
                <a id="aAgregarUsuarios" onclick="evaSeleccionarUsuarios()" style="cursor:pointer; font-size: 11px">Agregar usuarios</a>
        </div>                       

    </div>

    <table>
        <tr>
            <td width="400px">
                <p>
                    <br>
                    <input type="button" name="button" class="btn azul" value="Guardar" onclick="evaValidarFormularioEvaluacion()" />                                
                    <label></label>                                
                    <input type="button" name="cancelar" class="btn" id="cancelar" value="Cancelar" onclick="location.href = 'evaEvaluaciones.php'" />
                    <input type="submit" name="submit" id="btIngresarEva" style="display: none" onclick="evaGuardarEvaluacion()" />
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
<?
mssql_free_result($consulta);

$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>


