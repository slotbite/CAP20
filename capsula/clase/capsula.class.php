<?php

class Capsula {

    function envCargaTablaCuestionarioPrueba($capsulaId, $capsulaVersion, $usuarioEmail, $administradorId) {
		//USUARIOS DE ENCUESTAS PRUEBA ....
        $stmt = mssql_init("envCargaTablaCuestionarioPrueba");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);
        mssql_bind($stmt, '@usuarioEmail', $usuarioEmail, SQLVARCHAR);
		mssql_bind($stmt, '@administradorId', $administradorId, SQLVARCHAR);
        $result = sql_db::sql_ejecutar_sp($stmt);
		$row = sql_db::sql_fetch_assoc($result);
	    sql_db::sql_close();
        return $row;
    }





    //constructor


    function capMostrarTemas($clienteId, $administradorId, $temaLike, $caso) {

        $stmt = mssql_init("AcapMostrarTemas");
        mssql_bind($stmt, '@clienteId', $clienteId, SQLINT2);
        mssql_bind($stmt, '@administradorId', $administradorId, SQLINT2);
        mssql_bind($stmt, '@temaNombre', $temaLike, SQLVARCHAR);
        mssql_bind($stmt, '@caso', $caso, SQLINT2);
        $result = sql_db::sql_ejecutar_sp($stmt);
        //$row = sql_db::sql_fetch_assoc($result);
        sql_db::sql_close();
        return $result;
    }

    function capGuardarCapsula($capsulaId, $capsulaVersion, $clienteId, $temaNombre, $capsulaNombre, $capsulaDescripcion, $capsulaTipo, $capsulaEstado, $administradorId, $capsulaNumero) {

        //echo $capsulaId . "," . $capsulaVersion . "," . $clienteId . ",'" . $temaNombre . "','" . $capsulaNombre . "','" . $capsulaDescripcion . "'," . $capsulaTipo . "," . $capsulaEstado . "," . $administradorId;

        $stmt = mssql_init("AcapGuardarCapsula");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);
        mssql_bind($stmt, '@clienteId', $clienteId, SQLINT4);
        mssql_bind($stmt, '@temaNombre', $temaNombre, SQLVARCHAR);
        mssql_bind($stmt, '@capsulaNombre', $capsulaNombre, SQLVARCHAR);
        mssql_bind($stmt, '@capsulaDescripcion', $capsulaDescripcion, SQLVARCHAR);
        mssql_bind($stmt, '@capsulaTipo', $capsulaTipo, SQLINT2);
        mssql_bind($stmt, '@capsulaEstado', $capsulaEstado, SQLINT2);
        mssql_bind($stmt, '@administradorId', $administradorId, SQLINT4);
        mssql_bind($stmt, '@capsulaNumero', $capsulaNumero, SQLVARCHAR);
        $result = sql_db::sql_ejecutar_sp($stmt);
        $row = sql_db::sql_fetch_assoc($result);
        sql_db::sql_close();
        return $row;
    }

    /*     * * CONTENIDOS - TEXTO ** */

    function capGuardarContenido($capsulaId, $capsulaVersion, $contenidoId, $contenidoTipo, $contenidoDescripcion, $contenidoUrl, $contenidoObligatorio, $administradorId) {

        //echo $capsulaId . "," . $capsulaVersion . "," . $contenidoId . "," . $contenidoTipo . ",'" . $contenidoDescripcion . "','" . $contenidoUrl . "'," . $administradorId;

        $stmt = mssql_init("AcapGuardarContenido");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);
        mssql_bind($stmt, '@contenidoId', $contenidoId, SQLINT4);
        mssql_bind($stmt, '@contenidoTipo', $contenidoTipo, SQLINT2);
        mssql_bind($stmt, '@contenidoDescripcion', $contenidoDescripcion, SQLTEXT);
        mssql_bind($stmt, '@contenidoUrl', $contenidoUrl, SQLVARCHAR);
        mssql_bind($stmt, '@contenidoObligatorio', $contenidoObligatorio, SQLVARCHAR);
        mssql_bind($stmt, '@administradorId', $administradorId, SQLINT4);
        $result = sql_db::sql_ejecutar_sp($stmt);
        $row = sql_db::sql_fetch_assoc($result);
        sql_db::sql_close();
        return $row;
    }
	


    function capSeleccionarContenido($capsulaId, $capsulaVersion, $contenidoId) {

        //echo $capsulaId . "," . $capsulaVersion . "," . $contenidoId . "," . $contenidoTipo . ",'" . $contenidoDescripcion . "','" . $contenidoUrl . "'," . $administradorId;

        $stmt = mssql_init("AcapSeleccionarContenido");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);
        mssql_bind($stmt, '@contenidoId', $contenidoId, SQLINT4);
        $result = sql_db::sql_ejecutar_sp($stmt);
        $row = sql_db::sql_fetch_assoc($result);
        sql_db::sql_close();
        return $row;
    }

    function capEliminarContenido($capsulaId, $capsulaVersion, $contenidoId, $contenidoTipo) {
        
        $stmt = mssql_init("AcapEliminarContenido");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);
        mssql_bind($stmt, '@contenidoId', $contenidoId, SQLINT4);
        mssql_bind($stmt, '@contenidoTipo', $contenidoTipo, SQLINT2);
        $result = sql_db::sql_ejecutar_sp($stmt);
        $row = sql_db::sql_fetch_assoc($result);
        sql_db::sql_close();
        return $row;
    }

    function capGuardarImagen($capsulaId, $capsulaVersion, $capsulaImagen, $administradorId){

        //echo $capsulaId . "," . $capsulaVersion . "," . $contenidoId . "," . $contenidoTipo . ",'" . $contenidoDescripcion . "','" . $contenidoUrl . "'," . $administradorId;

        $stmt = mssql_init("AcapGuardarImagen");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);
        mssql_bind($stmt, '@capsulaImagen', $capsulaImagen, SQLVARCHAR);
        mssql_bind($stmt, '@administradorId', $administradorId, SQLINT4);
        $result = sql_db::sql_ejecutar_sp($stmt);
        $row = sql_db::sql_fetch_assoc($result);
        sql_db::sql_close();
        return $row;
    }

    /*     * ** PREGUNTAS *** */

    function capGuardarPregunta($capsulaId, $capsulaVersion, $preguntaId, $preguntaTexto, $mensajePositivo, $mensajeNegativo, $administradorId) {

        $stmt = mssql_init("AcapGuardarPregunta");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);
        mssql_bind($stmt, '@preguntaId', $preguntaId, SQLINT4);
        mssql_bind($stmt, '@preguntaTexto', $preguntaTexto, SQLTEXT);
        mssql_bind($stmt, '@mensajePositivo', $mensajePositivo, SQLTEXT);
        mssql_bind($stmt, '@mensajeNegativo', $mensajeNegativo, SQLTEXT);
        mssql_bind($stmt, '@administradorId', $administradorId, SQLINT4);
        $result = sql_db::sql_ejecutar_sp($stmt);
        $row = sql_db::sql_fetch_assoc($result);
        sql_db::sql_close();
        return $row;
    }
       /*     *** FEEDBACK ***    6/2/2015   */
	function capCrearFeedback($capsulaId, $capsulaVersion, $id_feedback, $administradorId ,$c1,$c2,$c3,$c4,$c5 ) {
		
        $stmt = mssql_init("AcapCrearFeedback");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);
        mssql_bind($stmt, '@id_feedback', $id_feedback, SQLINT4);
        //mssql_bind($stmt, '@contenidoFeedback', $contenidoFeedback, SQLTEXT);
        mssql_bind($stmt, '@administradorId', $administradorId, SQLINT4);
		mssql_bind($stmt, '@c1', $c1, SQLTEXT);
		mssql_bind($stmt, '@c2', $c2, SQLTEXT);
		mssql_bind($stmt, '@c3', $c3, SQLTEXT);
		mssql_bind($stmt, '@c4', $c4, SQLTEXT);
		mssql_bind($stmt, '@c5', $c5, SQLTEXT);
        $result = sql_db::sql_ejecutar_sp($stmt);
        $row = sql_db::sql_fetch_assoc($result);
        sql_db::sql_close();
        return $row;
    }
	
	function capCrearAlternativa($capsulaId, $capsulaVersion, $pk_id_feedback, $administradorId,$contenidoAlternativa) {
		
        $stmt = mssql_init("AcapCrearAlternativa");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);
        mssql_bind($stmt, '@pk_id_feedback', $pk_id_feedback, SQLINT4);
        mssql_bind($stmt, '@contenidoAlternativa', $contenidoAlternativa, SQLTEXT);
        mssql_bind($stmt, '@administradorId', $administradorId, SQLINT4);
        $result = sql_db::sql_ejecutar_sp($stmt);
        $row = sql_db::sql_fetch_assoc($result);
        sql_db::sql_close();
        return $row;
    }
	
	function capEliminarFeedback($capsulaId, $id_feedback, $capsulaVersion) {

        $stmt = mssql_init("AcapEliminarFeedback");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@id_feedback', $id_feedback, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);   
        $result = sql_db::sql_ejecutar_sp($stmt);
        $row = sql_db::sql_fetch_assoc($result);
        sql_db::sql_close();
        return $row;
    }
	
	
	function capSeleccionarFeedback($capsulaId, $capsulaVersion, $id_feedback) { 
	//EDITAR
        $stmt = mssql_init("AcapSeleccionarFeedback");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);
        mssql_bind($stmt, '@id_feedback', $id_feedback, SQLINT4);
        $result = sql_db::sql_ejecutar_sp($stmt);
        $row = sql_db::sql_fetch_assoc($result);
        sql_db::sql_close();        
        return $row;
    }
	
    function capSeleccionarAlternativas($capsulaId, $capsulaVersion, $id_feedback) { 
	//EDITAR
        $stmt = mssql_init("AcapSeleccionarAlternativas");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);
        mssql_bind($stmt, '@id_feedback', $id_feedback, SQLINT4);
        $result = sql_db::sql_ejecutar_sp($stmt);
        sql_db::sql_close();
        return $result;
    }

	    /*     *** END ***     */
		
		
    function capGuardarRespuesta($capsulaId, $capsulaVersion, $preguntaId, $respuestaId, $respuestaTexto, $respuestaCorrecta, $alternativa, $respuestaOrden, $administradorId) {

        //echo "Exec AcapGuardarRespuesta " . $capsulaId . "," . $capsulaVersion . "," . $preguntaId . "," . $respuestaId . ",'" . $respuestaTexto . "','" . $respuestaCorrecta . "'," . $administradorId;

        $stmt = mssql_init("AcapGuardarRespuesta");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);
        mssql_bind($stmt, '@preguntaId', $preguntaId, SQLINT4);
        mssql_bind($stmt, '@respuestaId', $respuestaId, SQLINT4);
        mssql_bind($stmt, '@respuestaTexto', $respuestaTexto, SQLTEXT);
        mssql_bind($stmt, '@respuestaCorrecta', $respuestaCorrecta, SQLVARCHAR);
        mssql_bind($stmt, '@alternativa', $alternativa, SQLVARCHAR);
        mssql_bind($stmt, '@respuestaOrden', $respuestaOrden, SQLINT4);
        mssql_bind($stmt, '@administradorId', $administradorId, SQLINT4);
        $result = sql_db::sql_ejecutar_sp($stmt);
        $row = sql_db::sql_fetch_assoc($result);
        sql_db::sql_close();
        return $row;
    }

    function capSeleccionarPregunta($capsulaId, $capsulaVersion, $preguntaId) { 

//        echo "Exec AcapSeleccionarPregunta " . $capsulaId . "," . $capsulaVersion . "," . $preguntaId . "";

        $stmt = mssql_init("AcapSeleccionarPregunta");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);
        mssql_bind($stmt, '@preguntaId', $preguntaId, SQLINT4);
        $result = sql_db::sql_ejecutar_sp($stmt);
        $row = sql_db::sql_fetch_assoc($result);
        sql_db::sql_close();        
        return $row;
    }
    
    
    function capSeleccionarRespuestas($capsulaId, $capsulaVersion, $preguntaId) { // NO UTILIZADA ?

        //echo "Exec AcapGuardarRespuesta " . $capsulaId . "," . $capsulaVersion . "," . $preguntaId . "," . $respuestaId . ",'" . $respuestaTexto . "','" . $respuestaCorrecta . "'," . $administradorId;

        $stmt = mssql_init("AcapSeleccionarRespuestas");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);
        mssql_bind($stmt, '@preguntaId', $preguntaId, SQLINT4);
        $result = sql_db::sql_ejecutar_sp($stmt);
        sql_db::sql_close();
        return $result;
    }
    
    
    function capEliminarPregunta($capsulaId, $capsulaVersion, $preguntaId) {
        
        $stmt = mssql_init("AcapEliminarPregunta");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);
        mssql_bind($stmt, '@preguntaId', $preguntaId, SQLINT4);        
        $result = sql_db::sql_ejecutar_sp($stmt);
        $row = sql_db::sql_fetch_assoc($result);
        sql_db::sql_close();
        return $row;
    }
    
   
    function capGuardarElemento($capsulaId, $capsulaVersion, $elementoTipo, $contenidoId, $preguntaId, $administradorId) {// NO UTILIZADA ?
		
        //echo "Exec AcapGuardarRespuesta " . $capsulaId . "," . $capsulaVersion . "," . $preguntaId . "," . $respuestaId . ",'" . $respuestaTexto . "','" . $respuestaCorrecta . "'," . $administradorId;

        $stmt = mssql_init("AcapGuardarElemento");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);
        mssql_bind($stmt, '@elementoTipo', $elementoTipo, SQLVARCHAR);
        mssql_bind($stmt, '@contenidoId', $contenidoId, SQLINT4);
        mssql_bind($stmt, '@preguntaId', $preguntaId, SQLINT4);        
        mssql_bind($stmt, '@administradorId', $administradorId, SQLINT4);
        sql_db::sql_ejecutar_sp($stmt);
