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

if ($clienteId == '') {
    echo "<script>window.location='../';</script>";
}

require('clases/usuarios.class.php');
$objUsuario = new usuarios();

$consulta = $objUsuario->manBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta);
$clienteNombre = $cliente['clienteNombreCompleto'];

if (isset($_POST['submit'])) {

    $usuarioNombres = mb_convert_encoding(trim($_POST['usuarioNombres']), "ISO-8859-1", "UTF-8");
    $usuarioApellidos = mb_convert_encoding(trim($_POST['usuarioApellidos']), "ISO-8859-1", "UTF-8");
    $organizacionNombre = mb_convert_encoding(trim($_POST['organizacionNombre']), "ISO-8859-1", "UTF-8");
    $sectorNombre = mb_convert_encoding(trim($_POST['sectorNombre']), "ISO-8859-1", "UTF-8");
    $areaNombre = mb_convert_encoding(trim($_POST['areaNombre']), "ISO-8859-1", "UTF-8");

    $consulta = $objUsuario->manMostrarUsuarios($clienteId, $usuarioNombres, $usuarioApellidos, $organizacionNombre, $sectorNombre, $areaNombre);
    
    $usuarioNombres = str_replace("\'", "'", $usuarioNombres);
    $usuarioNombres = str_replace('\"', '"', $usuarioNombres);
    
    $usuarioApellidos = str_replace("\'", "'", $usuarioApellidos);
    $usuarioApellidos = str_replace('\"', '"', $usuarioApellidos);
    
    $organizacionNombre = str_replace("\'", "'", $organizacionNombre);
    $organizacionNombre = str_replace('\"', '"', $organizacionNombre);
    
    $sectorNombre = str_replace("\'", "'", $sectorNombre);
    $sectorNombre = str_replace('\"', '"', $sectorNombre);
    
    $areaNombre = str_replace("\'", "'", $areaNombre);
    $areaNombre = str_replace('\"', '"', $areaNombre);
    
    
} else {
    $consulta = $objUsuario->manMostrarUsuarios($clienteId, '', '', '', '', '', '', '');
}
?>
<script type="text/javascript">
                                                                                    
    window.addEvent('domready', function() {
                                                                                                                                
        new Autocompleter.Request.HTML($('usuarioNombres'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'	:   'usuarioId',
                'nombre_campo' 	:   'usuarioNombres',
                'nombre_tablas' :   'From Usuarios u (nolock)',
                'nombre_where'  :   'and clienteId = ' + $('clienteId').get('value')                                          
            }
        });                
                                                                                    
        new Autocompleter.Request.HTML($('usuarioApellidos'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'	:   'usuarioId',
                'nombre_campo' 	:   'usuarioApellidos',
                'nombre_tablas' :   'From Usuarios u (nolock)',
                'nombre_where'  :   'and clienteId = ' + $('clienteId').get('value')                                          
            }
        });
                                
        new Autocompleter.Request.HTML($('organizacionNombre'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'	:   'organizacionId',
                'nombre_campo' 	:   'organizacionNombre',
                'nombre_tablas' :   'From Organizaciones o (nolock)',
                'nombre_where'  :   'and clienteId = ' + $('clienteId').get('value')
            }
        });
                                                                                                                            
    });                
                                                                                    
</script>    
<script type="text/javascript" src="../scripts/tablesort.js"></script>
<script>
window.addEvent('domready', function() {
	fdTableSort.init('detalle');
});   
</script>

<table width="100%">
    <tr>
        <td align="right">
            <a href="#" class="volver" onclick="location.href = '../mantenedores/admMantenedores.php'">Volver</a>
        </td>
    </tr>    
</table>

<input type="hidden" name="clienteId" id="clienteId" value="<?php echo htmlentities($clienteId) ?>"/>

<h4>Mantenedor de Usuarios</h4>
<br>

