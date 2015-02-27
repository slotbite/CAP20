<?php

	$total_preguntas 		= 	$_POST["total_preguntas"];
	$Nombre_cap 			= 	$_POST["Nombre_cap"];

    $cont = 0;
    for ($i = 1; $i <= $total_preguntas; $i++) {

        if ($i == 1) {
			echo '<tr class="Pregunta" ><td>';
            echo "Capsula Feedback: </b>$Nombre_cap";
			echo '</td></tr>';
			//echo "<form name='form1' id='form1' method='post' action='insertar.php'>";
        }

        echo '<tr class="Pregunta" ><td>';
        echo "Pregunta $i : <input  class='textbox' name='pregunta$cont' id='pregunta$cont' type='text' style='width:600px;border-radius:5px;' >";
        echo "<br>";
		echo '</td></tr>';
        $cont++;
        if ($i == $total_preguntas) {
			echo '<tr class="Pregunta"  ><td>';
            echo "<input name='total_preguntas' id='total_preguntas' type='hidden' value='$total_preguntas'>";
            echo "<input name='Nombre_cap' id='Nombre_cap' type='hidden' value='$Nombre_cap'>";
			echo '</td></tr>';
        }
    }
?>
