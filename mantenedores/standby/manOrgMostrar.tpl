
<?php
include("default.php");
require('clases/organizaciones.class.php');
$objOrganizacion = new Organizaciones;
$consulta = $objOrganizacion->manMostrarOrganizaciones();
?>

<link rel="stylesheet" type="text/css" href="../css/mantenedores.css" media="screen" />           
<span id="nuevo"><a href="nuevo.php"><img src="../images/add.png" alt="Agregar dato" /></a></span>
<table>
    <tr>
        <th>Organización</th>
        <th>Descripción</th>
        <th>Logo</th>
        <th>Estado</th>        
        <th>Fecha creación</th>
        <th>Fecha modificación</th>
        <th></th>
        <th></th>
    </tr>


    <?
    if ($consulta) {
        while ($organizacion = mssql_fetch_array($consulta)) {
            ?>
            <tr id="fila-<?php echo $organizacion['organizacionId'] ?>">
                <td><?php echo $organizacion['organizacionNombre'] ?></td>
                <td><?php echo $organizacion['organizacionDescripcion'] ?></td>
                <td><?php echo $organizacion['organizacionLogo'] ?></td>
                <td><?php
        if ($organizacion['organizacionEstado'] == "1") {
            echo "Activo";
        } else {
            echo "Inactivo";
        }
            ?></td>
                <td><?php echo $organizacion['fechaCreacion'] ?></td>
                <td><?php echo $organizacion['fechaModificacion'] ?></td>
                <td><span class="modi"><a href="manActualizarOrganizaciones.php?id=<?php echo $organizacion['organizacionId'] ?>"><img src="../images/database_edit.png" title="Editar" alt="Editar" /></a></span></td>
                <td><span class="dele"><a onClick="EliminarDato(<?php echo $organizacion['organizacionId'] ?>); return false" href="eliminar.php?id=<?php echo $organizacion['organizacionId'] ?>"><img src="../images/delete.png" title="Eliminar" alt="Eliminar" /></a></span></td>
            </tr>
        <?php
    }
}
?>

</table>

