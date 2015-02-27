<?

session_start();
include("../default.php");

require('clase/capsula.class.php');
$objCapsula = new Capsula();

//$clienteId = $_SESSION['clienteId'];
//$administradorId = $_SESSION['administradorId'];

$preguntaId = mb_convert_encoding(trim($_POST['preguntaId']), "ISO-8859-1", "UTF-8");
$capsulaId = mb_convert_encoding(trim($_POST['capsulaId']), "ISO-8859-1", "UTF-8");
$capsulaVersion = mb_convert_encoding(trim($_POST['capsulaVersion']), "ISO-8859-1", "UTF-8");
$capsulaTipo = mb_convert_encoding(trim($_POST['capsulaTipo']), "ISO-8859-1", "UTF-8");


$display = "";

if($capsulaTipo == 1){
    $display = "";
}
else{
    $display = " style='display:none' ";
}



$caso = $_POST['caso'];

$consulta = $objCapsula->capSeleccionarRespuestas($capsulaId, $capsulaVersion, $preguntaId);

$abc = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "ñ", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
$count = 0;

if ($caso == "2") {

    $html = "<table id='tablaAlternativas' cellpadding='0' cellspacing='0'>";

    if ($consulta) {
        while ($respuestas = mssql_fetch_array($consulta)) {

            if (strtoupper(trim($respuestas["respuestaCorrecta"])) == "SI") {
                $html = $html . "<tr><td><input type='radio' name='correcta' checked " . $display .  "/></td>";
            } else {
                $html = $html . "<tr><td><input type='radio' name='correcta' " . $display .  "/></td>";
            }

            $html = $html . "<td>" . $abc[$count] . ") </td><td style='width:1000px'><input type='text' class='inputText' placeHolder='Texto de alternativa " . $abc[$count] . "' style='width:980px' maxLength='1000' value='" . mb_convert_encoding(trim($respuestas['respuestaTexto']), "UTF-8", "ISO-8859-1") . "'/></td><td><img src='../css/imagenes/asistente/eliminar.png' onClick='eliminarAlternativa(this)'/></td></tr>";
            $count++;
        }
    }

    $html = $html . "<tr class='trAlternativa'><td><input type='radio' name='correcta' disabled " . $display .  "/></td><td>" . $abc[$count] . ") </td><td style='width:1000px'><input type='text' class='inputText' placeHolder='Texto de alternativa " . $abc[$count] . "' style='width:980px' maxLength='1000' onFocus='agregarAlternativa(this)' onBlur='agregarAlternativaB(this)'/></td><td><img src='../css/imagenes/asistente/eliminar.png' onClick='eliminarAlternativa(this)'/></td></tr>";
    $html = $html . "</table>";
}
else{//ver
    
    $html = "<table cellpadding='0' cellspacing='0'>";

    if ($consulta) {
        while ($respuestas = mssql_fetch_array($consulta)) {
                                    
            if(strtoupper(trim($respuestas['respuestaCorrecta'])) == "SI"){
                $html = $html . "<tr><td><input type='radio' checked disabled/></td><td style='color:#5D5E6E; padding-left:1px;'>   " . mb_convert_encoding(trim($respuestas['alternativa']), "UTF-8", "ISO-8859-1") . ")  </td><td style='width:1000px; padding-left:2px;'>" . mb_convert_encoding(trim($respuestas['respuestaTexto']), "UTF-8", "ISO-8859-1") . "</td></tr>";
            }
            else{
                $html = $html . "<tr><td><input type='radio' disabled/></td><td style='color:#5D5E6E; padding-left:1px;'>   " . mb_convert_encoding(trim($respuestas['alternativa']), "UTF-8", "ISO-8859-1") . ")  </td><td style='width:1000px; padding-left:2px;'>" . mb_convert_encoding(trim($respuestas['respuestaTexto']), "UTF-8", "ISO-8859-1") . "</td></tr>";
            }
            
            $count++;
        }
    }

    $html = $html . "</table><br/>";
    
}


echo $html;
?>