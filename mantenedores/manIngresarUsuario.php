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

require('clases/usuarios.class.php');
$objUsuario = new usuarios();

$consulta = $objUsuario->manBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta);
$clienteNombre = $cliente['clienteNombreCompleto'];

if (isset($_POST['submit'])) {

    $usuarioRut = mb_convert_encoding(trim($_POST['inUsuarioRut']), "ISO-8859-1", "UTF-8");
    $usuarioNombres = mb_convert_encoding(trim($_POST['inUsuarioNombres']), "ISO-8859-1", "UTF-8");
    $usuarioApellidos = mb_convert_encoding(trim($_POST['inUsuarioApellidos']), "ISO-8859-1", "UTF-8");
    $usuarioEmail = mb_convert_encoding(trim($_POST['inUsuarioEmail']), "ISO-8859-1", "UTF-8");
    $cargoNombre = mb_convert_encoding(trim($_POST['inUsuCargoNombre']), "ISO-8859-1", "UTF-8");
    $organizacionNombre = mb_convert_encoding(trim($_POST['inUsuOrganizacionNombre']), "ISO-8859-1", "UTF-8");
    $sectorNombre = mb_convert_encoding(trim($_POST['inUsuSectorNombre']), "ISO-8859-1", "UTF-8");
    $areaNombre = mb_convert_encoding(trim($_POST['inUsuAreaNombre']), "ISO-8859-1", "UTF-8");
    $usuarioEstado = "1";

    $resultado = $objUsuario->manInsertarUsuario($clienteId, $usuarioRut, $usuarioNombres, $usuarioApellidos, $usuarioEmail, $cargoNombre, $organizacionNombre, $sectorNombre, $areaNombre, $usuarioEstado, $usuarioModificacion);

    $usuarioRut = str_replace("\'", "'", $usuarioRut);
    $usuarioRut = str_replace('\"', '"', $usuarioRut);

    $usuarioNombres = str_replace("\'", "'", $usuarioNombres);
    $usuarioNombres = str_replace('\"', '"', $usuarioNombres);

    $usuarioApellidos = str_replace("\'", "'", $usuarioApellidos);
    $usuarioApellidos = str_replace('\"', '"', $usuarioApellidos);

    $usuarioEmail = str_replace("\'", "'", $usuarioEmail);
    $usuarioEmail = str_replace('\"', '"', $usuarioEmail);

    $cargoNombre = str_replace("\'", "'", $cargoNombre);
    $cargoNombre = str_replace('\"', '"', $cargoNombre);

    $organizacionNombre = str_replace("\'", "'", $organizacionNombre);
    $organizacionNombre = str_replace('\"', '"', $organizacionNombre);

    $sectorNombre = str_replace("\'", "'", $sectorNombre);
    $sectorNombre = str_replace('\"', '"', $sectorNombre);

    $areaNombre = str_replace("\'", "'", $areaNombre);
    $areaNombre = str_replace('\"', '"', $areaNombre);

    if ($resultado) {
        if ($resultado['estado'] == "1") {
            ?>                        
            <script language="JavaScript">       
                location.href = "manUsuarios.php";
            </script>
            <?
        } else if ($resultado['estado'] == "0") {
            ?>                                                            
            <script language="JavaScript">       
                alert("Ya existe el usuario para la organización seleccionada.");
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
                alert("El cargo seleccionado no existe.");
            </script>                        
            <?
        } else if ($resultado['estado'] == "4") {
            ?>                                                            
            <script language="JavaScript">       
                alert("La gerencia/agencia seleccionada no existe.");
            </script>                        
            <?
        } else if ($resultado['estado'] == "5") {
            ?>                                                            
            <script language="JavaScript">       
                alert("El área seleccionada no existe.");
            </script>                        
            <?
        } else {
            echo 'Se produjo un error. Intente nuevamente.';
        }
    } else {
        echo 'Se produjo un error. Intente nuevamente.';
    }
}
?>


<script type="text/javascript">
                                                                                    
    window.addEvent('domready', function() {
                                                                                                                                
        
        new Autocompleter.Request.HTML($('inUsuCargoNombre'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'	:   'cargoId',
                'nombre_campo' 	:   'cargoNombre',
                'nombre_tablas' :   'From Cargos c (nolock)',
                'nombre_where'  :   'and clienteId = ' + $('clienteId').get('value') + ' and cargoEstado = 1'                                       
            }
        });
                                                       
        new Autocompleter.Request.HTML($('inUsuOrganizacionNombre'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP cod
            'postData': {
                'nombre_id'	: 'organizacionId',
                'nombre_campo' 	: 'organizacionNombre',
                'nombre_tablas' : 'From Organizaciones o (nolock)',
                'nombre_where'  : 'and clienteId = ' + $('clienteId').get('value') + ' and organizacionEstado = 1'
            }
        });
                                                                                                                            
    });                
                                                                                    
