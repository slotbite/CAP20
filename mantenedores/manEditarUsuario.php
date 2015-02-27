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

    $usuarioId = mb_convert_encoding(trim($_POST['usuarioId']), "ISO-8859-1", "UTF-8");
    $usuarioEstado = mb_convert_encoding(trim($_POST['usuarioEstado']), "ISO-8859-1", "UTF-8");
    $usuarioRut = mb_convert_encoding(trim($_POST['updUsuarioRut']), "ISO-8859-1", "UTF-8");
    $usuarioNombres = mb_convert_encoding(trim($_POST['updUsuarioNombres']), "ISO-8859-1", "UTF-8");
    $usuarioApellidos = mb_convert_encoding(trim($_POST['updUsuarioApellidos']), "ISO-8859-1", "UTF-8");
    $usuarioEmail = mb_convert_encoding(trim($_POST['updUsuarioEmail']), "ISO-8859-1", "UTF-8");
    $cargoNombre = mb_convert_encoding(trim($_POST['updUsuCargoNombre']), "ISO-8859-1", "UTF-8");
    $organizacionNombre = mb_convert_encoding(trim($_POST['updUsuOrganizacionNombre']), "ISO-8859-1", "UTF-8");
    $sectorNombre = mb_convert_encoding(trim($_POST['updUsuSectorNombre']), "ISO-8859-1", "UTF-8");
    $areaNombre = mb_convert_encoding(trim($_POST['updUsuAreaNombre']), "ISO-8859-1", "UTF-8");

    $resultado = $objUsuario->manEditarUsuario($clienteId, $usuarioId, $usuarioRut, $usuarioNombres, $usuarioApellidos, $usuarioEmail, $cargoNombre, $organizacionNombre, $sectorNombre, $areaNombre, $usuarioEstado, $usuarioModificacion);

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
} else {

    $usuarioId = htmlspecialchars(trim($_POST['usuarioId']));

    $consulta = $objUsuario->manMostrarUsuario($usuarioId);

    if ($consulta) {

        $usuarioEdi = mssql_fetch_array($consulta);

        $usuarioRut = $usuarioEdi['usuarioRut'];
        $usuarioEstado = $usuarioEdi['usuarioEstado'];
        $usuarioNombres = $usuarioEdi['usuarioNombres'];
        $usuarioApellidos = $usuarioEdi['usuarioApellidos'];
        $usuarioEmail = $usuarioEdi['usuarioEmail'];
        $cargoNombre = $usuarioEdi['cargoNombre'];
        $organizacionNombre = $usuarioEdi['organizacionNombre'];
        $sectorNombre = $usuarioEdi['sectorNombre'];
        $areaNombre = $usuarioEdi['areaNombre'];
        $usuarioEstado = $usuarioEdi['usuarioEstado'];        
    }
}
?>


