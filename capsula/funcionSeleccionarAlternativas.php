<?

session_start();
include("../default.php");

require('clase/capsula.class.php');
$objCapsula = new Capsula();

$id_feedback 	= mb_convert_encoding(trim($_POST['id_feedback']), "ISO-8859-1", "UTF-8");
$capsulaId 		= mb_convert_encoding(trim($_POST['capsulaId']), "ISO-8859-1", "UTF-8");
$capsulaVersion = mb_convert_encoding(trim($_POST['capsulaVersion']), "ISO-8859-1", "UTF-8");

$consulta = $objCapsula->capSeleccionarAlternativas($capsulaId, $capsulaVersion, $id_feedback);

$TR1 	= '<table id="tablaFeedback" cellpadding="0" cellspacing="0"><tr id="first"><td></td><td></td><td style="width: 520px;"><p>Preguntas :</p> </td><td class="Feed" ><input  id="opcion_1" class="textbox" value="" type="text"   maxlength="20"  data-autosize-input="{ "space": 5 }" /></td><td class="Feed" ><input  id="opcion_2" class="textbox" value="" type="text"   maxlength="20"  data-autosize-input="{ "space": 5 }" /></td><td class="Feed" ><input  id="opcion_3" class="textbox" value="" type="text"   maxlength="20"  data-autosize-input="{ "space": 5 }" /></td><td class="Feed" ><input  id="opcion_4" class="textbox" value="" type="text"   maxlength="20"  data-autosize-input="{ "space": 5 }" /></td><td class="Feed" ><input  id="opcion_5" class="textbox" value="" type="text"   maxlength="20"  data-autosize-input="{ "space": 5 }" /></td><td  ></td></tr>';
$count 	= 0;
if ($consulta) {
	while ($alternativas = mssql_fetch_array($consulta)) {
		$count++;
			$TR = $TR."<tr ><td></td><td>".$count.")</td><td ><input type='text' class='textbox' value='". mb_convert_encoding(trim($alternativas['contenidoAlternativa']), "UTF-8", "ISO-8859-1") ."' style='min-width:400px' maxLength='1000' data-autosize-input='{ 'space': 5 }'/></td><td class='Feed'  ><input type='radio' disabled name=''/></td><td class='Feed'  ><input type='radio' disabled name=''/></td><td class='Feed'  ><input type='radio' disabled name=''/></td><td class='Feed'  ><input type='radio' disabled name=''/></td><td class='Feed'  ><input type='radio' disabled name=''/></td><td class='Feed' ><img src='../css/imagenes/asistente/eliminar.png' onClick='eliminarPregunta(this)'/></td></tr>";
			
	}
}
$count++;
$TR = $TR."<tr class='trPregunta' ><td></td><td>".$count." ) </td><td ><input type='text' class='textbox' placeHolder='Escribe aquí' style='min-width:400px' maxLength='1000' data-autosize-input='{ 'space': 5 }' onFocus='agregarPregunta(this)' onBlur='agregarPreguntaB(this)'/> </td><td class='Feed'  ><input type='radio' disabled name=''/></td><td class='Feed'  ><input type='radio' disabled name=''/></td><td class='Feed'  ><input type='radio' disabled name=''/></td><td class='Feed'  ><input type='radio' disabled name=''/></td><td class='Feed'  ><input type='radio' disabled name=''/></td><td class='Feed' ><img src='../css/imagenes/asistente/eliminar.png' onClick='eliminarPregunta(this)'/></td></tr>";

$html 	= $html.$TR1.$TR. "</table>";

echo $html;
?>