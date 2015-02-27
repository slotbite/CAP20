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

require('clases/sectores.class.php');
$objSector = new sectores();

$consulta = $objSector->manBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta);
$clienteNombre = $cliente['clienteNombreCompleto'];


if (isset($_POST['submit'])) {

    $organizacionNombre = mb_convert_encoding(trim($_POST['secOrganizacionNombre']), "ISO-8859-1", "UTF-8");
    $sectorNombre = mb_convert_encoding(trim($_POST['secSectorNombre']), "ISO-8859-1", "UTF-8");
    
    $organizacionNombre = str_replace("\'", "'", $organizacionNombre);
    $organizacionNombre = str_replace('\"', '"', $organizacionNombre);
    
    $sectorNombre = str_replace("\'", "'", $sectorNombre);
    $sectorNombre = str_replace('\"', '"', $sectorNombre);

    $consulta = $objSector->manMostrarSectores($clienteId, $organizacionNombre, $sectorNombre);
} else {

    $consulta = $objSector->manMostrarSectores($clienteId, '', '');
}
?>
<script type="text/javascript">
                                        
    window.addEvent('domready', function() {
                                                                                                                              
        new Autocompleter.Request.HTML($('secOrganizacionNombre'), '../librerias/autocompleterMantenedores.php', {
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

<h4>Mantenedor de Gerencia/Agencia</h4>
<br>

<div style="min-height: 400px">

    <div id="contenedor">
        <div id="formulario" style="display:none;">

        </div>

        <div id="tabla">

            <form id="frmSecMostrar" name="frmSecMostrar" method="post" action="manSectores.php" style="align:left">
                <table>
                    <tr>
                        <td width="100px" style="padding-bottom:6px">Cliente: </td><td style="padding-bottom:6px"><?php echo htmlentities($clienteNombre) ?></td>
                    </tr> 
                    <tr>
                        <td>Organización: </td><td><input id="secOrganizacionNombre" name="secOrganizacionNombre" type="text" class="campo" value= "<?php echo htmlentities($organizacionNombre) ?>"/></td>                    
                    </tr>
                    <tr>
                        <td>Gerencia/Agencia: </td><td><input id="secSectorNombre" name="secSectorNombre" type="text" class="campo" value= "<?php echo htmlentities($sectorNombre) ?>" onfocus="manMostrarSectores('secOrganizacionNombre', 'secSectorNombre')" /></td>
                    </tr>
                </table>
                <br>
                <br>

                <input type="submit" name="submit" class="btn" value="Buscar" />

                <br>                                      
            </form>

            <br>

            <span id="nuevo"><a id="nuevo" href="manIngresarSector">Ingresar Gerencia/Agencia</a></span>
            <br><br>

            <div class="divElementos">
                
                

                <table id="detalle" class="tabla">
                    <tr>
                        <th width="200px" class='sortable'>Organización</th>
                        <th width="210px" class='sortable'>Gerencia/Agencia</th>
                        <th width="300px" class='sortable'>Descripción</th>                        
                        <th width="50px" class='sortable'>Estado</th>        
                        <th width="80px" class='sortable'>Fecha creación</th>
                        <th width="80px" class='sortable'>Fecha edición</th>
                        <th width="40px"></th>
                        <th width="40px"></th>
                    </tr>


                    <?
                    if ($consulta) {
                        
                        $count = 0;                       
                        
                        while ($sector = mssql_fetch_array($consulta)) {
                            ?>
                            <tr id="fila-<?php echo htmlentities($sector['sectorId']) ?>">
                                <td><?php echo htmlentities($sector['organizacionNombre']) ?></td>
                                <td><?php echo htmlentities($sector['sectorNombre']) ?></td>
                                <td><?php echo htmlentities($sector['sectorDescripcion']) ?></td>                                
                                <td><?php
                    if (htmlentities($sector['sectorEstado']) == "1") {
                        echo "Activa";
                        $accion = "Anular";
                    } else {
                        echo "Inactiva";
                        $accion = "Activar";
                    }
                            ?></td>
                                <td><?php echo date("d-m-Y", strtotime($sector['fechaCreacion'])) ?></td>
                                <td><?php echo date("d-m-Y", strtotime($sector['fechaModificacion'])) ?></td>
                                <td><span><a href="#" onclick="manEditarData(<? echo htmlentities($sector['sectorId']) ?>, 'sectorId', 'btnEditarSector')">Editar</a></span></td>
                                <td><span><a href="#" onClick="manAnularData(<? echo htmlentities($sector['sectorId']) ?>, 'manFuncionAnularSector.php?sectorId=', 'manSectores.php', <?php echo htmlentities($sector['sectorEstado']) ?>, 'la gerencia/agencia seleccionada?'); return false"><? echo $accion ?></a></span></td>
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

            <form id="frmManEditarSector" name="frmManEditarSector" method="post" action="manEditarSector.php" style="align:left; display: none">

                <input id="sectorId" name="sectorId" type="text"/>                
                <input type="submit" name="btnEditarSector" id="btnEditarSector" />

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