<script type="text/javascript">
                                                                                    
    window.addEvent('domready', function() {
                                                                                                                                
        
        new Autocompleter.Request.HTML($('updUsuCargoNombre'), '../librerias/autocompleterMantenedores.php', {
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
                                                       
        new Autocompleter.Request.HTML($('updUsuOrganizacionNombre'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
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

<h4>Editar Usuario</h4>
<br>

<div class="divElementos">

<div style="min-height: 400px">
    <div id="contenedor">
        <div class="divFormulario">

            <form id="frmUsuEditar" name="frmUsuEditar" method="post" action="manEditarUsuario.php" onKeyPress="return manDisableEnterKey(event)">                                                                                                           
                <input type="hidden" name="usuarioId" id="usuarioId" value="<?php echo htmlentities($usuarioId) ?>"/>                            
                <input type="hidden" name="usuarioEstado" id="usuarioEstado" value="<?php echo htmlentities($usuarioEstado) ?>"/>                            

                <table>
                    <tr>
                        <td width="100px" style="padding-bottom:6px">Cliente: </td>
                        <td style="padding-bottom:6px"><?php echo htmlentities($clienteNombre) ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>RUT <span class="requerido">&nbsp;*</span>:</td>
                        <td><input type="text" name="updUsuarioRut" id="updUsuarioRut" class="campo" style="text-transform:uppercase;" value= "<?php echo htmlentities($usuarioRut) ?>" /></td>
                        <td colspan ="2"><i> Ejemplo: 10000000-k</i></td>
                        <td></td>                        
                        <td><label id="oRut" style="color: #FF0000; font-size: 12px">Debe ingresar el RUT del usuario</label> <label id="oRut2" style="color: #FF0000; font-size: 12px">Formato incorrecto.</label></td>
                    </tr>
                    <tr>
                        <td>Nombres <span class="requerido">&nbsp;*</span>:</td>
                        <td><input type="text" name="updUsuarioNombres" id="updUsuarioNombres" class="campo" maxlength="50" style="text-transform:uppercase;" value= "<?php echo htmlentities($usuarioNombres) ?>" /></td>
                        <td></td>
                        <td><label id="oNombres" style="color: #FF0000; font-size: 12px">Debe ingresar los Nombres del Usuario</label></td>
                    </tr>
                    <tr>
                        <td>Apellidos <span class="requerido">&nbsp;*</span>:</td>
                        <td><input type="text" name="updUsuarioApellidos" id="updUsuarioApellidos" class="campo" maxlength="50" style="text-transform:uppercase;" value= "<?php echo htmlentities($usuarioApellidos) ?>" /></td>
                        <td></td>
                        <td><label id="oApellidos" style="color: #FF0000; font-size: 12px">Debe ingresar los Apellidos del Usuario</label></td>
                    </tr>
                    <tr>
                        <td>Email <span class="requerido">&nbsp;*</span>:</td>
                        <td><input type="text" name="updUsuarioEmail" id="updUsuarioEmail" class="campo" maxlength="50" value= "<?php echo htmlentities($usuarioEmail) ?>" /></td>
                        <td></td>
                        <td><label id="oEmail" style="color: #FF0000; font-size: 12px">Ingrese e-mail del Usuario</label> <label id="oEmail2" style="color: #FF0000; font-size: 12px">Formato incorrecto.</label></td>
                    </tr>
                    <tr>
                        <td>Cargo <span class="requerido">&nbsp;*</span>:</td>
                        <td><input type="text" name="updUsuCargoNombre" id="updUsuCargoNombre" class="campo" value= "<?php echo htmlentities($cargoNombre) ?>" /></td>
                        <td width="20px"><img id="lupaCar" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="manSeleccionarCargos()"/></td>
                        <td><label id="oCargo" style="color: #FF0000; font-size: 12px">Seleccione un cargo para el Usuario</label></td>
                        <td valign="top">
                            <div id ="mBoxContainerCar" class="BoxContainer" style="position: absolute; left:420px; opacity:0;filter:alpha(opacity=0);" height="auto">

                                <div class="BoxTitle">Cargos</div>    

                                <div id="BoxContentCar" class="BoxContent">                        

                                </div>

                                <div class="BoxFooterContainer" align="right">

                                    <input id="btnCancelarCar" type="button" name="button" class="btn" value="Cancelar" />   
                                    <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="manSeleccionarCargo('updUsuCargoNombre')" />   

                                </div>

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Organización <span class="requerido">&nbsp;*</span>:</td>
                        <td><input type="text" name="updUsuOrganizacionNombre" id="updUsuOrganizacionNombre" class="campo" value= "<?php echo htmlentities($organizacionNombre) ?>" /></td>                        <!-- onchange="manLimpiarInputs('updUsuSectorNombre','updUsuAreaNombre','')" -->
                        <td width="20px"><img id="lupaOrg" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="manSeleccionarOrganizaciones()"/></td>
                        <td><label id="oOrganizacion" style="color: #FF0000; font-size: 12px">Seleccione una Organizaci&oacute;n para el Usuario</label></td>
                        <td valign="top">
                            <div id ="mBoxContainerOrg" class="BoxContainer" style="position: absolute; left:420px; opacity:0;filter:alpha(opacity=0);" height="auto">

                                <div class="BoxTitle">Organizaciones</div>    

                                <div id="BoxContentOrg" class="BoxContent">                        

                                </div>

                                <div class="BoxFooterContainer" align="right">

                                    <input id="btnCancelarOrg" type="button" name="button" class="btn" value="Cancelar" />   
                                    <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="manSeleccionarOrganizacion('updUsuOrganizacionNombre')" />   

                                </div>

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Gerencia/Agencia:</td>
                        <td><input type="text" name="updUsuSectorNombre" id="updUsuSectorNombre" onfocus="manMostrarSectores('updUsuOrganizacionNombre', 'updUsuSectorNombre')" class="campo" value= "<?php echo htmlentities($sectorNombre) ?>" /></td>   <!-- onchange="manLimpiarInputs('updUsuAreaNombre','','')" -->
                        <td width="20px"><img id="lupaSec" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="manSeleccionarSectores('updUsuOrganizacionNombre')"/></td>
                        <td></td>
                        <td valign="top">
                            <div id ="mBoxContainerSec" class="BoxContainer" style="position: absolute; left:420px; opacity:0;filter:alpha(opacity=0);" height="auto">

                                <div class="BoxTitle">Gerencias/Agencias</div>    

                                <div id="BoxContentSec" class="BoxContent">                        

                                </div>

                                <div class="BoxFooterContainer" align="right">

                                    <input id="btnCancelarSec" type="button" name="button" class="btn" value="Cancelar" />
                                    <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="manSeleccionarSector('updUsuSectorNombre', 'updUsuOrganizacionNombre')" />

                                </div>

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Área:</td>
                        <td><input type="text" name="updUsuAreaNombre" id="updUsuAreaNombre" onfocus="manMostrarAreas('updUsuOrganizacionNombre', 'updUsuSectorNombre', 'updUsuAreaNombre')" class="campo" value= "<?php echo htmlentities($areaNombre) ?>" /></td>
                        <td width="20px"><img id="lupaSec" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="manSeleccionarAreas('updUsuOrganizacionNombre','updUsuSectorNombre')"/></td>
                        <td></td>
                        <td valign="top">
                            <div id ="mBoxContainerAre" class="BoxContainer" style="position: absolute; left:420px; opacity:0;filter:alpha(opacity=0);" height="auto">

                                <div class="BoxTitle">Áreas</div>    

                                <div id="BoxContentAre" class="BoxContent">                        

                                </div>

                                <div class="BoxFooterContainer" align="right">

                                    <input id="btnCancelarAre" type="button" name="button" class="btn" value="Cancelar" />
                                    <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="manSeleccionarArea('updUsuAreaNombre', 'updUsuSectorNombre', 'updUsuOrganizacionNombre')" />

                                </div>

                            </div>
                        </td>
                        
                    </tr>
                </table>
        </div>
        <p>    
            <br>
            <input type="button" name="button" value="Guardar" class="btn azul" onclick="manValidarFormularioUsuariosEdicion('updUsuarioRut', 'updUsuarioNombres', 'updUsuarioApellidos', 'updUsuarioEmail', 'updUsuCargoNombre', 'updUsuOrganizacionNombre', 'btEditarUsu')" />                                
            <label></label>                                
            <input type="button" name="cancelar" id="cancelar" value="Cancelar" class="btn" onclick="location.href = 'manUsuarios.php'" />
            <input type="submit" name="submit" id="btEditarUsu" value="Editar" style="display: none" /> 
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
