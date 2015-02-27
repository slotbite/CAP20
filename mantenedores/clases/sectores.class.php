<?php 

class sectores{
 //constructor	                    
        
        function manBuscarCliente($clienteId) {
            $query = "      Select LTRIM(RTRIM(clienteNombres)) + ' ' + LTRIM(RTRIM(clienteApellidos)) as 'clienteNombreCompleto'
                            From Clientes c (nolock)                        
                            Where c.clienteId = " . $clienteId . "";
            $result = sql_db::sql_query($query);
            return $result;
        }
    
        function manMostrarSectores($clienteId, $organizacionNombre, $sectorNombre){                                                      
            
            $organizacionNombre = str_replace("\'", "''", $organizacionNombre);
            $organizacionNombre = str_replace('\"', '"', $organizacionNombre);
            
            $sectorNombre = str_replace("\'", "''", $sectorNombre);
            $sectorNombre = str_replace('\"', '"', $sectorNombre);                                   
            
            $query = "  Select s.*, o.organizacionId, o.organizacionNombre
                        From Sectores s (nolock)
                        inner join Organizaciones o (nolock) on s.organizacionId = o.organizacionId 
                        Where s.clienteId = ".$clienteId."";
            
            if($organizacionNombre != ""){
                $query = $query . " and o.organizacionNombre = '".$organizacionNombre."'";
                
            }
            
            if($sectorNombre != ""){
                $query = $query . " and s.sectorNombre = '".$sectorNombre."'";
                
            }
            
            $query = $query . " ORDER BY sectorId DESC";
                        
            $result = sql_db::sql_query($query);                    
            return $result;
	}
        
        function manMostrarSector($id){               
            $query = "  Select s.*, o.organizacionNombre
                        From Sectores s (nolock)
                        inner join Organizaciones o (nolock) on s.organizacionId = o.organizacionId 
                        Where s.sectorId = ".$id;
            $result = sql_db::sql_query($query);                
            return $result;
	}
            
        function manEditarSectores($clienteId, $sectorId, $organizacionNombre, $sectorNombre, $sectorDescripcion, $sectorEstado, $usuarioModificacion){                              
            
            $organizacionNombre = str_replace("\'", "''", $organizacionNombre);
            $organizacionNombre = str_replace('\"', '"', $organizacionNombre);
            
            $sectorNombre = str_replace("\'", "''", $sectorNombre);
            $sectorNombre = str_replace('\"', '"', $sectorNombre);
            
            $sectorDescripcion = str_replace("\'", "''", $sectorDescripcion);
            $sectorDescripcion = str_replace('\"', '"', $sectorDescripcion);
            
            
            $query = "Exec manEditarSector ".$clienteId.",".$sectorId.",'".$organizacionNombre."','".$sectorNombre."','".$sectorDescripcion."',".$sectorEstado.",'".$usuarioModificacion."'";
            $result = sql_db::sql_query($query);                
            $row = sql_db::sql_fetch_assoc($result);
            return $row;                                    	            
        }                                                                       
        
	function manInsertarSectores($clienteId, $organizacionNombre, $sectorNombre, $sectorDescripcion, $sectorEstado, $usuarioModificacion){
            
            $organizacionNombre = str_replace("\'", "''", $organizacionNombre);
            $organizacionNombre = str_replace('\"', '"', $organizacionNombre);
            
            $sectorNombre = str_replace("\'", "''", $sectorNombre);
            $sectorNombre = str_replace('\"', '"', $sectorNombre);
            
            $sectorDescripcion = str_replace("\'", "''", $sectorDescripcion);
            $sectorDescripcion = str_replace('\"', '"', $sectorDescripcion);
            
            $query = "Exec manInsertarSector ".$clienteId.",'".$organizacionNombre."','".$sectorNombre."','".$sectorDescripcion."',".$sectorEstado.",'".$usuarioModificacion."'";
            $result = sql_db::sql_query($query);                
            $row = sql_db::sql_fetch_assoc($result);
            return $row;                                    
	}
				
	function manAnularSector($id){
            $query = "IF((Select sectorEstado from Sectores s (nolock) Where sectorId = ".$id.") = 1)
                            BEGIN
                                    Update s set s.sectorEstado = 0
                                    From Sectores s 
                                    Where sectorId = ".$id."
                            END
                    ELSE
                            BEGIN
                                    Update s set s.sectorEstado = 1
                                    From Sectores s 
                                    Where sectorId = ".$id."
                            END	";        
            $result = sql_db::sql_query($query);  
            return $result;            
	}  
        
        function manBusquedaCascada($id){
            $query = "  DECLARE @count_areas int
                        DECLARE @count_usuarios int                                                

                        SET @count_areas = (    Select count(*)
                                                From Areas a (nolock)
                                                Where a.sectorId = ".$id." 
                                                and a.areaEstado = 1)                       
                                                
                        SET @count_usuarios = (  Select  COUNT(*)
                                                 From Usuarios u (nolock)
                                                 Where u.sectorId = ".$id." 
                                                 and u.usuarioEstado = 1)
                           
                        IF(@count_usuarios > 0)
                            BEGIN
                                Select 'No se puede anular esta gerencia/agencia, ya que cuenta con usuarios activos.' as 'estado'
                            END
                        ELSE
                            IF(@count_areas > 0)
                                BEGIN
                                    Select 'No se puede anular esta gerencia/agencia, ya que cuenta con áreas activas.' as 'estado'
                                END
                            ELSE	
                                BEGIN
                                    Select 'OK' as 'estado'
                                END";                                              
            $result = sql_db::sql_query($query);  
            return $result;
        }
}
?>