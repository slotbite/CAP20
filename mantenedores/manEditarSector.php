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

require('clases/sectores.class.php');
$objSector = new sectores;

$consulta = $objSector->manBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta);
$clienteNombre = $cliente['clienteNombreCompleto'];

if (isset($_POST['submit'])) {

    $sectorId = mb_convert_encoding(trim($_POST['sectorId']), "ISO-8859-1", "UTF-8");
    $sectorEstado = mb_convert_encoding(trim($_POST['sectorEstado']), "ISO-8859-1", "UTF-8");
    $organizacionNombre = mb_convert_encoding(trim($_POST['updSecOrganizacionNombre']), "ISO-8859-1", "UTF-8");
    $sectorNombre = mb_convert_encoding(trim($_POST['updSecSectorNombre']), "ISO-8859-1", "UTF-8");
    $sectorDescripcion = mb_convert_encoding(trim($_POST['updSecSectorDescripcion']), "ISO-8859-1", "UTF-8");


    $resultado = $objSector->manEditarSectores($clienteId, $sectorId, $organizacionNombre, $sectorNombre, $sectorDescripcion, $sectorEstado, $usuarioModificacion);

    $organizacionNombre = str_replace("\'", "'", $organizacionNombre);
    $organizacionNombre = str_replace('\"', '"', $organizacionNombre);

    $sectorNombre = str_replace("\'", "'", $sectorNombre);
    $sectorNombre = str_replace('\"', '"', $sectorNombre);

    $sectorDescripcion = str_replace("\'", "'", $sectorDescripcion);
    $sectorDescripcion = str_replace('\"', '"', $sectorDescripcion);


    if ($resultado) {
        if ($resultado['estado'] == "1") {
            ?>                        
            <script language="JavaScript">       
                location.href = "manSectores.php";
            </script>
            <?
        } else if ($resultado['estado'] == "0") {
            ?>                                                            
            <script language="JavaScript">       
                alert("Ya existe la gerencia/agencia para la organización seleccionada.");
            </script>                        
            <?
        } else if ($resultado['estado'] == "2") {
            ?>                                                            
            <script language="JavaScript">       
                alert("La organización seleccionada no existe.");
            </script>                        
            <?
        } else {
            echo 'Se produjo un error. Intente nuevamente.';
        }
    } else {
        echo 'Se produjo un error. Intente nuevamente.';
    }
} else {

    $sectorId = htmlspecialchars(trim($_POST['sectorId']));

    $consulta = $objSector->manMostrarSector($sectorId);

    if ($consulta) {

        $sector = mssql_fetch_array($consulta);

        $organizacionNombre = $sector['organizacionNombre'];
        $sectorNombre = $sector['sectorNombre'];
        $sectorDescripcion = $sector['sectorDescripcion'];
        $sectorEstado = $sector['sectorEstado'];
    }
}
?>                  

<script type="text/javascript">
                                    
    window.addEvent('domready', function() {
                                                                                                                  
        new Autocompleter.Request.HTML($('updSecOrganizacionNombre'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'         : 'organizacionId',
                'nombre_campo'      : 'organizacionNombre',
                'nombre_tablas'     : 'From Organizaciones o (nolock)',
                'nombre_where'      : 'and clienteId = ' + $('clienteId').get('value')  + ' and o.organizacionEstado = 1'                                        
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
        
        $('mBoxContainerOrg').setStyle('opacity', '0');
        
        $('btnCancelarOrg').addEvent('click', function(){

            var property = 'opacity';
            var to = "0";

            $('mBoxContainerOrg').tween(property, to);

        });
    });
    
</script>

<table width="100%">
    <tr>
        <td align="right">
            <a href="manSectores.php" class="volver">Volver</a>
        </td>
    </tr>    
</table>
<input type="hidden" name="clienteId" id="clienteId" value="<?php echo $clienteId ?>"/>

<h4>Editar Gerencia/Agencia</h4>
<br>

<div class="divElementos">
        
<div style="min-height: 400px">
    <div id="contenedor">

        <div class="divFormulario">

            <form id="frmSecIngresar" name="frmSecIngresar" method="post" action="manEditarSector.php" onKeyPress="return manDisableEnterKey(event)">

                <input type="hidden" name="sectorId" id="sectorId" value="<?php echo htmlentities($sectorId) ?>"/>
                <input type="hidden" name="sectorEstado" id="sectorEstado" value="<?php echo htmlentities($sectorEstado) ?>"/>

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
                        <td><input type="text" name="updSecOrganizacionNombre" id="updSecOrganizacionNombre" class="campo" style="text-transform:uppercase;" maxlength="50" value= "<?php echo htmlentities($organizacionNombre) ?>" /></td>
                        <td width="20px"><img id="lupaOrg" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="manSeleccionarOrganizaciones()"/></td>
                        <td><label id="oOrganizacion" style="color: #FF0000; font-size: 12px">Debe seleccionar una Organización</label></td>
                        <td valign="top">
                            <div id ="mBoxContainerOrg" class="BoxContainer" style="position: absolute; left:420px; opacity:0;filter:alpha(opacity=0);" height="auto">

                                <div class="BoxTitle">Organizaciones</div>    

                                <div id="BoxContentOrg" class="BoxContent ">                        

                                </div>

                                <div class="BoxFooterContainer" align="right">

                                    <input id="btnCancelarOrg" type="button" name="button" class="btn" value="Cancelar" />   
                                    <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="manSeleccionarOrganizacion('updSecOrganizacionNombre')" />   

                                </div>

                            </div>
                        </td>
                        
                        
                    </tr>
                    <tr>
                        <td>Gerencia/Agencia <span class="requerido">&nbsp;*</span>:</td>
                        <td><input type="text" name="updSecSectorNombre" id="updSecSectorNombre" class="campo" style="text-transform:uppercase;" maxlength="50" value= "<?php echo htmlentities($sectorNombre) ?>" /> </td>
                        <td></td>
                        <td><label id="oSector" style="color: #FF0000; font-size: 12px">Debe ingresar un nombre para la Gerencia/Agencia</label></td>
                    </tr>
                    <tr>
                        <td>Descripción:</td>
                        <td colspan="3"><input type="text" name="updSecSectorDescripcion" id="updSecSectorDescripcion" class="campoDescripcion" maxlength="250" value= "<?php echo htmlentities($sectorDescripcion) ?>" /></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                </table>
        </div>                                                                                           
        <p> 
            <br>
            <input type="button" name="button" value="Guardar" class="btn azul" onclick="manValidarFormularioSectoresEdicion('updSecOrganizacionNombre','updSecSectorNombre','btActualizarSec')" />                                
            <label></label>                                
            <input type="button" name="cancelar" id="cancelar" value="Cancelar" class="btn" onclick="location.href = 'manSectores.php'" />
            <input type="submit" name="submit" id="btActualizarSec" value="Ingresar" style="display: none" />
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

