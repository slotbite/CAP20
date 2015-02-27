<?PHP

session_start();
include ("../librerias/conexion.php");

$clienteId = $_SESSION['clienteId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}


$queryPerfiles = "  Select *
                    From Perfiles p (nolock)                            
                    Where   p.clienteId = '" . $clienteId . "'
                    and     p.perfilEstado = 1
                    Order by p.perfilNombre";

$consulta = $base_datos->sql_query($queryPerfiles);

if ($consulta) {

    $count = 0;

    $lista = "<table id='tablaListaPerfiles' class='tabla'>";
    $lista.= '<tr><th></th><th>Perfil</th></tr></thead><tbody>';

    while ($row = $base_datos->sql_fetch_assoc($consulta)) {
        $lista.= "<tr><td style='width:16px;'><input type='radio' name='listaPerfiles'/></td>";
        $lista.= "<td style='width:200px;'>" . htmlentities(trim($row['perfilNombre'])) . "</td></tr>";
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


