<?php

class evaluaciones {

    //constructor	                    

    function evaBuscarCliente($clienteId) {
        $query = "  Select LTRIM(RTRIM(clienteNombres)) + ' ' + LTRIM(RTRIM(clienteApellidos)) as 'clienteNombreCompleto'
                        From Clientes c (nolock)                        
                        Where c.clienteId = " . $clienteId . "";
        $result = sql_db::sql_query($query);
        return $result;
    }

    
    function evaBuscarAdministrador($administradorId) {
        $query = "  Select LTRIM(RTRIM(administradorNombres)) + ' ' + LTRIM(RTRIM(administradorApellidos)) as 'administradorNombreCompleto'
                        From Administradores a (nolock)                        
                        Where a.administradorId = " . $administradorId . "";
        $result = sql_db::sql_query($query);
        return $result;
    }

    
    function evaMostrarEvaluaciones($clienteId, $administradorId, $temaNombre, $evaluacionNombre) {
        
        $temaNombre = str_replace("\'", "''", $temaNombre);
        $temaNombre = str_replace('\"', '"', $temaNombre);
            
        $evaluacionNombre = str_replace("\'", "''", $evaluacionNombre);
        $evaluacionNombre = str_replace('\"', '"', $evaluacionNombre);                                     
        
        $query = "  Select e.*, t.temaNombre
                        From Evaluaciones e (nolock)
                        inner join Temas t (nolock) on e.temaId = t.temaId
                        Where e.clienteId = " . $clienteId . "
                        and   e.administradorId = " . $administradorId . "";
        
        
        if($temaNombre != ""){
                   
            $query = $query . " and t.temaNombre = '" . $temaNombre . "'";
        }
        
        if($evaluacionNombre != ""){
                   
            $query = $query . " and e.evaluacionNombre = '" . $evaluacionNombre . "'";
        }
        
        $query = $query . " Order by e.evaluacionId desc";
                
        $result = sql_db::sql_query($query);
        return $result;
    }

    /************** Ingresar evaluación *************/   

    function evaBuscarTemas($clienteId, $administradorId, $perfilId) {
        $query = "  Select *
                    From Temas t (nolock)
                    Where   t.clienteId = " . $clienteId . "                     
                    and     t.temaEstado = 1";
        
        if($perfilId == "2"){
           $query = $query . " and t.usuarioCreacion = ". $administradorId . "";           
        }        
        
        $query = $query . " Order by temaNombre";
        
        $result = sql_db::sql_query($query);
        return $result;
    }

    function evaBuscarTemasMantenedor($clienteId, $administradorId, $perfilId) {
        $query = "  Select *
                    From Temas t (nolock)
                    Where   t.clienteId = " . $clienteId . "";

        if ($perfilId == "2") {
            $query = $query . " and t.usuarioCreacion = " . $administradorId . "";
        }

        $query = $query . " Order by temaNombre";

        $result = sql_db::sql_query($query);
        return $result;
    }

    function evaBuscarEvaMantenedor($clienteId, $administradorId, $perfilId) {
        $query = "  select evaluacionNombre from Evaluaciones
                    Where   clienteId = " . $clienteId . "";

        if ($perfilId == "2") {
            $query = $query . " and administradorId = " . $administradorId . "";
        }

        $query = $query . " Order by evaluacionNombre";

        $result = sql_db::sql_query($query);
        return $result;
    }

