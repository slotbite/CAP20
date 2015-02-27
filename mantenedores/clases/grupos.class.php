<?php 

class grupos{
 //constructor	                    
        
        function manBuscarCliente($clienteId) {
            $query = "      Select LTRIM(RTRIM(clienteNombres)) + ' ' + LTRIM(RTRIM(clienteApellidos)) as 'clienteNombreCompleto'
                            From Clientes c (nolock)                        
                            Where c.clienteId = " . $clienteId . "";
            $result = sql_db::sql_query($query);
            return $result;
        }
    
        
        function manMostrarGrupos($clienteId, $grupoNombre){                                                      
            
            $grupoNombre = str_replace("\'", "''", $grupoNombre);
            $grupoNombre = str_replace('\"', '"', $grupoNombre);
                                       
            $query = "  Select *
                        from Grupos g (nolock)
                        Where g.clienteId = ".$clienteId."";                                
            
            if($grupoNombre != ""){
                $query = $query . " and g.grupoNombre = '".$grupoNombre."'";
            }
            
            $query = $query . " ORDER BY grupoId DESC";
            
            $result = sql_db::sql_query($query);                    
            return $result;
	}                                     
        
        
        function manGuardarGrupo($clienteId, $administradorId, $grupoNombre, $grupoDescripcion, $usuarioModificacion) {
            
            $grupoNombre = str_replace("\'", "''", $grupoNombre);
            $grupoNombre = str_replace('\"', '"', $grupoNombre);
            
            $grupoDescripcion = str_replace("\'", "''", $grupoDescripcion);
            $grupoDescripcion = str_replace('\"', '"', $grupoDescripcion);
            
            $query = "Exec manInsertarGrupo " . $clienteId . "," . $administradorId . ",'" . $grupoNombre . "','" . $grupoDescripcion . "','" . $usuarioModificacion . "'";
            $result = sql_db::sql_query($query);
            return $result;
        }
        
        
        function manGuardarGrupoUsuario($grupoId, $clienteId, $usuarioId, $usuarioModificacion) {
            $query = "Exec manGuardarGrupoUsuario " . $grupoId . "," . $clienteId . "," . $usuarioId . ",'" . $usuarioModificacion . "'";
            $result = sql_db::sql_query($query);
            return $result;
        }
                              

        function manMostrarGrupo($id){               
            $query = "  Select *
                        From Grupos g (nolock)                        
                        Where g.grupoId = ".$id;
            $result = sql_db::sql_query($query);                
            return $result;
	}
        
        
        function manMostrarGrupoUsuarios($grupoId){
        $query = "  Select u.usuarioId, LTRIM(RTRIM(u.usuarioNombres)) + ' ' + LTRIM(RTRIM(u.usuarioApellidos)) as 'nombre', u.usuarioEmail, o.organizacionNombre, s.sectorNombre, a.areaNombre, c.cargoNombre, u.usuarioRut
                    From GruposUsuarios g (nolock)
                    inner join Usuarios u (nolock) on g.usuarioId = u.usuarioId
                    left join Organizaciones o (nolock) on u.organizacionId = o.organizacionId
                    left join Sectores s (nolock) on u.sectorId = s.sectorId and s.organizacionId = o.organizacionId
                    left join Areas a (nolock) on u.areaId = a.areaId and a.organizacionId = o.organizacionId and a.sectorId = s.sectorId
                    left join Cargos c (nolock) on u.cargoId = c.cargoId                     
                    Where g.grupoId = " . $grupoId;
        $result = sql_db::sql_query($query);
        return $result;
    }
        
        
        function manEditarGrupo($grupoId, $clienteId, $grupoNombre, $grupoDescripcion, $usuarios, $grupoEstado, $usuarioModificacion) {
            
            $grupoNombre = str_replace("\'", "''", $grupoNombre);
            $grupoNombre = str_replace('\"', '"', $grupoNombre);
            
            $grupoDescripcion = str_replace("\'", "''", $grupoDescripcion);
            $grupoDescripcion = str_replace('\"', '"', $grupoDescripcion);
            
            $query = "  Delete gu
                        From GruposUsuarios gu
                        Where gu.usuarioId not in (" . $usuarios . ")
                        and gu.grupoId = " . $grupoId . "";
            $result = sql_db::sql_query($query);
            
            
            $query = "Exec manEditarGrupo " . $grupoId . "," . $clienteId . ",'" . $grupoNombre . "','" . $grupoDescripcion . "'," . $grupoEstado . ",'" . $usuarioModificacion . "'";
            $result = sql_db::sql_query($query);
            return $result;
        }                                                                                                                                                                                                                
	
        
	function manAnularGrupo($id){
            $query = "  IF((Select grupoEstado from Grupos g (nolock) Where grupoId = ".$id.") = 1)
                            BEGIN
                                Update g set g.grupoEstado = 0
                                From Grupos g 
                                Where grupoId = ".$id."
                            END
                    ELSE
                            BEGIN
                                Update g set g.grupoEstado = 1
                                From Grupos g
                                Where grupoId = ".$id."
                            END	";        
            $result = sql_db::sql_query($query);  
            return $result;            
	}  
                
}
?>


