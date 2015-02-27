<?php 

class usuarios{
 //constructor	                    
        
        function manBuscarCliente($clienteId) {
            $query = "      Select LTRIM(RTRIM(clienteNombres)) + ' ' + LTRIM(RTRIM(clienteApellidos)) as 'clienteNombreCompleto'
                            From Clientes c (nolock)                        
                            Where c.clienteId = " . $clienteId . "";
            $result = sql_db::sql_query($query);
            return $result;
        }
    
        function manMostrarUsuarios($clienteId, $usuarioNombres, $usuarioApellidos, $organizacionNombre, $sectorNombre, $areaNombre){                                                      
            
            $usuarioNombres = str_replace("\'", "''", $usuarioNombres);
            $usuarioNombres = str_replace('\"', '"', $usuarioNombres);
            
            $usuarioApellidos = str_replace("\'", "''", $usuarioApellidos);
            $usuarioApellidos = str_replace('\"', '"', $usuarioApellidos);
            
            $organizacionNombre = str_replace("\'", "''", $organizacionNombre);
            $organizacionNombre = str_replace('\"', '"', $organizacionNombre);
            
            $sectorNombre = str_replace("\'", "''", $sectorNombre);
            $sectorNombre = str_replace('\"', '"', $sectorNombre);
            
            $areaNombre = str_replace("\'", "''", $areaNombre);
            $areaNombre = str_replace('\"', '"', $areaNombre);
            
            $query = "  Select top 100 u.*, o.organizacionNombre, ISNULL(s.sectorNombre,'') as 'sectorNombre', ISNULL(a.areaNombre,'') as 'areaNombre', ISNULL(c.cargoNombre,'') as 'cargoNombre'
                         From Usuarios u (nolock)
                           inner join Organizaciones o (nolock) on u.organizacionId = o.organizacionId
                           left join Cargos c (nolock) on u.cargoId = c.cargoId
                           left join Sectores s (nolock) on u.sectorId = s.sectorId and s.organizacionId =   
						   o.organizacionId
                           left join Areas a (nolock) on u.areaId = a.areaId and a.sectorId = s.sectorId and a.organizacionId = o.organizacionId
                            Where	u.clienteId = ".$clienteId."";                        

            if($usuarioNombres != ""){
                $query =  $query." and u.usuarioNombres = '".$usuarioNombres."'";
            }
            
            if($usuarioApellidos != ""){
                $query =  $query." and u.usuarioApellidos = '".$usuarioApellidos."'";
            }
                        
            if($organizacionNombre != ""){
                $query =  $query." and o.organizacionNombre = '".$organizacionNombre."'";
            }
                        
            if($sectorNombre != ""){
                $query =  $query." and s.sectorNombre = '".$sectorNombre."'";
            }
            
            if($areaNombre != ""){
                $query =  $query." and a.areaNombre = '".$areaNombre."'";
            }
            
            $query = $query." Order by usuarioId desc";
            
            $result = sql_db::sql_query($query);                    
            return $result;
	}        
         
       function manMostrarUsuario($id){               
            $query = "  Select u.*, o.organizacionNombre, ISNULL(s.sectorNombre,'') as 'sectorNombre', ISNULL(a.areaNombre,'') as 'areaNombre', ISNULL(c.cargoNombre,'') as 'cargoNombre'
                        From Usuarios u (nolock)
                        inner join Organizaciones o (nolock) on u.organizacionId = o.organizacionId
                        left join Cargos c (nolock) on u.cargoId = c.cargoId
                        left join Sectores s (nolock) on u.sectorId = s.sectorId and s.organizacionId = o.organizacionId
                        left join Areas a (nolock) on u.areaId = a.areaId and a.sectorId = s.sectorId and a.organizacionId = o.organizacionId
                        Where u.usuarioId = ".$id;
            $result = sql_db::sql_query($query);                
            return $result;
		}
        