    function evaBuscarCapsulas($clienteId, $nombreTema, $administradorId, $perfilId, $ids) {
        
        $temaNombre = str_replace("\'", "''", $temaNombre);
        $temaNombre = str_replace('\"', '"', $temaNombre);      
                                
        $query = "  Select  c.capsulaId, 
                            CASE WHEN c.capsulaNumero is NULL or LTRIM(RTRIM(c.capsulaNumero)) = '' THEN c.capsulaNombre ELSE LTRIM(RTRIM(c.capsulaNumero)) + '.- ' + c.capsulaNombre END as capsulaNombre, 
                            t.temaNombre
                    From Capsulas c (nolock)
                    inner join Temas t (nolock) on c.temaId = t.temaId
                    Where   c.clienteId = " . $clienteId . "
                    and     t.temaNombre = '" . $nombreTema . "'                    
                    and     c.capsulaVersion = (Select MAX(c2.capsulaVersion) From Capsulas c2 (nolock) Where c2.capsulaId = c.capsulaId)
                    and     c.capsulaEstado = 1
                    and     c.capsulaTipo = 1";
        
        
       if($perfilId == "2"){
           $query = $query . " and c.usuarioCreacion = ". $administradorId . "";           
       }           
        
        if($ids != ""){
            $query = $query . " and c.capsulaId not in (".$ids.")";
        }
        
        $query = $query . " Order by capsulaNombre";
        
        $result = sql_db::sql_query($query);
        return $result;
    }

    
    function evaBuscarOrganizaciones($clienteId) {
        $query = "  Select *
                    From Organizaciones o (nolock)
                    Where   o.clienteId = " . $clienteId . "
                    and     o.organizacionEstado = 1
                    Order by organizacionNombre";
        $result = sql_db::sql_query($query);
        return $result;
    }

    
    function evaBuscarSectores($clienteId, $organizacionNombre) {
        
        $organizacionNombre = str_replace("\'", "''", $organizacionNombre);
        $organizacionNombre = str_replace('\"', '"', $organizacionNombre);                    
        
        $query = "  Select o.organizacionNombre, s.sectorNombre
                    From Organizaciones o (nolock)
                    inner join Sectores s (nolock) on o.organizacionId = s.organizacionId
                    Where   o.clienteId = " . $clienteId . "
                    and     o.organizacionNombre like LTRIM(RTRIM('" . $organizacionNombre . "%'))
                    and     o.organizacionEstado = 1
                    and     s.sectorEstado = 1
                    Order by o.organizacionNombre, s.sectorNombre";
        $result = sql_db::sql_query($query);
        return $result;
    }

    
    function evaBuscarAreas($clienteId, $organizacionNombre, $sectorNombre) {
        
        $organizacionNombre = str_replace("\'", "''", $organizacionNombre);
        $organizacionNombre = str_replace('\"', '"', $organizacionNombre);                    
        
        $sectorNombre = str_replace("\'", "''", $sectorNombre);
        $sectorNombre = str_replace('\"', '"', $sectorNombre);
        
        $query = "  Select o.organizacionNombre, s.sectorNombre, a.areaNombre
                    From Organizaciones o (nolock)
                    inner join Sectores s (nolock) on o.organizacionId = s.organizacionId
                    inner join Areas a (nolock) on o.organizacionId = a.organizacionId and s.sectorId = a.sectorId
                    Where   o.clienteId = " . $clienteId . "
                    and     o.organizacionNombre like LTRIM(RTRIM('" . $organizacionNombre . "%'))
                    and     s.sectorNombre like LTRIM(RTRIM('" . $sectorNombre . "%'))
                    and     o.organizacionEstado = 1
                    and     s.sectorEstado = 1
                    and     a.areaEstado = 1
                    Order by o.organizacionNombre, s.sectorNombre, a.areaNombre";
        $result = sql_db::sql_query($query);
        return $result;
    }

    
    function evaBuscarGrupos($clienteId) {
        $query = "  Select *
                    From Grupos g (nolock)
                    Where   g.clienteId = " . $clienteId . "
                    and     g.grupoEstado = 1
                    Order by grupoNombre";
        $result = sql_db::sql_query($query);
        return $result;
    }

    
    function evaBuscarUsuarios($clienteId, $organizacionNombre, $sectorNombre, $areaNombre, $grupoNombre, $tipoBusqueda, $ids) {
        
        $organizacionNombre = str_replace("\'", "''", $organizacionNombre);
        $organizacionNombre = str_replace('\"', '"', $organizacionNombre);                    
        
        $sectorNombre = str_replace("\'", "''", $sectorNombre);
        $sectorNombre = str_replace('\"', '"', $sectorNombre);
        
        $areaNombre = str_replace("\'", "''", $areaNombre);
        $areaNombre = str_replace('\"', '"', $areaNombre);                    
        
        $grupoNombre = str_replace("\'", "''", $grupoNombre);
        $grupoNombre = str_replace('\"', '"', $grupoNombre);
        
        $ids = str_replace("\'", "''", $ids);
        $ids = str_replace('\"', '"', $ids);
                
        
        $query = "";
        $usuarios = "";
        $queryFinal = "";        
        
        $query = "CREATE TABLE #usuarios(usuarioId numeric(18), nombre varchar(250), usuarioEmail varchar(50), organizacionNombre varchar(250), sectorNombre varchar(250), areaNombre varchar(250), cargoNombre varchar(250), clienteId numeric(18), usuarioEstado int)
                Insert into #usuarios
                Select distinct u.usuarioId, (LTRIM(RTRIM(u.usuarioNombres)) + ' ' + LTRIM(RTRIM(u.usuarioApellidos))) as 'nombre', u.usuarioEmail, ISNULL(o.organizacionNombre,''), ISNULL(s.sectorNombre,''), ISNULL(a.areaNombre, ''), ISNULL(c.cargoNombre, ''), u.clienteId, u.usuarioEstado
                From Usuarios u (nolock)
                inner join Organizaciones o (nolock) on u.organizacionId = o.organizacionId
                left join Sectores s (nolock) on u.sectorId = s.sectorId and s.organizacionId = o.organizacionId
                left join Areas a (nolock) on u.areaId = a.areaId and a.organizacionId = o.organizacionId and a.sectorId = s.sectorId
                left join Cargos c (nolock) on u.cargoId = c.cargoId";               
        
        
        if($tipoBusqueda == "Org" ){
            
            $usuarios = "Select distinct u.usuarioId, u.nombre, u.usuarioEmail, u.organizacionNombre, u.sectorNombre, u.areaNombre, u.cargoNombre From #usuarios u (nolock) Where u.clienteId = " . $clienteId . " and u.organizacionNombre like LTRIM(RTRIM('" . $organizacionNombre . "%')) and u.sectorNombre like LTRIM(RTRIM('" . $sectorNombre . "%')) and u.areaNombre like LTRIM(RTRIM('" . $areaNombre . "%')) and u.usuarioEstado = 1";                        
            
        }        
        
        if($tipoBusqueda == "Gru" ){
            
            $usuarios = " Select distinct u.usuarioId, u.nombre, u.usuarioEmail, u.organizacionNombre, u.sectorNombre, u.areaNombre, u.cargoNombre From #usuarios u (nolock) inner join GruposUsuarios gu (nolock) on u.usuarioId = gu.usuarioId inner join Grupos g (nolock) on gu.grupoId = g.grupoId Where u.clienteId = " . $clienteId . " and g.grupoNombre like LTRIM(RTRIM('" . $grupoNombre . "%')) and g.grupoEstado = 1 and	u.usuarioEstado = 1";
        }
                
        if($ids != ""){
            
            $usuarios = $usuarios . " " . "and u.usuarioId not in (" . $ids . ")";                                    
        }
        
        $queryFinal = $query . " " . $usuarios;
                                              
        //$query = "Exec evaBuscarUsuarios " . $evaluacionId . "," . $clienteId . ",'" . $organizacionNombre . "%','" . $sectorNombre . "%','" . $areaNombre . "%','" . $grupoNombre . "%','" . $tipoBusqueda . "'";
        $result = sql_db::sql_query($queryFinal);
        return $result;
    }

    
    function evaGuardarCabecera($clienteId, $administradorId, $temaNombre, $evaluacionNombre, $evaluacionDescripcion, $usuarioModificacion) {        
        
        $temaNombre = str_replace("\'", "''", $temaNombre);
        $temaNombre = str_replace('\"', '"', $temaNombre);
        
        $evaluacionNombre = str_replace("\'", "''", $evaluacionNombre);
        $evaluacionNombre = str_replace('\"', '"', $evaluacionNombre);
        
        $evaluacionDescripcion = str_replace("\'", "''", $evaluacionDescripcion);
        $evaluacionDescripcion = str_replace('\"', '"', $evaluacionDescripcion);
        
        $query = "Exec evaGuardarCabecera " . $clienteId . "," . $administradorId . ",'" . $temaNombre . "','" . $evaluacionNombre . "','" . $evaluacionDescripcion . "','" . $usuarioModificacion . "'";
        $result = sql_db::sql_query($query);
        return $result;
    }

    
    function evaGuardarCapsula($evaluacionId, $clienteId, $temaNombre, $capsulaId, $ponderacion, $usuarioModificacion) {
       
        $temaNombre = str_replace("\'", "''", $temaNombre);
        $temaNombre = str_replace('\"', '"', $temaNombre);                
                
        $query = "Exec evaGuardarCapsula " . $evaluacionId . "," . $clienteId . ",'" . $temaNombre . "'," . $capsulaId . "," . $ponderacion . ",'" . $usuarioModificacion . "'";
        $result = sql_db::sql_query($query);
        return $result;
    }

    
    function evaGuardarPractica($evaluacionId, $clienteId, $temaNombre, $practicaId, $practicaNombre, $ponderacion, $usuarioModificacion) {
        
        $temaNombre = str_replace("\'", "''", $temaNombre);
        $temaNombre = str_replace('\"', '"', $temaNombre);
                
        $practicaNombre = str_replace("\'", "''", $practicaNombre);
        $practicaNombre = str_replace('\"', '"', $practicaNombre);                
        
        $query = "Exec evaGuardarPractica " . $evaluacionId . "," . $clienteId . ",'" . $temaNombre . "'," . $practicaId . ",'" . $practicaNombre . "'," . $ponderacion . ",'" . $usuarioModificacion . "'";
        $result = sql_db::sql_query($query);
        return $result;
    }
    
    
    function evaGuardarUsuario($evaluacionId, $clienteId, $usuarioId, $usuarioModificacion) {
        $query = "Exec evaGuardarUsuario " . $evaluacionId . "," . $clienteId . "," . $usuarioId . ",'" . $usuarioModificacion . "'";
        $result = sql_db::sql_query($query);
        return $result;
    }

    
    /************** Ver calificaciones *************/ 
    
