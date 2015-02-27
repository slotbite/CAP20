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

require('clases/organizaciones.class.php');
$objOrganizacion = new organizaciones;

$consulta = $objOrganizacion->manBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta);
$clienteNombre = $cliente['clienteNombreCompleto'];


if (isset($_POST['submit'])) {

    $organizacionNombre = mb_convert_encoding(trim($_POST['organizacionNombre']), "ISO-8859-1", "UTF-8");
    $consulta = $objOrganizacion->manMostrarOrganizaciones($clienteId, $organizacionNombre);
    
    $organizacionNombre = str_replace("\'", "'", $organizacionNombre);
    $organizacionNombre = str_replace('\"', '"', $organizacionNombre);
    
} else {

    $consulta = $objOrganizacion->manMostrarOrganizaciones($clienteId, '');
}
?>

<script type="text/javascript">
                                    
    window.addEvent('domready', function() {
                                                                                                                  
        new Autocompleter.Request.HTML($('organizacionNombre'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'	:	'organizacionId',
                'nombre_campo' 	: 	'organizacionNombre',
                'nombre_tablas' :       'From Organizaciones o (nolock)',
                'nombre_where'  :       'and clienteId = ' + $('clienteId').get('value')
                /*'nombre_estado'         :       'organizacionEstado'
                 */
            }
        });
                                                             
    });                
                                    
</script>    

<script type="text/javascript" src="../scripts/mediabox/mediaboxAdv-1.2.5b.js"></script>

<link rel="stylesheet" type="text/css" href="../scripts/mediabox/mediaboxAdvBlack21.css"/>
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
<input type="hidden" name="clienteId" id="clienteId" value="<?php echo $clienteId ?>"/>


<h4>Mantenedor de Organizaciones</h4>
<br>



<div style="min-height: 400px">

    <div id="contenedor">

        <div id="formulario" style="display:none;">

        </div>

        <div id="tabla">

            <form id="frmOrgMostrar" name="frmOrgMostrar" method="post" action="manOrganizaciones.php" style="align:left">
                <table>
                    <tr>
                        <td width="100px" style="padding-bottom:6px">Cliente: </td><td style="padding-bottom:6px"><?php echo htmlentities($clienteNombre) ?></td>
                    </tr> 
                    <tr>
                        <td width="100px">Organización: </td><td><input id="organizacionNombre" name="organizacionNombre" type="text" class="campo" value= "<?php echo htmlentities($organizacionNombre) ?>"/></td>                    
                    </tr>                            
                </table>

                <br>
                <br>

                <input type="submit" name="submit" class="btn" value="Buscar" />

                <br>                                      
            </form>

            <br>

            <span id="nuevo"><a id="nuevo" href="manIngresarOrganizacion">Ingresar Organización</a></span>
            <br><br>

            <div>
                
                <div class="divElementos">

                <table id="detalle" class="tabla">
                    <tr>
                        <th width="250px" class='sortable'>Organización</th>
                        <th width="70px" class='sortable'>Logo</th>
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

                        while ($organizacion = mssql_fetch_array($consulta)) {
                            ?>
                            <tr id="fila-<?php echo $organizacion['organizacionId'] ?>">
                                <td><?php echo htmlentities($organizacion['organizacionNombre']) ?></td>
                                <?
                                if (htmlentities(trim($organizacion['organizacionLogo'])) != "") {
                                    ?>
                                    <td>
                                        <img src="<?php echo htmlentities($organizacion['organizacionLogo']) ?>" width="60" height="30" alt="Logo <?php echo htmlentities($organizacion['organizacionNombre']) ?>">
                                    </td>
                                    <?
                                } else {
                                    ?>        
                                    <td>
                                        
                                    </td>
                                    <?
                                }
                                ?>
                                <td><?php echo htmlentities($organizacion['organizacionDescripcion']) ?></td>                                
                                <td><?php
                        if (htmlentities($organizacion['organizacionEstado']) == "1") {
                            echo "Activa";
                            $accion = "Anular";
                        } else {
                            echo "Inactiva";
                            $accion = "Activar";
                        }
                                ?></td>
                                <td><?php echo date("d-m-Y", strtotime(htmlentities($organizacion['fechaCreacion']))) ?></td>
                                <td><?php echo date("d-m-Y", strtotime(htmlentities($organizacion['fechaModificacion']))) ?></td>
<!--                                <td><span><a rel="lightbox[url 1018 500]" href="../mantenedores/manVerOrganizacion.php?organizacionId=<?php //echo $organizacion['organizacionId'] ?>" target="_blank">Ver</a></span></td>-->
                                <td><span><a href="#" onclick="manEditarData(<? echo htmlentities($organizacion['organizacionId']) ?>, 'organizacionId', 'btnEditarOrganizacion')">Editar</a></span></td>
                                <td><span><a href="#" onClick="manAnularData(<? echo htmlentities($organizacion['organizacionId']) ?>, 'manFuncionAnularOrganizacion.php?organizacionId=', 'manOrganizaciones.php', <?php echo htmlentities($organizacion['organizacionEstado']) ?>, 'la organización seleccionada?' ); return false"><? echo $accion ?></a></span></td>
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

            </div>

            <form id="frmManEditarOrganizacion" name="frmManEditarOrganizacion" method="post" action="manEditarOrganizacion.php" style="align:left; display: none">

                <input id="organizacionId" name="organizacionId" type="text"/>                
                <input type="submit" name="btnEditarOrganizacion" id="btnEditarOrganizacion" />

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