</script>             

<script type="text/javascript">
                                                                                                    
    window.addEvent('domready', function() {  
        
        $('oRut').setStyle('display', 'none');
        $('oRut2').setStyle('display', 'none');
        $('oNombres').setStyle('display', 'none');
        $('oApellidos').setStyle('display', 'none');
        $('oEmail').setStyle('display', 'none');
        $('oEmail2').setStyle('display', 'none');
        $('oCargo').setStyle('display', 'none');
        $('oOrganizacion').setStyle('display', 'none');
        
        
        $('mBoxContainerOrg').setStyle('opacity', '0');
        $('mBoxContainerSec').setStyle('opacity', '0');
        $('mBoxContainerAre').setStyle('opacity', '0');
        $('mBoxContainerCar').setStyle('opacity', '0');
        
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
        
        $('btnCancelarCar').addEvent('click', function(){

            var property = 'opacity';
            var to = "0";

            $('mBoxContainerCar').tween(property, to);

        });
        
        
    });
    
</script>            



<table width="100%">
    <tr>
        <td align="right">
            <a href="manUsuarios.php" class="volver">Volver</a>
        </td>
    </tr>    
</table>
<input type="hidden" name="clienteId" id="clienteId" value="<?php echo htmlentities($clienteId) ?>"/>

<h4>Ingresar Usuario</h4>
<br>

<div class="divElementos">

