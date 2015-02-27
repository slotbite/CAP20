<?php

class ayudas {

    //constructor	                    

    function planBuscarCliente($clienteId) {
        $query = "  Select LTRIM(RTRIM(clienteNombres)) + ' ' + LTRIM(RTRIM(clienteApellidos)) as 'clienteNombreCompleto'
                        From Clientes c (nolock)                        
                        Where c.clienteId = " . $clienteId . "";
        $result = sql_db::sql_query($query);
        return $result;
    }

    
    
    
    function planBuscarCapsulas($clienteId, $nombreTema, $ids) {
        
        $nombreTema = str_replace("\'", "''", $nombreTema);
        $nombreTema = str_replace('\"', '"', $nombreTema);
        
        
        $query = "  Select  c.capsulaId, 
                            t.temaNombre,
                            c.capsulaNombre, 
                            (CASE WHEN(c.capsulaTipo = 1) 
                                THEN 'Cuestionario'  
                            ELSE 
                                (CASE WHEN(c.capsulaTipo = 2) 
					THEN 'Encuesta' 
                                    ELSE 'Contenido' 
                                END)		
                            END) as 'capsulaTipo'
                    From Capsulas c (nolock)
                    inner join Temas t (nolock) on c.temaId = t.temaId
                    Where   c.clienteId = " . $clienteId . "                    
                    and     c.capsulaVersion = (Select MAX(c2.capsulaVersion) From Capsulas c2 (nolock) Where c2.capsulaId = c.capsulaId)
                    and     c.capsulaEstado = 1";
        
        if($nombreTema != ""){
            
            $query = $query . " and t.temaNombre = '" . $nombreTema . "'";
        }
        
        if($ids != ""){
            
            $query = $query . " and c.capsulaId not in (" . $ids . ")";
        }
        
        $query = $query . " Order by 2,3";
        $result = sql_db::sql_query($query);
        return $result;
    }
           
    
    function planBuscarUsuarios($clienteId, $organizacionNombre, $sectorNombre, $areaNombre, $grupoNombre, $evaluacionNombre, $tipoBusqueda, $ids) {
        
        
        $organizacionNombre = str_replace("\'", "''", $organizacionNombre);
        $organizacionNombre = str_replace('\"', '"', $organizacionNombre);                    
        
        $sectorNombre = str_replace("\'", "''", $sectorNombre);
        $sectorNombre = str_replace('\"', '"', $sectorNombre);
        
        $areaNombre = str_replace("\'", "''", $areaNombre);
        $areaNombre = str_replace('\"', '"', $areaNombre);                    
        
        $grupoNombre = str_replace("\'", "''", $grupoNombre);
        $grupoNombre = str_replace('\"', '"', $grupoNombre);
        
        $evaluacionNombre = str_replace("\'", "''", $evaluacionNombre);
        $evaluacionNombre = str_replace('\"', '"', $evaluacionNombre);
        
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
                left join Sectores s (nolock) on u.sectorId = s.sectorId
                left join Areas a (nolock) on u.areaId = a.areaId
                left join Cargos c (nolock) on u.cargoId = c.cargoId";               
        
        
        if($tipoBusqueda == "Org" ){
            
            $usuarios = "Select distinct u.usuarioId, u.nombre, u.usuarioEmail, u.organizacionNombre, u.sectorNombre, u.areaNombre, u.cargoNombre From #usuarios u (nolock) Where u.clienteId = " . $clienteId . " and u.organizacionNombre like LTRIM(RTRIM('" . $organizacionNombre . "%')) and u.sectorNombre like LTRIM(RTRIM('" . $sectorNombre . "%')) and u.areaNombre like LTRIM(RTRIM('" . $areaNombre . "%')) and u.usuarioEstado = 1";                        
            
        }        
        
        if($tipoBusqueda == "Gru" ){
            
            $usuarios = " Select distinct u.usuarioId, u.nombre, u.usuarioEmail, u.organizacionNombre, u.sectorNombre, u.areaNombre, u.cargoNombre From #usuarios u (nolock) inner join GruposUsuarios gu (nolock) on u.usuarioId = gu.usuarioId inner join Grupos g (nolock) on gu.grupoId = g.grupoId Where u.clienteId = " . $clienteId . " and g.grupoNombre like LTRIM(RTRIM('" . $grupoNombre . "%')) and g.grupoEstado = 1 and	u.usuarioEstado = 1";
        }
        
        if($tipoBusqueda == "Eva" ){
            
            $usuarios = " Select distinct u.usuarioId, u.nombre, u.usuarioEmail, u.organizacionNombre, u.sectorNombre, u.areaNombre, u.cargoNombre From #usuarios u (nolock) inner join UsuariosEvaluados ue (nolock) on u.usuarioId = ue.usuarioId inner join Evaluaciones e (nolock) on ue.evaluacionId = e.evaluacionId Where u.clienteId = " . $clienteId . " and e.evaluacionNombre like LTRIM(RTRIM('" . $evaluacionNombre . "%')) and e.evaluacionEstado = 1 and	u.usuarioEstado = 1";
        }
        
        if($ids != ""){
            
            $usuarios = $usuarios . " " . "and u.usuarioId not in (" . $ids . ")";                                    
        }
        
        $queryFinal = $query . " " . $usuarios;
                                              
        //$query = "Exec evaBuscarUsuarios " . $evaluacionId . "," . $clienteId . ",'" . $organizacionNombre . "%','" . $sectorNombre . "%','" . $areaNombre . "%','" . $grupoNombre . "%','" . $tipoBusqueda . "'";
        $result = sql_db::sql_query($queryFinal);
        return $result;
    }

    
    function planBuscarOrganizaciones($clienteId) {
        $query = "  Select *
                    From Organizaciones o (nolock)
                    Where   o.clienteId = " . $clienteId . "
                    and     o.organizacionEstado = 1
                    Order by organizacionNombre";
        $result = sql_db::sql_query($query);
        return $result;
    }
    
    
    function planBuscarSectores($clienteId, $organizacionNombre) {
        
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
    
    
    function planBuscarAreas($clienteId, $organizacionNombre, $sectorNombre) {
        
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
    
    
    function planBuscarGrupos($clienteId) {
        $query = "  Select *
                    From Grupos g (nolock)
                    Where   g.clienteId = " . $clienteId . "
                    and     g.grupoEstado = 1
                    Order by grupoNombre";
        $result = sql_db::sql_query($query);
        return $result;
    }
    
    
    function planBuscarEvaluaciones($clienteId) {
        $query = "  Select *
                    From Evaluaciones e (nolock)
                    Where   e.clienteId = " . $clienteId . "
                    and     e.evaluacionEstado = 1
                    Order by evaluacionNombre";
        $result = sql_db::sql_query($query);
        return $result;
    }
    
    
    function planBuscarTemas($clienteId) {
        $query = "  Select *
                    From Temas t (nolock)
                    Where   t.clienteId = " . $clienteId . "
                    and     t.temaEstado = 1
                    Order by temaNombre";
        $result = sql_db::sql_query($query);
        return $result;
    }
    
}

?>