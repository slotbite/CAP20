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

require('clases/clientes.class.php');
$objCliente = new clientes();

if (isset($_POST['submit'])) {

    $clienteNombres = mb_convert_encoding(trim($_POST['cliClienteNombres']), "ISO-8859-1", "UTF-8");
    $clienteApellidos = mb_convert_encoding(trim($_POST['cliClienteApellidos']), "ISO-8859-1", "UTF-8");
            
    $consulta = $objCliente->manMostrarClientes($clienteNombres, $clienteApellidos);
    
    $clienteNombres = str_replace("\'", "'", $clienteNombres);
    $clienteNombres = str_replace('\"', '"', $clienteNombres);

    $clienteApellidos = str_replace("\'", "'", $clienteApellidos);
    $clienteApellidos = str_replace('\"', '"', $clienteApellidos);
    
} else {

    $consulta = $objCliente->manMostrarClientes('', '');
}
?>
<script type="text/javascript">
                                                        
    window.addEvent('domready', function() {
                                                                                               
        new Autocompleter.Request.HTML($('cliClienteNombres'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'         :	'clienteId',
                'nombre_campo'      : 	'clienteNombres',
                'nombre_tablas'     :   'From Clientes c (nolock)',
                'nombre_where'      :   ''                                         
            }
        });                
                                                        
        new Autocompleter.Request.HTML($('cliClienteApellidos'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'         :	'clienteId',
                'nombre_campo'      : 	'clienteApellidos',
                'nombre_tablas'     :   'From Clientes c (nolock)',
                'nombre_where'      :   ''                                         
            }
        });
                                                                                                
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

<h4>Mantenedor de Clientes</h4>
<br>

<div style="min-height: 400px">

    <div id="contenedor">

        <div id="formulario" style="display:none;">
        </div>

        <div id="tabla">

            <form id="frmCliMostrar" name="frmCliMostrar" method="post" action="manClientes.php" style="align:left">
                <table>
                    <tr>
                        <td width="100px" style="border:none">Nombres: </td>
                        <td style="border:none"><input id="cliClienteNombres" name="cliClienteNombres" type="text" class="campo" value= "<?php echo htmlentities($clienteNombres) ?>"/></td>                    
                    </tr>                        
                    <tr>
                        <td width="100px">Apellidos: </td>
                        <td><input id="cliClienteApellidos" name="cliClienteApellidos" type="text" class="campo" value= "<?php echo htmlentities($clienteApellidos) ?>"/></td>                    
                    </tr>
                </table>
                <br>
                <br>

                <input type="submit" name="submit" class="btn" value="Buscar" />

                <br>                                      
            </form>

            <br>

            <span id="nuevo"><a id="nuevo" href="manIngresarCliente">Ingresar Cliente</a></span>
            <br><br>

            <div class="divElementos">

                <table id="detalle" class="tabla">
                    <tr>
                        <th width="130px">Nombres</th>                        
                        <th width="130px">Apellidos</th>                       
                        <th width="60px">Email</th>                        
                        <th width="200px">Dirección</th>                        
                        <th width="60px">Fono</th>                        
                        <th width="50px">Multimedia</th>                                                
                        <th width="50px">Estado</th>        
                        <th width="80px">Fecha creación</th>
                        <th width="80px">Fecha edición</th>
                        <th width="40px"></th>
                        <th width="40px"></th>
                    </tr>

                    <?
                    if ($consulta) {

                        $count = 0;

                        while ($cliente = mssql_fetch_array($consulta)) {
                            ?>
                            <tr id="fila-<?php echo htmlentities($cliente['clienteId']) ?>">
                                <td><?php echo htmlentities($cliente['clienteNombres']) ?></td>                                
                                <td><?php echo htmlentities($cliente['clienteApellidos']) ?></td>                                
                                <td><?php echo htmlentities($cliente['clienteEmail']) ?></td>                                
                                <td><?php echo htmlentities($cliente['clienteDireccion']) ?></td>
                                <td><?php echo htmlentities($cliente['clienteFono']) ?></td>
                                <td><?php echo htmlentities($cliente['clienteMultimedia']) ?></td>
                                <td><?php
                    if (htmlentities($cliente['clienteEstado']) == "1") {
                        echo "Activo";
                        $accion = "Anular";
                    } else {
                        echo "Inactivo";
                        $accion = "Activar";
                    }
                            ?></td>
                                <td><?php echo date("d-m-Y", strtotime($cliente['fechaCreacion'])) ?></td>
                                <td><?php echo date("d-m-Y", strtotime($cliente['fechaModificacion'])) ?></td>
                                <td><span><a href="#" onclick="manEditarData(<? echo htmlentities($cliente['clienteId']) ?>, 'cliClienteId', 'btnEditarCliente')">Editar</a></span></td>
                                <td><span><a href="#" onClick="manAnularData(<? echo htmlentities($cliente['clienteId']) ?>, 'manFuncionAnularCliente.php?cliClienteId=', 'manClientes.php', <?php echo htmlentities($cliente['clienteEstado']) ?>, 'el cliente seleccionado?'); return false"><? echo $accion ?></a></span></td>
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

            <form id="frmManEditarCliente" name="frmManEditarCliente" method="post" action="manEditarCliente.php" style="align:left; display: none">

                <input id="cliClienteId" name="cliClienteId" type="text"/>                
                <input type="submit" name="btnEditarCliente" id="btnEditarCliente" />

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