<div style="min-height: 400px">
    <div id="contenedor">
        <div class="divFormulario">

            <form id="frmUsuIngresar" name="frmUsuIngresar" method="post" action="manIngresarUsuario.php" onKeyPress="return manDisableEnterKey(event)">                                                                                                           

                <table>
                    <tr>
                        <td width="100px" style="padding-bottom:6px">Cliente: </td>
                        <td style="padding-bottom:6px"><?php echo htmlentities($clienteNombre) ?></td>                    
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>RUT <span class="requerido">&nbsp;*</span>:</td>
                        <td><input type="text" name="inUsuarioRut" id="inUsuarioRut" class="campo" style="text-transform:uppercase;" value= "<?php echo htmlentities($usuarioRut) ?>" /></td>
                        <td colspan ="2"><i> Ejemplo: 10000000-k</i></td>
                        <td></td>                                                
                        <td><label id="oRut" style="color: #FF0000; font-size: 12px">Debe ingresar el RUT del usuario</label> <label id="oRut2" style="color: #FF0000; font-size: 12px">Formato incorrecto.</label></td>
                    </tr>
                    <tr>
                        <td>Nombres <span class="requerido">&nbsp;*</span>:</td>
                        <td><input type="text" name="inUsuarioNombres" id="inUsuarioNombres" class="campo" maxlength="50" style="text-transform:uppercase;" value= "<?php echo htmlentities($usuarioNombres) ?>" /></td>
                        <td></td>
                        <td><label id="oNombres" style="color: #FF0000; font-size: 12px">Debe ingresar los Nombres del Usuario</label></td>
                    </tr>
                    <tr>
                        <td>Apellidos <span class="requerido">&nbsp;*</span>:</td>
                        <td><input type="text" name="inUsuarioApellidos" id="inUsuarioApellidos" class="campo" maxlength="50" style="text-transform:uppercase;" value= "<?php echo htmlentities($usuarioApellidos) ?>" /></td>
                        <td></td>
                        <td><label id="oApellidos" style="color: #FF0000; font-size: 12px">Debe ingresar los Apellidos del Usuario</label></td>
                    </tr>
                    <tr>
                        <td>Email <span class="requerido">&nbsp;*</span>:</td>
                        <td><input type="text" name="inUsuarioEmail" id="inUsuarioEmail" class="campo" maxlength="50" value= "<?php echo htmlentities($usuarioEmail) ?>" /></td>
                        <td></td>
                        <td><label id="oEmail" style="color: #FF0000; font-size: 12px">Ingrese e-mail del Usuario</label> <label id="oEmail2" style="color: #FF0000; font-size: 12px">Formato incorrecto.</label></td>
                    </tr>
                    <tr>
                        <td>Cargo <span class="requerido">&nbsp;*</span>:</td>
                        <td><input type="text" name="inUsuCargoNombre" id="inUsuCargoNombre" class="campo" value= "<?php echo htmlentities($cargoNombre) ?>" /></td>
                        <td width="20px"><img id="lupaCar" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="manSeleccionarCargos()"/></td>
                        <td><label id="oCargo" style="color: #FF0000; font-size: 12px">Seleccione un cargo para el Usuario</label></td>
                        <td valign="top">
                            <div id ="mBoxContainerCar" class="BoxContainer" style="position: absolute; left:420px; opacity:0;filter:alpha(opacity=0);" height="auto">

                                <div class="BoxTitle">Cargos</div>    

                                <div id="BoxContentCar" class="BoxContent">                        

                                </div>

                                <div class="BoxFooterContainer" align="right">

                                    <input id="btnCancelarCar" type="button" name="button" class="btn" value="Cancelar" />   
                                    <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="manSeleccionarCargo('inUsuCargoNombre')" />   

                                </div>

                            </div>
                        </td>
                        
                    </tr>
                    <tr>
                        <td>Organización <span class="requerido">&nbsp;*</span>:</td>
                        <td><input type="text" name="inUsuOrganizacionNombre" id="inUsuOrganizacionNombre" class="campo" value= "<?php echo htmlentities($organizacionNombre) ?>" /></td>                         <!--change="manLimpiarInputs('inUsuSectorNombre','inUsuAreaNombre','')" -->
                        <td width="20px"><img id="lupaOrg" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="manSeleccionarOrganizaciones()"/></td>
                        <td><label id="oOrganizacion" style="color: #FF0000; font-size: 12px">Seleccione una Organizaci&oacute;n para el Usuario</label></td>
                        <td valign="top">
                            <div id ="mBoxContainerOrg" class="BoxContainer" style="position: absolute; left:420px; opacity:0;filter:alpha(opacity=0);" height="auto">

                                <div class="BoxTitle">Organizaciones</div>    

                                <div id="BoxContentOrg" class="BoxContent">                        

                                </div>

                                <div class="BoxFooterContainer" align="right">

                                    <input id="btnCancelarOrg" type="button" name="button" class="btn" value="Cancelar" />   
                                    <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="manSeleccionarOrganizacion('inUsuOrganizacionNombre')" />   

                                </div>

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Gerencia/Agencia:</td>
                        <td><input type="text" name="inUsuSectorNombre" id="inUsuSectorNombre" onfocus="manMostrarSectores('inUsuOrganizacionNombre', 'inUsuSectorNombre')" class="campo" value= "<?php echo htmlentities($sectorNombre) ?>" /></td>                           <!-- onchange="manLimpiarInputs('inUsuAreaNombre','','')"-->
                        <td width="20px"><img id="lupaSec" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="manSeleccionarSectores('inUsuOrganizacionNombre')"/></td>
                        <td></td>
                        <td valign="top">
                            <div id ="mBoxContainerSec" class="BoxContainer" style="position: absolute; left:420px; opacity:0;filter:alpha(opacity=0);" height="auto">

                                <div class="BoxTitle">Gerencias/Agencias</div>    

                                <div id="BoxContentSec" class="BoxContent">                        

                                </div>

                                <div class="BoxFooterContainer" align="right">

                                    <input id="btnCancelarSec" type="button" name="button" class="btn" value="Cancelar" />
                                    <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="manSeleccionarSector('inUsuSectorNombre', 'inUsuOrganizacionNombre')" />

                                </div>

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Área:</td>
                        <td><input type="text" name="inUsuAreaNombre" id="inUsuAreaNombre" onfocus="manMostrarAreas('inUsuOrganizacionNombre', 'inUsuSectorNombre', 'inUsuAreaNombre')" class="campo" value= "<?php echo htmlentities($areaNombre) ?>" /></td>
                        <td width="20px"><img id="lupaSec" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="manSeleccionarAreas('inUsuOrganizacionNombre','inUsuSectorNombre')"/></td>
                        <td></td>
                        <td valign="top">
                            <div id ="mBoxContainerAre" class="BoxContainer" style="position: absolute; left:420px; opacity:0;filter:alpha(opacity=0);" height="auto">

                                <div class="BoxTitle">Áreas</div>    

                                <div id="BoxContentAre" class="BoxContent">                        

                                </div>

                                <div class="BoxFooterContainer" align="right">

                                    <input id="btnCancelarAre" type="button" name="button" class="btn" value="Cancelar" />
                                    <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="manSeleccionarArea('inUsuAreaNombre', 'inUsuSectorNombre', 'inUsuOrganizacionNombre')" />

                                </div>

                            </div>
                        </td>
                    </tr>
                </table>
        </div>
        <p>     
            <br>
            <input type="button" name="button" value="Ingresar" class="btn azul" onclick="manValidarFormularioUsuarios('inUsuarioRut', 'inUsuarioNombres', 'inUsuarioApellidos', 'inUsuarioEmail', 'inUsuCargoNombre', 'inUsuOrganizacionNombre', 'btIngresarUsu')" />                                
            <label></label>                                
            <input type="button" name="cancelar" id="cancelar" value="Cancelar" class="btn" onclick="location.href = 'manUsuarios.php'" />
            <input type="submit" name="submit" id="btIngresarUsu" value="Ingresar" style="display: none" /> 
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