    function evaBuscarTipoEvaluacion($clienteId) {
        $query = "Select ec.escalaId From EscalasClientes ec (nolock) Where ec.clienteId = " . $clienteId . "";
        $result = sql_db::sql_query($query);
        return $result;
    }
      
    
    function evaCalcularNotas($evaluacionId, $clienteId) {
        $query = "Exec evaCalcularNotas " . $evaluacionId . "," . $clienteId . "";
        $result = sql_db::sql_query($query);
        return $result;
    }
    
    
    function evaMostrarNotas($evaluacionId, $listaEvaluaciones) {
        
        $listaEvaluaciones = str_replace("\'", "''", $listaEvaluaciones);
        
        $query = "Exec evaBuscarNotasUsuarios " . $evaluacionId . ",'" . $listaEvaluaciones . "'";
        $result = sql_db::sql_query($query);
        return $result;
    }

    
    function evaGuardarNotaUsuario($clienteId, $evaluacionId, $usuarioId, $practicaId, $nota, $ponderacion, $usuarioModificacion) {
        $query = "Exec evaGuardarNotaUsuario " . $clienteId . "," . $evaluacionId . "," . $usuarioId . "," . $practicaId . "," . $nota . "," . $ponderacion . ",'" . $usuarioModificacion . "'";
        $result = sql_db::sql_query($query);
        return $result;
    }
    
    
    /************** Editar calificaciones *************/ 
               

