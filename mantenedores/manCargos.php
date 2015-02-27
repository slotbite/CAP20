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

require('clases/cargos.class.php');
$objCargo = new cargos;

$consulta = $objCargo->manBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta);
$clienteNombre = $cliente['clienteNombreCompleto'];



if (isset($_POST['submit'])) {

    $cargoNombre = mb_convert_encoding(trim($_POST['carCargoNombre']), "ISO-8859-1", "UTF-8");
    $consulta = $objCargo->manMostrarCargos($clienteId, $cargoNombre);
    
    $cargoNombre = str_replace("\'", "'", $cargoNombre);
    $cargoNombre = str_replace('\"', '"', $cargoNombre);
    
} else {
    
    $consulta = $objCargo->manMostrarCargos($clienteId, '');
}
?>

<script type="text/javascript">
                                        
    window.addEvent('domready', function() {
                                                                                                                                   
        new Autocompleter.Request.HTML($('carCargoNombre'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'         :	'cargoId',
                'nombre_campo'      : 	'cargoNombre',
                'nombre_tablas'     :   'From Cargos c (nolock)',
                'nombre_where'      :   'and clienteId = ' + $('clienteId').get('value')                                         
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

<h4>Mantenedor de Cargos</h4>
<br>

<div style="min-height: 400px">

    <div id="contenedor">
        <div id="formulario" style="display:none;">
        </div>
        <div id="tabla">

            <form id="frmCarMostrar" name="frmCarMostrar" method="post" action="manCargos.php" style="align:left">
                <table>
                    <tr>
                        <td width="100px" style="padding-bottom:6px">Cliente: </td><td style="padding-bottom:6px"><?php echo htmlentities($clienteNombre) ?></td>
                    </tr> 
                    <tr>
                        <td>Cargo: </td><td><input id="carCargoNombre" name="carCargoNombre" type="text" class="campo" value= "<?php echo htmlentities($cargoNombre) ?>"/></td>                    
                    </tr>
                </table>
                <br>
                <br>

                <input type="submit" name="submit" class="btn" value="Buscar" />

                <br>                                      
            </form>
            
            <br>

            <span id="nuevo"><a id="nuevo" href="manIngresarCargo">Ingresar Cargo</a></span>
            <br><br>

            <div class="divElementos">
            
            <table id="detalle" class="tabla">
                <tr>
                    <th width="300px" class='sortable'>Cargo</th>                        
                    <th width="420px" class='sortable'>Descripción</th>                        
                    <th width="50px" class='sortable'>Estado</th>        
                    <th width="80px" class='sortable'>Fecha creación</th>
                    <th width="80px" class='sortable'>Fecha edición</th>
                    <th width="40px"></th>
                    <th width="40px"></th>
                </tr>

                <?
                if ($consulta) {
                    
                    $count = 0;
                    
                    while ($cargo = mssql_fetch_array($consulta)) {
                        ?>
                        <tr id="fila-<?php echo htmlentities($cargo['cargoId']) ?>">
                            <td><?php echo htmlentities($cargo['cargoNombre']) ?></td>                                
                            <td><?php echo htmlentities($cargo['cargoDescripcion']) ?></td>                                
                            <td><?php
                if (htmlentities($cargo['cargoEstado']) == "1") {
                    echo "Activo";
                    $accion = "Anular";
                } else {
                    echo "Inactivo";
                    $accion = "Activar";
                }
                        ?></td>
                            <td><?php echo date("d-m-Y", strtotime($cargo['fechaCreacion'])) ?></td>
                            <td><?php echo date("d-m-Y", strtotime($cargo['fechaModificacion'])) ?></td>
                            <td><span><a href="#" onclick="manEditarData(<? echo htmlentities($cargo['cargoId']) ?>, 'cargoId', 'btnEditarCargo')">Editar</a></span></td>
                            <td><span><a href="#" onClick="manAnularData(<? echo htmlentities($cargo['cargoId']) ?>, 'manFuncionAnularCargo.php?cargoId=', 'manCargos.php', <?php echo htmlentities($cargo['cargoEstado']) ?>, 'el cargo seleccionado?'); return false"><? echo $accion ?></a></span></td>
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
            
         <form id="frmManEditarCargo" name="frmManEditarCargo" method="post" action="manEditarCargo.php" style="align:left; display: none">

            <input id="cargoId" name="cargoId" type="text"/>                
            <input type="submit" name="btnEditarCargo" id="btnEditarCargo" />

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
