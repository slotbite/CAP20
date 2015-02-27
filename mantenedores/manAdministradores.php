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

require('clases/administradores.class.php');
$objAdministrador = new administradores();

$consulta = $objAdministrador->manBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta);
$clienteNombre = $cliente['clienteNombreCompleto'];

if (isset($_POST['submit'])) {

    $administradorNombres = mb_convert_encoding(trim($_POST['administradorNombres']), "ISO-8859-1", "UTF-8");
    $administradorApellidos = mb_convert_encoding(trim($_POST['administradorApellidos']), "ISO-8859-1", "UTF-8");

    $consulta = $objAdministrador->manMostrarAdministradores($clienteId, $administradorNombres, $administradorApellidos);
    
    $administradorNombres = str_replace("\'", "'", $administradorNombres);
    $administradorNombres = str_replace('\"', '"', $administradorNombres);
    
    $administradorApellidos = str_replace("\'", "'", $administradorApellidos);
    $administradorApellidos = str_replace('\"', '"', $administradorApellidos);
    
    
} else {

    $consulta = $objAdministrador->manMostrarAdministradores($clienteId, '', '');
}

?>
<script type="text/javascript">
                                                                
    window.addEvent('domready', function() {
                                                                                                              
        new Autocompleter.Request.HTML($('administradorNombres'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'	:   'administradorId',
                'nombre_campo' 	:   'administradorNombres',
                'nombre_tablas' :   'From Administradores a (nolock)',
                'nombre_where'  :   'and clienteId = ' + $('clienteId').get('value')                                          
            }
        });                
                                                                
        new Autocompleter.Request.HTML($('administradorApellidos'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'	:   'administradorId',
                'nombre_campo' 	:   'administradorApellidos',
                'nombre_tablas' :   'From Administradores a (nolock)',
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

<h4>Mantenedor de Administradores</h4>
<br>

<div style="min-height: 400px">

    <div id="contenedor">
        <div id="formulario" style="display:none;">
        </div>
        
        <div id="tabla">

            <form id="frmAdmMostrar" name="frmAdmMostrar" method="post" action="manAdministradores.php" style="align:left">
                <table>
                    <tr>
                        <td width="100px" style="padding-bottom:6px">Cliente: </td><td style="padding-bottom:6px"><?php echo htmlentities($clienteNombre) ?></td>
                    </tr> 
                    <tr>
                        <td>Nombres: </td><td style="border:none"><input id="administradorNombres" name="administradorNombres" type="text" class="campo" value= "<?php echo htmlentities($administradorNombres) ?>"/></td>                    
                    </tr>                        
                    <tr>
                        <td>Apellidos: </td><td><input id="administradorApellidos" name="administradorApellidos" type="text" class="campo" value= "<?php echo htmlentities($administradorApellidos) ?>"/></td>                    
                    </tr>
                </table>
                <br>
                <br>

                <input type="submit" name="submit" class="btn" value="Buscar" />

                <br>                                      
            </form>

            <br>

            <span id="nuevo"><a id="nuevo" href="manIngresarAdministrador">Ingresar Administrador</a></span>
            <br><br>

            <div class="divElementos">
                            
                <table id="detalle" class="tabla">
                    <tr>
                        <th width="200px" class='sortable'>Nombres</th>                        
                        <th width="200px" class='sortable'>Apellidos</th>                       
                        <th width="150px" class='sortable'>Email</th>                        
                        <!--<th width="90px">Perfil</th>-->
                        <th width="80px" class='sortable'>Login</th>                        
                        <th width="80px" class='sortable'>Password</th>                                                
                        <th width="50px" class='sortable'>Estado</th>        
                        <th width="80px" class='sortable'>Fecha creación</th>
                        <th width="80px" class='sortable'>Fecha edición</th>
                        <th width="40px"></th>
                        <th width="40px"></th>
                    </tr>

                    <?
                    if ($consulta) {
                        
                        $count = 0;
                        
                        while ($administrador = mssql_fetch_array($consulta)) {
                            ?>
                            <tr id="fila-<?php echo htmlentities($administrador['administradorId']) ?>">
                                <td><?php echo htmlentities($administrador['administradorNombres']) ?></td>                                
                                <td><?php echo htmlentities($administrador['administradorApellidos']) ?></td>                                
                                <td><?php echo htmlentities($administrador['administradorEmail']) ?></td>                                                                
                                <td><?php echo htmlentities($administrador['login']) ?></td>
                                <td><?php echo '*******'//htmlentities($administrador['password']) ?></td>

                                <td><?php
                    if (htmlentities($administrador['administradorEstado']) == "1") {
                        echo "Activo";
                        $accion = "Anular";
                    } else {
                        echo "Inactivo";
                        $accion = "Activar";
                    }
                            ?></td>
                                <td><?php echo date("d-m-Y", strtotime($administrador['fechaCreacion'])) ?></td>
                                <td><?php echo date("d-m-Y", strtotime($administrador['fechaModificacion'])) ?></td>
                                <td><span><a href="#" onclick="manEditarData(<? echo htmlentities($administrador['administradorId']) ?>, 'administradorId', 'btnEditarAdministrador')">Editar</a></span></td>
                                <td><span><a href="#" onClick="manAnularData(<? echo htmlentities($administrador['administradorId']) ?>, 'manFuncionAnularAdministrador.php?administradorId=', 'manAdministradores.php', <?php echo htmlentities($administrador['administradorEstado']) ?>, 'el administrador seleccionado?'); return false"><? echo $accion ?></a></span></td>
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

            <form id="frmManEditarAdministrador" name="frmManEditarAdministrador" method="post" action="manEditarAdministrador.php" style="align:left; display: none">

                <input id="administradorId" name="administradorId" type="text"/>                
                <input type="submit" name="btnEditarAdministrador" id="btnEditarAdministrador" />

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
