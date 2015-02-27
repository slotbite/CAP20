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

require('clases/areas.class.php');
$objArea = new areas();

$consulta = $objArea->manBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta);
$clienteNombre = $cliente['clienteNombreCompleto'];


if (isset($_POST['submit'])) {

    $organizacionNombre = mb_convert_encoding(trim($_POST['arOrganizacionNombre']), "ISO-8859-1", "UTF-8");
    $sectorNombre = mb_convert_encoding(trim($_POST['arSectorNombre']), "ISO-8859-1", "UTF-8");
    $areaNombre = mb_convert_encoding(trim($_POST['arAreaNombre']), "ISO-8859-1", "UTF-8");              
    
    $consulta = $objArea->manMostrarAreas($clienteId, $organizacionNombre, $sectorNombre, $areaNombre);
    
    $organizacionNombre = str_replace("\'", "'", $organizacionNombre);
    $organizacionNombre = str_replace('\"', '"', $organizacionNombre);
    
    $sectorNombre = str_replace("\'", "'", $sectorNombre);
    $sectorNombre = str_replace('\"', '"', $sectorNombre);
    
    $areaNombre = str_replace("\'", "'", $areaNombre);
    $areaNombre = str_replace('\"', '"', $areaNombre);       
    
} else {

    $consulta = $objArea->manMostrarAreas($clienteId, '', '', '');
}
?>
<script type="text/javascript">
                                        
    window.addEvent('domready', function() {
                                                                                                                                
        new Autocompleter.Request.HTML($('arOrganizacionNombre'), '../librerias/autocompleterMantenedores.php', {
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

<h4>Mantenedor de Áreas</h4>
<br>

<div style="min-height: 400px">

    <div id="contenedor">
        <div id="formulario" style="display:none;">

        </div>
        <div id="tabla">

            <form id="frmArMostrar" name="frmArMostrar" method="post" action="manAreas.php" style="align:left">
                <table>
                    <tr>
                        <td width="100px" style="padding-bottom:6px">Cliente: </td><td style="padding-bottom:6px"><?php echo htmlentities($clienteNombre) ?></td>
                    </tr> 
                    <tr>
                        <td>Organización: </td><td><input id="arOrganizacionNombre" name="arOrganizacionNombre" type="text" class="campo" value= "<?php echo htmlentities($organizacionNombre) ?>"/></td>                    
                    </tr>
                    <tr>
                        <td>Gerencia/Agencia: </td><td><input id="arSectorNombre" name="arSectorNombre" type="text" class="campo" value= "<?php echo htmlentities($sectorNombre) ?>" onfocus="manMostrarSectores('arOrganizacionNombre','arSectorNombre')" /></td>
                    </tr>
                    <tr>
                        <td>Área: </td><td><input id="arAreaNombre" name="arAreaNombre" type="text" class="campo" value= "<?php echo htmlentities($areaNombre) ?>" onfocus="manMostrarAreas('arOrganizacionNombre','arSectorNombre','arAreaNombre')" /></td>
                    </tr>
                </table>
                <br>
                <br>

                <input type="submit" name="submit" class="btn" value="Buscar" />

                <br>                                      
            </form>

            <br>

            <span id="nuevo"><a id="nuevo" href="manIngresarArea">Ingresar Área</a></span>
            <br><br>

            <div class="divElementos">

                <table id="detalle" class="tabla">
                    <tr>
                        <th width="150px" class='sortable'>Organización</th>
                        <th width="200px" class='sortable'>Gerencia/Agencia</th>
                        <th width="200px" class='sortable'>Área</th>
                        <th width="200px" class='sortable'>Descripción</th>                        
                        <th width="50px" class='sortable'>Estado</th>        
                        <th width="80px" class='sortable'>Fecha creación</th>
                        <th width="80px" class='sortable'>Fecha edición</th>
                        <th width="40px"></th>
                        <th width="40px"></th>
                    </tr>

                    <?
                    if ($consulta) {

                        $count = 0;

                        while ($area = mssql_fetch_array($consulta)) {
                            ?>
                            <tr id="fila-<?php echo htmlentities($area['sectorId']) ?>">
                                <td><?php echo htmlentities($area['organizacionNombre']) ?></td>
                                <td><?php echo htmlentities($area['sectorNombre']) ?></td>
                                <td><?php echo htmlentities($area['areaNombre']) ?></td>
                                <td><?php echo htmlentities($area['areaDescripcion']) ?></td>                                
                                <td><?php
                    if (htmlentities($area['areaEstado']) == "1") {
                        echo "Activa";
                        $accion = "Anular";
                    } else {
                        echo "Inactiva";
                        $accion = "Activar";
                    }
                            ?></td>
                                <td><?php echo date("d-m-Y", strtotime($area['fechaCreacion'])) ?></td>
                                <td><?php echo date("d-m-Y", strtotime($area['fechaModificacion'])) ?></td>
                                <td><span><a href="#" onclick="manEditarData(<? echo htmlentities($area['areaId']) ?>, 'areaId', 'btnEditarArea')">Editar</a></span></td>
                                <td><span><a href="#" onClick="manAnularData(<? echo htmlentities($area['areaId']) ?>, 'manFuncionAnularArea.php?areaId=', 'manAreas.php', <?php echo htmlentities($area['areaEstado']) ?>, 'el área seleccionada?'); return false"><? echo $accion ?></a></span></td>
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
            
            <form id="frmManEditarArea" name="frmManEditarArea" method="post" action="manEditarArea.php" style="align:left; display: none">

                <input id="areaId" name="areaId" type="text"/>                
                <input type="submit" name="btnEditarArea" id="btnEditarArea" />

            </form>

        </div>

    </div>

</div>

<?

mssql_free_result($consulta);

$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>
