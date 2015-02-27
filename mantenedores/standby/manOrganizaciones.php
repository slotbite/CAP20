<?
session_start();

include("../default.php");
$plantilla->setPath('../skins/saam/plantillas/');

$plantilla->setTemplate("header_2");
echo $plantilla->show();

$clienteId =  $_SESSION['clienteId'] ;

if($clienteId == '') {
    echo "<script>window.location='../';</script>";
}
else{     
        
    $plantilla->setTemplate("manMostrar");
                       
    require('clases/organizaciones.class.php');
    $objOrganizacion = new organizaciones;
    $consulta = $objOrganizacion->manMostrarOrganizaciones($clienteId);
        
    $cabecera = "<tr><th>Organización</th><th>Descripción</th><th>Logo</th><th>Estado</th><th>Fecha creación</th><th>Fecha modificación</th><th></th><th></th></tr>";
    $detalle = "";
    $mantenedor = "organizaciones.";
            
    if ($consulta) {
        while ($organizacion = mssql_fetch_array($consulta)) {
            
            $detalle=$detalle . "<tr id='fila-".$organizacion['organizacionId']."'>";
            $detalle=$detalle . "<td>" . $organizacion['organizacionNombre'] . "</td>";
            $detalle=$detalle . "<td>" . $organizacion['organizacionDescripcion'] . "</td>";
            $detalle=$detalle . "<td>" . $organizacion['organizacionLogo'] . "</td>";
            
            if($organizacion['organizacionEstado'] == "1")
            {            
                $detalle=$detalle . "<td>Activo</td>";
            }
            else 
            {
                $detalle=$detalle . "<td>Inactivo</td>";
            }
                        
            $detalle=$detalle . "<td>" . date("d-m-Y",strtotime($organizacion['fechaCreacion'])) . "</td>";
            $detalle=$detalle . "<td>" . date("d-m-Y",strtotime($organizacion['fechaModificacion'])) . "</td>";
            $detalle=$detalle . "<td align='center'><span class='modi'><a href='manActualizarOrganizaciones.php?organizacionId=" . base64_encode($organizacion['organizacionId']) ."'><img src='../skins/saam/img/database_edit.png' title='Editar' alt='Editar' /></a></td>";
            $detalle=$detalle . "<td align='center'><span class='dele'><a onClick='EliminarDato(" . $organizacion['organizacionId'] . "); return false' href='eliminar.php?id=" . $organizacion['organizacionId'] . "'><img src='../skins/saam/img/delete.png' title='Eliminar' alt='Eliminar'/></a></span></td>";
            $detalle=$detalle . "</tr>";                         
        }       
    }
                     
    $plantilla->setVars(array("Cabecera" => "$cabecera", "Detalle" => "$detalle", "Titulo" => "$mantenedor"));                                                   
    echo $plantilla->show(); 
}


$plantilla->setTemplate("footer_2");
echo $plantilla->show();

?>