        function manEditarUsuario($clienteId, $usuarioId, $usuarioRut, $usuarioNombres, $usuarioApellidos, $usuarioEmail, $cargoNombre, $organizacionNombre, $sectorNombre, $areaNombre, $usuarioEstado, $usuarioModificacion){                              
            
            $usuarioNombres = str_replace("\'", "''", $usuarioNombres);
            $usuarioNombres = str_replace('\"', '"', $usuarioNombres);
            
            $usuarioApellidos = str_replace("\'", "''", $usuarioApellidos);
            $usuarioApellidos = str_replace('\"', '"', $usuarioApellidos);
            
            $organizacionNombre = str_replace("\'", "''", $organizacionNombre);
            $organizacionNombre = str_replace('\"', '"', $organizacionNombre);
            
            $sectorNombre = str_replace("\'", "''", $sectorNombre);
            $sectorNombre = str_replace('\"', '"', $sectorNombre);
            
            $areaNombre = str_replace("\'", "''", $areaNombre);
            $areaNombre = str_replace('\"', '"', $areaNombre);
            
            $usuarioEmail = str_replace("\'", "''", $usuarioEmail);
            $usuarioEmail = str_replace('\"', '"', $usuarioEmail);
            
            $cargoNombre = str_replace("\'", "''", $cargoNombre);
            $cargoNombre = str_replace('\"', '"', $cargoNombre);
            
            
            $query = "Exec manEditarUsuario ".$clienteId.",".$usuarioId.",'".$usuarioRut."','".$usuarioNombres."','".$usuarioApellidos."','".$usuarioEmail."','".$cargoNombre."','".$organizacionNombre."','".$sectorNombre."','".$areaNombre."',".$usuarioEstado.",'".$usuarioModificacion."'";
            $result = sql_db::sql_query($query);                
            $row = sql_db::sql_fetch_assoc($result);
            return $row;                                    	            
        } 
                     
	function manInsertarUsuario($clienteId, $usuarioRut, $usuarioNombres, $usuarioApellidos, $usuarioEmail, $cargoNombre, $organizacionNombre, $sectorNombre, $areaNombre, $usuarioEstado, $usuarioModificacion){
            
            $usuarioNombres = str_replace("\'", "''", $usuarioNombres);
            $usuarioNombres = str_replace('\"', '"', $usuarioNombres);
            
            $usuarioApellidos = str_replace("\'", "''", $usuarioApellidos);
            $usuarioApellidos = str_replace('\"', '"', $usuarioApellidos);
            
            $organizacionNombre = str_replace("\'", "''", $organizacionNombre);
            $organizacionNombre = str_replace('\"', '"', $organizacionNombre);
            
            $sectorNombre = str_replace("\'", "''", $sectorNombre);
            $sectorNombre = str_replace('\"', '"', $sectorNombre);
            
            $areaNombre = str_replace("\'", "''", $areaNombre);
            $areaNombre = str_replace('\"', '"', $areaNombre);
            
            $usuarioEmail = str_replace("\'", "''", $usuarioEmail);
            $usuarioEmail = str_replace('\"', '"', $usuarioEmail);
            
            $cargoNombre = str_replace("\'", "''", $cargoNombre);
            $cargoNombre = str_replace('\"', '"', $cargoNombre);
            
            $query = "Exec manInsertarUsuario ".$clienteId.",'".$usuarioRut."','".$usuarioNombres."','".$usuarioApellidos."','".$usuarioEmail."','".$cargoNombre."','".$organizacionNombre."','".$sectorNombre."','".$areaNombre."',".$usuarioEstado.",'".$usuarioModificacion."'";
            $result = sql_db::sql_query($query);                
            $row = sql_db::sql_fetch_assoc($result);
            return $row;                                    
	}
             
	function manAnularUsuario($id){
            $query = "IF((Select usuarioEstado from Usuarios a (nolock) Where usuarioId = ".$id.") = 1)
                            BEGIN
                                    Update u set u.usuarioEstado = 0
                                    From Usuarios u
                                    Where usuarioId = ".$id."
                            END
                    ELSE
                            BEGIN
                                    Update u set u.usuarioEstado = 1
                                    From Usuarios u
                                    Where usuarioId = ".$id."
                            END	";        
            $result = sql_db::sql_query($query);  
            return $result;            
	}             
                                                                            
}
?>