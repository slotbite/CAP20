<?php

class Registro {

    //constructor	                    
    function Registro() {
        
    }

    function ListarCapsulasDuplicadas($fecha_desde, $fecha_hasta) {
        $query = "SELECT e.capsulaId, 
                        CASE WHEN c.capsulaNumero is NULL or LTRIM(RTRIM(c.capsulaNumero)) = '' THEN c.capsulaNombre ELSE LTRIM(RTRIM(c.capsulaNumero)) + '.- ' + c.capsulaNombre END as capsulaNombre, 
                        e.capsulaVersion, e.usuarioId,u.usuarioNombres,u.usuarioApellidos, COUNT(*)as Contador,
                        c.capsulaTipo as tipo,
                        CASE    WHEN c.capsulaTipo = 1 THEN 'Cuestionario'
                                WHEN c.capsulaTipo = 2 THEN 'Encuesta'
                                WHEN c.capsulaTipo = 4 THEN 'Contenido'
                                ELSE ''END as tipoNombre
                 FROM Envios e (nolock)
                 LEFT JOIN Capsulas c (nolock) on e.capsulaId = c.capsulaId and e.capsulaVersion = c.capsulaVersion
                 LEFT JOIN Usuarios u (nolock) on e.usuarioId = u.usuarioId
                 Where fechaEnvio between '$fecha_desde 00:00:00' and '$fecha_hasta 23:59:59' and c.capsulaTipo  <> 3
		 group by e.capsulaId, c.capsulaNombre, c.capsulaNumero, e.capsulaVersion, e.usuarioId, u.usuarioNombres,u.usuarioApellidos,c.capsulaTipo
		 Having COUNT(*) > 1
		 Order by  1";
        $result = sql_db::sql_query($query);
        return $result;
    }

    function TraerEnviosCapsula($tabla, $capsulaId, $capsulaVersion, $usuarioId) {
        $query = "SELECT envioId, us.capsulaId, us.capsulaVersion, c.capsulaNombre, us.usuarioId, u.usuarioNombres, u.usuarioApellidos, COUNT(us.preguntaId) AS cantidad 
			  FROM $tabla us (nolock)
                          LEFT JOIN Capsulas c (nolock) on us.capsulaId = c.capsulaId and us.capsulaVersion = c.capsulaVersion 
                          LEFT JOIN Usuarios u (nolock) on us.usuarioId = u.usuarioId
                          Where us.capsulaId = $capsulaId 
			  and   us.capsulaVersion = $capsulaVersion 
                          and   us.usuarioId = $usuarioId
			  Group by envioId, us.capsulaId, us.capsulaVersion, c.capsulaNombre ,us.usuarioId,u.usuarioNombres,u.usuarioApellidos
			  Order by envioId asc";
        //echo $query;
        $result = sql_db::sql_query($query);
        return $result;
    }

    function TraerDetalleEnviosCapsula($tabla, $capsulaId, $usuarioId, $envioId) {
        $query = "  SELECT envioId,u.capsulaId,c.capsulaNombre,u.capsulaVersion,u.usuarioId,textoRespuesta,u.fechaCreacion, p.preguntaOrden, dbo.udf_StripHTML(p.preguntaTexto) as preguntaTexto
                    FROM $tabla u (nolock)
                    LEFT JOIN Capsulas c (nolock) on u.capsulaId = c.capsulaId and u.capsulaVersion = c.capsulaVersion
                    LEFT JOIN Usuarios s (nolock) on u.usuarioId = s.usuarioId
                    LEFT JOIN Preguntas p (nolock) on u.preguntaId = p.preguntaId
                    Where u.capsulaId = $capsulaId and u.usuarioId = $usuarioId and envioId = $envioId ";
        //echo $query;
        $result = sql_db::sql_query($query);
        return $result;
    }

    function EliminarRegistrosDuplicados($envioId, $capsulaId, $capsulaVersion, $usuarioId) {
        $query = "EXEC indEliminarRegistrosDuplicados $envioId, $capsulaId, $capsulaVersion,$usuarioId";
        $result = sql_db::sql_query($query);
        return $result;
    }

    function seleccionarRegistrosInactivos($fecha_desde, $fecha_hasta) {
        $query = "EXEC indSeleccionarRegistrosInactivos '$fecha_desde','$fecha_hasta'";
        //echo $query;
        $result = sql_db::sql_query($query);
        return $result;
    }

    function EliminarRegistrosInactivosFechas($fecha_desde, $fecha_hasta) {
        $query = "EXEC indEliminarRegistrosInactivosFecha '$fecha_desde','$fecha_hasta'";
        //echo $query;
        $result = sql_db::sql_query($query);
        return $result;
    }
    
    
    function seleccionarLimites(){
        $query = "Exec indSemaforoColores 'CAP'";
        //echo $query;
        $result = sql_db::sql_query($query);
        return $result;                
    }
    
    
    function seleccionarIndicadoresAnual($yyyy, $mm, $personalId, $usuarioNombre){
        $query = "Exec indIndicadoresCAPAnual " . $yyyy . "," . $mm . "," . $personalId . ",'" . $usuarioNombre . "'";                
        //echo $query;
        $result = sql_db::sql_query($query);
        return $result;                
    }
    
    function recalcularIndicadoresMensual($yyyy, $mm){
        $query = "Exec indIndicadoresCAPCarga " . $yyyy . "," . $mm ;                
        //echo $query;
        $result = sql_db::sql_query($query);
        return $result;                
    }

}
?>