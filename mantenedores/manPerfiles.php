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

require('clases/perfiles.class.php');
$objPerfil = new perfiles();

if (isset($_POST['submit'])) {

    $perfilNombre = mb_convert_encoding(trim($_POST['perfilNombre']), "ISO-8859-1", "UTF-8");
    $consulta = $objPerfil->manMostrarPerfiles($clienteId, $perfilNombre);
    
    $perfilNombre = str_replace("\'", "'", $perfilNombre);
    $perfilNombre = str_replace('\"', '"', $perfilNombre);
    
} else {

    $consulta = $objPerfil->manMostrarPerfiles($clienteId, '');
}
?>

<script type="text/javascript">
                                        
    window.addEvent('domready', function() {
                                                                                                                                  
        new Autocompleter.Request.HTML($('perfilNombre'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'	: 'perfilId',
                'nombre_campo' 	: 'perfilNombre',
                'nombre_tablas' : 'From Perfiles p (nolock)',
                'nombre_where'  : ' --and clienteId = ' + $('clienteId').get('value')
                /*'nombre_estado'         :       'organizacionEstado'
                 */
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

<h4>Mantenedor de Perfiles</h4>
<br>

<div style="min-height: 400px">

    <div id="contenedor">
        <div id="formulario" style="display:none;">

        </div>

        <div id="tabla">

            <form id="frmPerMostrar" name="frmPerMostrar" method="post" action="manPerfiles.php" style="align:left">
                <table>
                    <tr>
                        <td width="100px">Perfil: </td><td><input id="perfilNombre" name="perfilNombre" type="text" class="campo" value= "<?php echo htmlentities($perfilNombre) ?>"/></td>                    
                    </tr>                            
                </table>

                <br>
                <br>

                <input type="submit" name="submit" class="btn" value="Buscar" />

                <br>                                      
            </form>

            <br>

            <span id="nuevo"><a id="nuevo" href="manIngresarPerfil">Ingresar Perfil</a></span>
            <br><br>


            <div class="divElementos">
                <table id="detalle" class="tabla">
                    <tr>
                        <th width="250px">Perfil</th>                        
                        <th width="400px">Descripción</th>                        
                        <th width="50px">Estado</th>        
                        <th width="80px">Fecha creación</th>
                        <th width="80px">Fecha edición</th>
                        <th width="40px"></th>
                        <th width="40px"></th>
                    </tr>


                    <?
                    if ($consulta) {

                        $count = 0;

                        while ($perfil = mssql_fetch_array($consulta)) {
                            ?>
                            <tr id="fila-<?php echo htmlentities($perfil['perfilId']) ?>">
                                <td><?php echo htmlentities($perfil['perfilNombre']) ?></td>                                
                                <td><?php echo htmlentities($perfil['perfilDescripcion']) ?></td>                                
                                <td><?php
                    if (htmlentities($perfil['perfilEstado']) == "1") {
                        echo "Activo";
                        $accion = "Anular";
                    } else {
                        echo "Inactivo";
                        $accion = "Activar";
                    }
                            ?></td>
                                <td><?php echo date("d-m-Y", strtotime($perfil['fechaCreacion'])) ?></td>
                                <td><?php echo date("d-m-Y", strtotime($perfil['fechaModificacion'])) ?></td>
                                <td><span><a href="#" onclick="manEditarData(<? echo htmlentities($perfil['perfilId']) ?>, 'perfilId', 'btnEditarPerfil')">Editar</a></span></td>
                                <td><span><a href="#" onClick="manAnularData(<? echo htmlentities($perfil['perfilId']) ?>, 'manFuncionAnularPerfil.php?perfilId=', 'manPerfiles.php', <?php echo htmlentities($perfil['perfilEstado']) ?>, 'el perfil seleccionado?'); return false"><? echo $accion ?></a></span></td>
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

            <form id="frmManEditarPerfil" name="frmManEditarPerfil" method="post" action="manEditarPerfil.php" style="align:left; display: none">

                <input id="perfilId" name="perfilId" type="text"/>                
                <input type="submit" name="btnEditarPerfil" id="btnEditarPerfil" />

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