//        $row = sql_db::sql_fetch_assoc($result);
//        sql_db::sql_close();
//        return $row;
    }
    
    
    function capLimpiarElementos($capsulaId, $capsulaVersion) {

        //echo "Exec AcapGuardarRespuesta " . $capsulaId . "," . $capsulaVersion . "," . $preguntaId . "," . $respuestaId . ",'" . $respuestaTexto . "','" . $respuestaCorrecta . "'," . $administradorId;

        $stmt = mssql_init("AcapLimpiarElementos");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);        
        $result = sql_db::sql_ejecutar_sp($stmt);
        $row = sql_db::sql_fetch_assoc($result);
        sql_db::sql_close();
        return $row;
    }
    
    
    function capSeleccionarCapsula($capsulaId, $capsulaVersion) {

        //echo "Exec AcapGuardarRespuesta " . $capsulaId . "," . $capsulaVersion . "," . $preguntaId . "," . $respuestaId . ",'" . $respuestaTexto . "','" . $respuestaCorrecta . "'," . $administradorId;

        $stmt = mssql_init("AcapSeleccionarCapsula");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);        
        $result = sql_db::sql_ejecutar_sp($stmt);
        $row = sql_db::sql_fetch_assoc($result);
        sql_db::sql_close();
        return $row;
    }
    
    
    function capSeleccionarElementos($capsulaId, $capsulaVersion) {

        //echo "Exec AcapGuardarRespuesta " . $capsulaId . "," . $capsulaVersion . "," . $preguntaId . "," . $respuestaId . ",'" . $respuestaTexto . "','" . $respuestaCorrecta . "'," . $administradorId;

        $stmt = mssql_init("AcapSeleccionarElementos");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);        
        $result = sql_db::sql_ejecutar_sp($stmt);        
        sql_db::sql_close();
        return $result;
    }
    
    
    function capSeleccionarImagenes($capsulaId, $capsulaVersion){
        $stmt = mssql_init("AcapSeleccionarImagenes");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);        
        $result = sql_db::sql_ejecutar_sp($stmt);        
        sql_db::sql_close();
        return $result;
    }
    
    
    function capEliminarImagen($capsulaId, $capsulaVersion, $capsulaImagenRuta){
        $stmt = mssql_init("AcapEliminarImagen");
        mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
        mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);        
        mssql_bind($stmt, '@capsulaImagenRuta', $capsulaImagenRuta, SQLVARCHAR);        
        $result = sql_db::sql_ejecutar_sp($stmt);        
        sql_db::sql_close();
        return $result;
    }
    
    
}
?>

