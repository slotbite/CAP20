<?PHP

session_start();
include ("../librerias/conexion.php");

$clienteId = $_SESSION['clienteId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}


$queryCargos = "  Select distinct c.cargoNombre
                  From Cargos c (nolock)                            
                  Where   c.clienteId = '" . $clienteId . "'
                  and     c.cargoEstado = 1
                  Order by c.cargoNombre";

$consulta = $base_datos->sql_query($queryCargos);

if ($consulta) {

    $count = 0;

    $lista = "<table id='tablaListaCargos' class='tabla'><tbody>";
    //$lista.= '<tr><th></th><th>Cargo</th></tr></thead>';

    while ($row = $base_datos->sql_fetch_assoc($consulta)) {
        $lista.= "<tr><td style='width:16px;'><input type='radio' name='listaCargos'/></td>";
        $lista.= "<td style='width:200px;'>" . htmlentities(trim($row['cargoNombre'])) . "</td></tr>";
        $count = $count + 1;
    }
    $lista.= "</tbody></table>";
            
    if ($count == 0) {
        echo "<label style='color: #FF0000; font-size: 12px'>Búsqueda sin resultados.</label>";
    }
    else{
        echo $lista;
    }
    
} else {
    echo "<label style='color: #FF0000; font-size: 12px'>Búsqueda sin resultados.</label>";
}
?>


