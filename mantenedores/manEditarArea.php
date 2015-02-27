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
    echo "<script>window.location='../';</script>";
}

require('clases/areas.class.php');
$objArea = new areas();

$consulta = $objArea->manBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta);
$clienteNombre = $cliente['clienteNombreCompleto'];


if (isset($_POST['submit'])) {

    $areaId = mb_convert_encoding(trim($_POST['areaId']), "ISO-8859-1", "UTF-8");
    $areaEstado = mb_convert_encoding(trim($_POST['areaEstado']), "ISO-8859-1", "UTF-8");
    $organizacionNombre = mb_convert_encoding(trim($_POST['updArOrganizacionNombre']), "ISO-8859-1", "UTF-8");
    $sectorNombre = mb_convert_encoding(trim($_POST['updArSectorNombre']), "ISO-8859-1", "UTF-8");
    $areaNombre = mb_convert_encoding(trim($_POST['updArAreaNombre']), "ISO-8859-1", "UTF-8");
    $areaDescripcion = mb_convert_encoding(trim($_POST['updArAreaDescripcion']), "ISO-8859-1", "UTF-8");


    $resultado = $objArea->manEditarArea($clienteId, $areaId, $organizacionNombre, $sectorNombre, $areaNombre, $areaDescripcion, $areaEstado, $usuarioModificacion);

    $organizacionNombre = str_replace("\'", "'", $organizacionNombre);
    $organizacionNombre = str_replace('\"', '"', $organizacionNombre);

    $sectorNombre = str_replace("\'", "'", $sectorNombre);
    $sectorNombre = str_replace('\"', '"', $sectorNombre);

    $areaNombre = str_replace("\'", "'", $areaNombre);
    $areaNombre = str_replace('\"', '"', $areaNombre);

    $areaDescripcion = str_replace("\'", "'", $areaDescripcion);
    $areaDescripcion = str_replace('\"', '"', $areaDescripcion);

    if ($resultado) {
        if ($resultado['estado'] == "1") {
            ?>                        
            <script language="JavaScript">       
                location.href = "manAreas.php";
            </script>
            <?
        } else if ($resultado['estado'] == "0") {
            ?>                                                            
            <script language="JavaScript">       
                alert("Ya existe el área para la gerencia/agencia y organización seleccionadas.");
            </script>                        
            <?
        } else if ($resultado['estado'] == "2") {
            ?>                                                            
            <script language="JavaScript">       
                alert("La organización seleccionada no existe.");
            </script>                        
            <?
        } else if ($resultado['estado'] == "3") {
            ?>                                                            
            <script language="JavaScript">       
                alert("La gerencia/agencia seleccionada no existe.");
            </script>                        
            <?
        } else {
            echo 'Se produjo un error. Intente nuevamente.';
        }
    } else {
        echo 'Se produjo un error. Intente nuevamente.';
    }
} else {

    $areaId = htmlspecialchars(trim($_POST['areaId']));

    $consulta = $objArea->manMostrarArea($areaId);

    if ($consulta) {

        $area = mssql_fetch_array($consulta);

        $organizacionNombre = $area['organizacionNombre'];
        $sectorNombre = $area['sectorNombre'];
        $areaNombre = $area['areaNombre'];
        $areaDescripcion = $area['areaDescripcion'];
        $areaEstado = $area['areaEstado'];
    }
}
?>


<script type="text/javascript">
                                    
    window.addEvent('domready', function() {
                                                                                                                  
        new Autocompleter.Request.HTML($('updArOrganizacionNombre'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'		: 'organizacionId',
                'nombre_campo'          : 'organizacionNombre',
                'nombre_tablas' 	: 'From Organizaciones o (nolock)',
                'nombre_where'      :   'and clienteId = ' + $('clienteId').get('value')  + ' and o.organizacionEstado = 1'                                        
                /*'nombre_estado'         :       'organizacionEstado'
                 */
            }
        });
                                                             
    });                
                                    
</script>       

<script type="text/javascript">
                                                                                                    
    window.addEvent('domready', function() {  
        
        $('oOrganizacion').setStyle('display', 'none');
        $('oSector').setStyle('display', 'none');        
        $('oArea').setStyle('display', 'none'); 
        
        $('mBoxContainerOrg').setStyle('opacity', '0');
        $('mBoxContainerSec').setStyle('opacity', '0');
        
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
        
    });
    
</script>


<table width="100%">
    <tr>
        <td align="right">
            <a href="manAreas.php" class="volver">Volver</a>
        </td>
    </tr>    
</table>
<input type="hidden" name="clienteId" id="clienteId" value="<?php echo htmlentities($clienteId) ?>"/>

<h4>Editar Área</h4>
<br>

