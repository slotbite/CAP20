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
$usuarioModificacion = $_SESSION['usuario'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}


require('clases/sectores.class.php');
$objSector = new sectores;

$consulta = $objSector->manBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta);
$clienteNombre = $cliente['clienteNombreCompleto'];
?>

<script type="text/javascript">
                                        
    window.addEvent('domready', function() {
        
        $('oGrupo').setStyle('display', 'none');
        $('oUsuarios').setStyle('display', 'none');
        
        $('aAgregarUsuarios').setStyle('display', 'none');
        $('aDescartarUsuarios').setStyle('display', 'none');
        
        $('mBoxContainerOrg').setStyle('opacity', '0'); 
        $('mBoxContainerSec').setStyle('opacity', '0'); 
        $('mBoxContainerAre').setStyle('opacity', '0');
                                                                                                                              
        new Autocompleter.Request.HTML($('gruOrganizacionNombre'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'         :	'organizacionId',
                'nombre_campo'      : 	'organizacionNombre',
                'nombre_tablas'     :   'From Organizaciones o (nolock)',
                'nombre_where'      :   'and clienteId = ' + $('clienteId').get('value') + ' and o.organizacionEstado = 1'
            }
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
        
        
                                                                                 
    });                
                                        
</script>

<script type="text/javascript" src="../scripts/tablesort.js"></script>

<table width="100%">
    <tr>
        <td align="right">
            <a href="manGrupos.php" class="volver">Volver</a>
        </td>
    </tr>    
</table>
<input type="hidden" name="clienteId" id="clienteId" value="<?php echo htmlentities($clienteId) ?>"/>
<input type="hidden" name="grupoId" id="grupoId"/>

<h4>Ingresar Grupo</h4>
<br>

<div class="divElementos">

<div style="min-height: 400px">
    <div id="contenedor">

        <div class="divFormulario">


            <table>
                <tr>
                    <td width="100px" style="padding-bottom:6px">Cliente: </td><td style="padding-bottom:6px"><?php echo htmlentities($clienteNombre) ?></td>                    
                </tr>
                <tr>
                    <td>Grupo <span class="requerido">&nbsp;*</span>:</td><td><input type="text" name="inGrupoNombre" id="inGrupoNombre" class="campo" style="text-transform:uppercase;" maxlength="50" /> <label id="oGrupo" style="color: #FF0000; font-size: 12px">Debe ingresar un nombre para el Grupo</label></td>
                </tr>                    
                <td>Descripción:</td><td><input type="text" name="inGrupoDescripcion" id="inGrupoDescripcion" class="campoDescripcion" maxlength="250" /></td>
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
                        <th style="width:16px;">
                            <input type="checkbox" onclick="manSeleccionarTodo(this.checked, 'listaUsuariosSeleccionados')" title="Seleccionar Todo"/>
                        </th>
                        <th onclick="fdTableSort.jsWrapper('listaUsuariosSeleccionados', [1])" style="width:140px;">
                            Nombre
                        </th>                        
                        <th onclick="fdTableSort.jsWrapper('listaUsuariosSeleccionados', [2])" style="width:140px;">
                            Organización
                        </th>
                        <th onclick="fdTableSort.jsWrapper('listaUsuariosSeleccionados', [3])"style="width:140px;">
                            Gerencia/Agencia
                        </th>
                        <th onclick="fdTableSort.jsWrapper('listaUsuariosSeleccionados', [4])" style="width:140px;">
                            Área
                        </th>
                        <th onclick="fdTableSort.jsWrapper('listaUsuariosSeleccionados', [5])" style="width:140px;">
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
                <a id="aDescartarUsuarios" onclick="manDescartarUsuarios()" style="cursor:pointer; font-size: 11px">Descartar usuarios</a>

            </div>
        </div>
        <br>                      
        <div class="divFormulario">    
            <font color="#003366">Agregar usuarios</font>
            <br><br>
            <table>          
                <tr>
                    <td valign="top"></td>
                    <td width="100px" display="border: none">Organización: </td>
                    <td display="border: none"><input id="gruOrganizacionNombre" name="gruOrganizacionNombre" onchange="manLimpiarInputs('gruSectorNombre','gruAreaNombre','')" type="text" class="campo"/></td>
                    <td width="20px"><img id="lupaOrg" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="manSeleccionarOrganizaciones()"/></td>
                    <td valign="top">
                        <div id ="mBoxContainerOrg" class="BoxContainer" style="position: absolute;opacity:0;filter:alpha(opacity=0);" height="auto">

                            <div class="BoxTitle">Organizaciones</div>    

                            <div id="BoxContentOrg" class="BoxContent ">                        

                            </div>

                            <div class="BoxFooterContainer" align="right">

                                <input id="btnCancelarOrg" type="button" name="button" class="btn" value="Cancelar" />   
                                <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="manSeleccionarOrganizacion('gruOrganizacionNombre')" />   

                            </div>

                        </div>
                    </td>            
                </tr>  
                <tr>
                    <td></td>
                    <td width="100px" display="border: none">Gerencia/Agencia: </td>
                    <td display="border: none"><input id="gruSectorNombre" name="gruSectorNombre" type="text" onchange="manLimpiarInputs('gruAreaNombre','','')" onfocus="manMostrarSectores('gruOrganizacionNombre', 'gruSectorNombre')" class="campo" maxlength="50"/></td>
                    <td width="20px"><img id="lupaSec" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="manSeleccionarSectores('gruOrganizacionNombre')"/></td>
                    <td valign="top">
                        <div id ="mBoxContainerSec" class="BoxContainer" style="position: absolute;opacity:0;filter:alpha(opacity=0);" height="auto">

                            <div class="BoxTitle">Gerencias/Agencias</div>    

                            <div id="BoxContentSec" class="BoxContent ">                        

                            </div>

                            <div class="BoxFooterContainer" align="right">

                                <input id="btnCancelarSec" type="button" name="button" class="btn" value="Cancelar" />
                                <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="manSeleccionarSector('gruSectorNombre', 'gruOrganizacionNombre')" />

                            </div>

                        </div>
                    </td>               

                </tr>
                <tr>
                    <td></td>
                    <td width="100px" display="border: none">Área: </td>
                    <td display="border: none"><input id="gruAreaNombre" name="gruAreaNombre" type="text" onfocus="manMostrarAreas('gruOrganizacionNombre', 'gruSectorNombre', 'gruAreaNombre')" class="campo" maxlength="250"/></td>
                    <td width="20px"><img id="lupaArea" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="manSeleccionarAreas('gruOrganizacionNombre','gruSectorNombre')"/></td>
                    <td valign="top">
                        <div id ="mBoxContainerAre" class="BoxContainer" style="position: absolute;opacity:0;filter:alpha(opacity=0);" height="auto">

                            <div class="BoxTitle">Áreas</div>    

                            <div id="BoxContentAre" class="BoxContent ">                        

                            </div>

                            <div class="BoxFooterContainer" align="right">

                                <input id="btnCancelarAre" type="button" name="button" class="btn" value="Cancelar" />
                                <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="manSeleccionarArea('gruAreaNombre', 'gruSectorNombre', 'gruOrganizacionNombre')" />

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
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td width="100px">
                        <input type="button" name="evaBuscar" class="btn" value="Buscar" onclick="manBuscarUsuarios('gruOrganizacionNombre','gruSectorNombre','gruAreaNombre')" />
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
                </tr>

            </table>    

            <br>

            <div>
                <table border=0" class="tabla" style="width:990px;cursor:default; margin: 0px">
                    <tr>
                        <th style="width:16px;">
                            <input type="checkbox" onclick="manSeleccionarTodo(this.checked, 'ListadeUsuarios')" title="Seleccionar Todo"/>
                        </th>
                        <th onclick="fdTableSort.jsWrapper('ListadeUsuarios', [1])" style="width:150px;">
                            Nombre
                        </th>                        
                        <th onclick="fdTableSort.jsWrapper('ListadeUsuarios', [2])" style="width:150px;">
                            Organización
                        </th>
                        <th onclick="fdTableSort.jsWrapper('ListadeUsuarios', [3])" style="width:150px;">
                            Gerencia/Agencia
                        </th>
                        <th onclick="fdTableSort.jsWrapper('ListadeUsuarios', [4])" style="width:150px;">
                            Área
                        </th>
                        <th onclick="fdTableSort.jsWrapper('ListadeUsuarios', [5])" style="width:150px;">
                            Cargo
                        </th>
                    </tr>
                </table>
                <div id="ListaUsuarios" style="max-height:200px;width:1010px;overflow:hidden;overflow-y:auto;">

                </div>
                <br>
                <a id="aAgregarUsuarios" onclick="manSeleccionarUsuarios()" style="cursor:pointer; font-size: 11px">Agregar usuarios</a>
            </div>
        </div>

        <table>
            <tr>
                <td width="400px">
                    <p>
                        <br>
                        <input type="button" name="button" value="Ingresar" class="btn azul" onclick="manValidarFormularioGrupos('inGrupoNombre', 'btIngresarGru')" />                                
                        <label></label>                                
                        <input type="button" name="cancelar" id="cancelar" value="Cancelar" class="btn" onclick="location.href = 'manGrupos.php'" />
                        <input type="submit" name="submit" id="btIngresarGru" value="Ingresar" style="display: none" onclick="manGuardarGrupo('inGrupoNombre','inGrupoDescripcion')"/>
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

</div>
    
<?
mssql_free_result($consulta);

$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>
    