    function evaMostrarEvaluacion($evaluacionId) {
        $query = "  Select e.evaluacionNombre, e.evaluacionDescripcion, t.temaNombre, e.evaluacionEstado
                    From Evaluaciones e (nolock) 
                    inner join Temas t (nolock) on e.temaId = t.temaId                     
                    Where e.evaluacionId = " . $evaluacionId;
        $result = sql_db::sql_query($query);
        return $result;
    }
    
    
    function evaMostrarEvaluacionCapsulas($evaluacionId) {
        $query = "  Select  CASE WHEN c.capsulaNumero is NULL or LTRIM(RTRIM(c.capsulaNumero)) = '' THEN c.capsulaNombre ELSE LTRIM(RTRIM(c.capsulaNumero)) + '.- ' + c.capsulaNombre END as capsulaNombre, 
                            CAST(ce.ponderacion as int) as 'ponderacion', c.capsulaId, c.capsulaVersion
                    From CapsulasEvaluadas ce (nolock)
                    inner join Capsulas c (nolock) on ce.capsulaId = c.capsulaId and ce.capsulaVersion = c.capsulaVersion                     
                    Where ce.evaluacionId = " . $evaluacionId . "
                    Order by ce.fechaCreacion";
        $result = sql_db::sql_query($query);
        return $result;
    }

    
    function evaMostrarEvaluacionPracticas($evaluacionId) {
        $query = "  Select practicaNombre, CAST(ponderacion as int) as 'ponderacion', practicaId
                    From PracticasEvaluadas pe (nolock)                     
                    Where pe.evaluacionId = " . $evaluacionId . "
                    Order by pe.fechaCreacion";
        $result = sql_db::sql_query($query);
        return $result;
    }

    
    function evaMostrarEvaluacionUsuarios($evaluacionId) {
        $query = "  Select u.usuarioId, LTRIM(RTRIM(u.usuarioNombres)) + ' ' + LTRIM(RTRIM(u.usuarioApellidos)) as 'nombre', u.usuarioEmail, o.organizacionNombre, s.sectorNombre, a.areaNombre, c.cargoNombre, u.usuarioRut
                    From UsuariosEvaluados ue (nolock)
                    inner join Usuarios u (nolock) on ue.usuarioId = u.usuarioId
                    left join Organizaciones o (nolock) on u.organizacionId = o.organizacionId
                    left join Sectores s (nolock) on u.sectorId = s.sectorId and s.organizacionId=o.organizacionId
                    left join Areas a (nolock) on u.areaId = a.areaId and a.sectorId = s.sectorId and a.organizacionId=o.organizacionId
                    left join Cargos c (nolock) on u.cargoId = c.cargoId                     
                    Where ue.evaluacionId = " . $evaluacionId;
        $result = sql_db::sql_query($query);
        return $result;
    }

    
    function evaEditarEvaluacion($evaluacionId, $clienteId, $administradorId, $temaNombre, $evaluacionNombre, $evaluacionDescripcion, $evaluacionEstado, $idsCapsulas, $idsPracticas, $idsUsuarios, $usuarioModificacion) {
        
        $temaNombre = str_replace("\'", "''", $temaNombre);
        $temaNombre = str_replace('\"', '"', $temaNombre);
        
        $evaluacionNombre = str_replace("\'", "''", $evaluacionNombre);
        $evaluacionNombre = str_replace('\"', '"', $evaluacionNombre);
        
        $evaluacionDescripcion = str_replace("\'", "''", $evaluacionDescripcion);
        $evaluacionDescripcion = str_replace('\"', '"', $evaluacionDescripcion);
        
        $idsCapsulas = str_replace("\'", "''", $idsCapsulas);
        $idsCapsulas = str_replace('\"', '"', $idsCapsulas);
        
        $idsPracticas = str_replace("\'", "''", $idsPracticas);
        $idsPracticas = str_replace('\"', '"', $idsPracticas);
        
        $idsUsuarios = str_replace("\'", "''", $idsUsuarios);
        $idsUsuarios = str_replace('\"', '"', $idsUsuarios);
        
        
        $query = "  Delete ce
                    From CapsulasEvaluadas ce
                    Where ce.capsulaId not in (" . $idsCapsulas . ")
                    and ce.evaluacionId = " . $evaluacionId . "";
        $result = sql_db::sql_query($query);
        
        $query = "  Delete pe
                    From PracticasEvaluadas pe
                    Where pe.practicaId not in (" . $idsPracticas . ")
                    and pe.evaluacionId = " . $evaluacionId . "";
        $result = sql_db::sql_query($query);
        
        $query = "  Delete ue
                    From UsuariosEvaluados ue
                    Where ue.usuarioId not in (" . $idsUsuarios . ")
                    and ue.evaluacionId = " . $evaluacionId . "";
        $result = sql_db::sql_query($query);                                    
        
        
        $query = "Exec evaEditarEvaluacion " . $evaluacionId . "," . $clienteId . "," . $administradorId . ",'" . $temaNombre . "','" . $evaluacionNombre . "','" . $evaluacionDescripcion . "'," . $evaluacionEstado .",'" . $usuarioModificacion . "'";
        $result = sql_db::sql_query($query);
        return $result;
    }

    
    function evaEditarCapsula($evaluacionId, $clienteId, $temaNombre, $capsulaId, $ponderacion, $usuarioModificacion) {
        
        $temaNombre = str_replace("\'", "''", $temaNombre);
        $temaNombre = str_replace('\"', '"', $temaNombre);
        
        $query = "Exec evaEditarCapsula " . $evaluacionId . "," . $clienteId . ",'" . $temaNombre . "'," . $capsulaId . "," . $ponderacion . ",'" . $usuarioModificacion . "'";
        $result = sql_db::sql_query($query);
        return $result;
    }

    
           
    /************** Anular planificacion *************/ 
    
    
    function evaAnularEvaluacion($id){
            $query = "IF((Select evaluacionEstado from Evaluaciones e (nolock) Where evaluacionId = ".$id.") = 1)
                            BEGIN
                                    Update e set e.evaluacionEstado = 0
                                    From Evaluaciones e
                                    Where evaluacionId = ".$id."
                            END
                    ELSE
                            BEGIN
                                    Update e set e.evaluacionEstado = 1
                                    From Evaluaciones e
                                    Where evaluacionId = ".$id."
                            END	";        
            $result = sql_db::sql_query($query);  
            return $result;            
	}
    
    
    
}

?>