<div class="divElementos">

<div style="min-height: 400px">
    <div id="contenedor">

        <div class="divFormulario">

            <form id="frmArIngresar" name="frmArIngresar" method="post" action="manEditarArea.php" onKeyPress="return manDisableEnterKey(event)">

                <input type="hidden" name="areaId" id="areaId" value="<?php echo htmlentities($areaId) ?>"/>
                <input type="hidden" name="areaEstado" id="areaEstado" value="<?php echo htmlentities($areaEstado) ?>"/>

                <table>
                    <tr>
                        <td width="120px" style="padding-bottom:6px">Cliente: </td>
                        <td width="120px" style="padding-bottom:6px"><?php echo htmlentities($clienteNombre) ?></td> 
                        <td width="20px"></td>
                        <td width="200px"></td>
                        <td width="200px"></td>
                    </tr>
                    <tr>
                        <td>Organización <span class="requerido">&nbsp;*</span>:</td>
                        <td><input type="text" name="updArOrganizacionNombre" id="updArOrganizacionNombre" class="campo" style="text-transform:uppercase;" maxlength="50" value= "<?php echo htmlentities($organizacionNombre) ?>" onchange="manLimpiarInputs('updArSectorNombre','','')" /></td>
                        <td width="20px"><img id="lupaOrg" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="manSeleccionarOrganizaciones()"/></td>
                        <td><label id="oOrganizacion" style="color: #FF0000; font-size: 12px">Debe seleccionar una Organizaci&oacute;n</label></td>
                        <td valign="top">
                            <div id ="mBoxContainerOrg" class="BoxContainer" style="position: absolute;opacity:0;filter:alpha(opacity=0);" height="auto">

                                <div class="BoxTitle">Organizaciones</div>    

                                <div id="BoxContentOrg" class="BoxContent">                        

                                </div>

                                <div class="BoxFooterContainer" align="right">

                                    <input id="btnCancelarOrg" type="button" name="button" class="btn" value="Cancelar" />   
                                    <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="manSeleccionarOrganizacion('updArOrganizacionNombre')" />   

                                </div>

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Gerencia/Agencia <span class="requerido">&nbsp;*</span>:</td>
                        <td><input type="text" name="updArSectorNombre" id="updArSectorNombre" class="campo" style="text-transform:uppercase;" maxlength="50" value= "<?php echo htmlentities($sectorNombre) ?>" onfocus="manMostrarSectores('updArOrganizacionNombre','updArSectorNombre')" /></td>
                        <td width="20px"><img id="lupaSec" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="manSeleccionarSectores('updArOrganizacionNombre')"/></td>
                        <td><label id="oSector" style="color: #FF0000; font-size: 12px">Debe seleccionar una Gerencia/Agencia</label></td>
                        <td valign="top">
                            <div id ="mBoxContainerSec" class="BoxContainer" style="position: absolute;opacity:0;filter:alpha(opacity=0);" height="auto">

                                <div class="BoxTitle">Gerencias/Agencias</div>    

                                <div id="BoxContentSec" class="BoxContent">                        

                                </div>

                                <div class="BoxFooterContainer" align="right">

                                    <input id="btnCancelarSec" type="button" name="button" class="btn" value="Cancelar" />
                                    <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="manSeleccionarSector('updArSectorNombre', 'updArOrganizacionNombre')" />

                                </div>

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Área <span class="requerido">&nbsp;*</span>:</td>
                        <td><input type="text" name="updArAreaNombre" id="updArAreaNombre" class="campo" style="text-transform:uppercase;" maxlength="50" value= "<?php echo htmlentities($areaNombre) ?>" /> </td>
                        <td></td>
                        <td><label id="oArea" style="color: #FF0000; font-size: 12px">Debe ingresar un nombre para el &Aacute;rea</label></td>
                    </tr>
                    <tr>
                        <td>Descripción:</td>
                        <td colspan="3"><input type="text" name="updArAreaDescripcion" id="updArAreaDescripcion" class="campoDescripcion" maxlength="250" value= "<?php echo htmlentities($areaDescripcion) ?>" /></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                </table>                            
        </div>
        <p>    
            <br>
            <input type="button" name="button" value="Guardar" class="btn azul" onclick="manValidarFormularioAreasEdicion('updArOrganizacionNombre','updArSectorNombre','updArAreaNombre','btEditarAr')" />                                
            <label></label>                                
            <input type="button" name="cancelar" id="cancelar" value="Cancelar" class="btn" onclick="location.href = 'manAreas.php'" />
            <input type="submit" name="submit" id="btEditarAr" value="Ingresar" style="display: none" />
        </p>

        </form>

    </div>
</div>

</div>
    
<?
mssql_free_result($consulta);

$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>