<div style="min-height: 400px">

    <div id="contenedor">
        <div id="formulario" style="display:none;">
        </div>
        <div id="tabla">

            <form id="frmUsuMostrar" name="frmUsuMostrar" method="post" action="manUsuarios.php" style="align:left">
                <table>
                    <tr>
                        <td width="100px" style="padding-bottom:6px">Cliente: </td><td style="padding-bottom:6px"><?php echo htmlentities($clienteNombre) ?></td>
                    </tr> 
                    <tr>
                        <td>Nombres: </td><td style="border:none"><input id="usuarioNombres" name="usuarioNombres" type="text" class="campo" value= "<?php echo htmlentities($usuarioNombres) ?>"/></td>                    
                    </tr>                        
                    <tr>
                        <td>Apellidos: </td><td style="border:none"><input id="usuarioApellidos" name="usuarioApellidos" type="text" class="campo" value= "<?php echo htmlentities($usuarioApellidos) ?>"/></td>                    
                    </tr>
                    <tr>
                        <td>Organización: </td><td style="border:none"><input id="organizacionNombre" name="organizacionNombre" type="text" class="campo" onchange="manLimpiarInputs('sectorNombre','areaNombre','')" value= "<?php echo htmlentities($organizacionNombre) ?>"/></td>                    
                    </tr>
                    <tr>
                        <td>Gerencia/Agencia:</td><td style="border:none"><input id="sectorNombre" name="sectorNombre" type="text" class="campo" onchange="manLimpiarInputs('areaNombre','','')" value= "<?php echo htmlentities($sectorNombre) ?>" onfocus="manMostrarSectores('organizacionNombre', 'sectorNombre')"/></td>
                    </tr>
                    <tr>
                        <td>Área:</td><td><input id="areaNombre" name="areaNombre" type="text" class="campo" value= "<?php echo htmlentities($areaNombre) ?>" onclick="manMostrarAreas('organizacionNombre', 'sectorNombre','areaNombre')"/></td>
                    </tr>
                </table>
                <br>
                <br>

                <input type="submit" name="submit" class="btn" value="Buscar" />

                <br>                                      
            </form>

            <br>

            <span id="nuevo"><a id="nuevo" href="manIngresarUsuario">Ingresar Usuario</a></span>
            <br><br>

            <div class="divElementos">

                <table id="detalle" class="tabla" width="1150px">
                    <tr>
                        <th width="150px" class='sortable'>Nombres</th>                        
                        <th width="150px" class='sortable'>Apellidos</th>                       
                        <th width="40px" class='sortable'>Rut</th>                        
                        <th width="40px" class='sortable'>Email</th>                        
                        <th width="40px" class='sortable'>Cargo</th>                        
                        <th width="40px" class='sortable'>Organizaci&oacute;n</th>                        
                        <th width="40px" class='sortable'>Gerencia/Agencia</th>                                                
                        <th width="40px" class='sortable'>&Aacute;rea</th>                                                
                        <th width="40px" class='sortable'>Estado</th>        
                        <th width="90px" class='sortable'>Fecha creaci&oacute;n</th>
                        <th width="90px" class='sortable'>Fecha edici&oacute;n</th>
                        <th width="30px"></th>
                        <th width="30px"></th>
                    </tr>

                    <?
                    if ($consulta) {

                        $count = 0;

                        while ($usuario2 = mssql_fetch_array($consulta)) {
                            ?>
                            <tr id="fila-<?php echo htmlentities($usuario2['usuarioId']) ?>">
                                <td><?php echo htmlentities($usuario2['usuarioNombres']) ?></td>
                                <td><?php echo htmlentities($usuario2['usuarioApellidos']) ?></td>                                
                                <td><?php echo htmlentities($usuario2['usuarioRut']) ?></td>
                                <td><?php echo htmlentities($usuario2['usuarioEmail']) ?></td>                                
                                <td><?php echo htmlentities($usuario2['cargoNombre']) ?></td>
                                <td><?php echo htmlentities($usuario2['organizacionNombre']) ?></td>
                                <td><?php echo htmlentities($usuario2['sectorNombre']) ?></td>
                                <td><?php echo htmlentities($usuario2['areaNombre']) ?></td>
                                <td><?php
                                if (htmlentities($usuario2['usuarioEstado'])== "1") {
                                    echo "Activo";
                                    $accion = "Anular";
                                } else {
                                    echo "Inactivo";
                                    $accion = "Activar";
                                }
                            ?></td>
                                <td><?php echo date("d-m-Y", strtotime($usuario2['fechaCreacion'])) ?></td>
                                <td><?php echo date("d-m-Y", strtotime($usuario2['fechaModificacion'])) ?></td>
                                <td><span><a href="#" onclick="manEditarData(<? echo htmlentities($usuario2['usuarioId']) ?>, 'usuarioId', 'btnEditarUsuario')">Editar</a></span></td>
                                <td><span><a href="#" onClick="manAnularData(<? echo htmlentities($usuario2['usuarioId']) ?>, 'manFuncionAnularUsuario.php?usuarioId=', 'manUsuarios.php', <?php echo htmlentities($usuario2['usuarioEstado']) ?>, 'el usuario seleccionado?'); return false"><? echo $accion ?></a></span></td>
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

            <form id="frmManEditarUsuario" name="frmManEditarUsuario" method="post" action="manEditarUsuario.php" style="align:left; display: none">

                <input id="usuarioId" name="usuarioId" type="text"/>                
                <input type="submit" name="btnEditarUsuario" id="btnEditarUsuario" />

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

