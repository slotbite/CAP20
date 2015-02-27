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

require('clases/grupos.class.php');
$objGrupo = new grupos();

$consulta = $objGrupo->manBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta);
$clienteNombre = $cliente['clienteNombreCompleto'];


if (isset($_POST['submit'])) {

    $grupoNombre = mb_convert_encoding(trim($_POST['grupoNombre']), "ISO-8859-1", "UTF-8");    

    $consulta = $objGrupo->manMostrarGrupos($clienteId, $grupoNombre);
    
    $grupoNombre = str_replace("\'", "'", $grupoNombre);
    $grupoNombre = str_replace('\"', '"', $grupoNombre);
    
} else {

    $consulta = $objGrupo->manMostrarGrupos($clienteId, '');
}
?>
<script type="text/javascript">
                                        
    window.addEvent('domready', function() {
                                                                                                                              
        new Autocompleter.Request.HTML($('grupoNombre'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'         :	'grupoId',
                'nombre_campo'      : 	'grupoNombre',
                'nombre_tablas'     :   'From Grupos g (nolock)',
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

<h4>Mantenedor de Grupos</h4>
<br>

<div style="min-height: 400px">

    <div id="contenedor">
        <div id="formulario" style="display:none;">

        </div>

        <div id="tabla">

            <form id="frmGruMostrar" name="frmGruMostrar" method="post" action="manGrupos.php" style="align:left">
                <table>
                    <tr>
                        <td width="100px" style="padding-bottom:6px">Cliente: </td><td style="padding-bottom:6px"><?php echo htmlentities($clienteNombre) ?></td>
                    </tr> 
                    <tr>
                        <td>Grupo: </td><td><input id="grupoNombre" name="grupoNombre" type="text" class="campo" value= "<?php echo htmlentities($grupoNombre) ?>"/></td>                    
                    </tr>                    
                </table>
                <br>
                <br>

                <input type="submit" name="submit" class="btn" value="Buscar" />

                <br>                                      
            </form>

            <br>

            <span id="nuevo"><a id="nuevo" href="manIngresarGrupo">Ingresar Grupo</a></span>
            <br><br>

            <div class="divElementos">

                <table id="detalle" class="tabla">
                    <tr>
                        <th width="300px" class='sortable'>Grupo</th>                        
                        <th width="400px" class='sortable'>Descripción</th>                        
                        <th width="50px" class='sortable'>Estado</th>        
                        <th width="80px" class='sortable'>Fecha creación</th>
                        <th width="80px" class='sortable'>Fecha edición</th>
                        <th width="40px"></th>
                        <th width="40px"></th>
                    </tr>


                    <?
                    if ($consulta) {

                        $count = 0;

                        while ($grupo = mssql_fetch_array($consulta)) {
                            ?>
                            <tr id="fila-<?php echo htmlentities($grupo['grupoId']) ?>">
                                <td><?php echo htmlentities($grupo['grupoNombre']) ?></td>                                
                                <td><?php echo htmlentities($grupo['grupoDescripcion']) ?></td>                                
                                <td><?php
                    if (htmlentities($grupo['grupoEstado']) == "1") {
                        echo "Activo";
                        $accion = "Anular";
                    } else {
                        echo "Inactivo";
                        $accion = "Activar";
                    }
                            ?></td>
                                <td><?php echo date("d-m-Y", strtotime($grupo['fechaCreacion'])) ?></td>
                                <td><?php echo date("d-m-Y", strtotime($grupo['fechaModificacion'])) ?></td>
                                <td><span><a href="#" onclick="manEditarData(<? echo htmlentities($grupo['grupoId']) ?>, 'grupoId', 'btnEditarGrupo')">Editar</a></span></td>
                                <td><span><a href="#" onclick="manAnularData(<? echo htmlentities($grupo['grupoId']) ?>, 'manFuncionAnularGrupo.php?grupoId=', 'manGrupos.php', <?php echo htmlentities($grupo['grupoEstado']) ?>, 'el grupo seleccionado?'); return false"><? echo $accion ?></a></span></td>
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

            <form id="frmManEditarGrupo" name="frmManEditarGrupo" method="post" action="manEditarGrupo.php" style="align:left; display: none">

                <input id="grupoId" name="grupoId" type="text"/>                
                <input type="submit" name="btnEditarGrupo" id="btnEditarGrupo" />

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
