<?php 

class areas{
 //constructor	                    
        
        function manBuscarCliente($clienteId) {
            $query = "      Select LTRIM(RTRIM(clienteNombres)) + ' ' + LTRIM(RTRIM(clienteApellidos)) as 'clienteNombreCompleto'
                            From Clientes c (nolock)                        
                            Where c.clienteId = " . $clienteId . "";
            $result = sql_db::sql_query($query);
            return $result;
        }
    
        function manMostrarAreas($clienteId, $organizacionNombre, $sectorNombre, $areaNombre){                                                      
                       
            $organizacionNombre = str_replace("\'", "''", $organizacionNombre);
            $organizacionNombre = str_replace('\"', '"', $organizacionNombre);
            
            $sectorNombre = str_replace("\'", "''", $sectorNombre);
            $sectorNombre = str_replace('\"', '"', $sectorNombre);                        
            
            $areaNombre = str_replace("\'", "''", $areaNombre);
            $areaNombre = str_replace('\"', '"', $areaNombre);                                   
            
            $query = "  Select a.*, o.organizacionNombre, s.sectorNombre
                        From Areas a (nolock)
                        inner join Sectores s (nolock) on a.sectorId = s.sectorId and a.organizacionId = s.organizacionId 
                        inner join Organizaciones o (nolock) on a.organizacionId = o.organizacionId 
                        Where a.clienteId = ".$clienteId."";                                
            
            if($organizacionNombre != ""){
               $query = $query . " and o.organizacionNombre = '".$organizacionNombre."'";
            }
            
            if($sectorNombre != ""){
               $query = $query . " and s.sectorNombre = '".$sectorNombre."'";
            }
            
            if($areaNombre != ""){
               $query = $query . " and a.areaNombre = '".$areaNombre."'";
            }
            
            $query = $query . " ORDER BY areaId DESC";
            
            
            $result = sql_db::sql_query($query);                    
            return $result;
	}
        
        function manMostrarArea($id){               
            $query = "  Select a.*, o.organizacionNombre, s.sectorNombre
                        From Areas a (nolock)
                        inner join Sectores s (nolock) on a.sectorId = s.sectorId and a.organizacionId = s.organizacionId 
                        inner join Organizaciones o (nolock) on a.organizacionId = o.organizacionId 
                        Where a.areaId = ".$id;
            $result = sql_db::sql_query($query);                
            return $result;
	}
        
        
        function manEditarArea($clienteId, $areaId, $organizacionNombre, $sectorNombre, $areaNombre, $areaDescripcion, $areaEstado, $usuarioModificacion){                              
            
            $organizacionNombre = str_replace("\'", "''", $organizacionNombre);
            $organizacionNombre = str_replace('\"', '"', $organizacionNombre);
            
            $sectorNombre = str_replace("\'", "''", $sectorNombre);
            $sectorNombre = str_replace('\"', '"', $sectorNombre);                        
            
            $areaNombre = str_replace("\'", "''", $areaNombre);
            $areaNombre = str_replace('\"', '"', $areaNombre);
            
            $areaDescripcion = str_replace("\'", "''", $areaDescripcion);
            $areaDescripcion = str_replace('\"', '"', $areaDescripcion);
            
            $query = "Exec manEditarArea ".$clienteId.",".$areaId.",'".$organizacionNombre."','".$sectorNombre."','".$areaNombre."','".$areaDescripcion."',".$areaEstado.",'".$usuarioModificacion."'";
            $result = sql_db::sql_query($query);                
            $row = sql_db::sql_fetch_assoc($result);
            return $row;                                    	            
        }                                                                       
        
        
	function manInsertarArea($clienteId, $organizacionNombre, $sectorNombre, $areaNombre, $areaDescripcion, $areaEstado, $usuarioModificacion){
            
            $organizacionNombre = str_replace("\'", "''", $organizacionNombre);
            $organizacionNombre = str_replace('\"', '"', $organizacionNombre);
            
            $sectorNombre = str_replace("\'", "''", $sectorNombre);
            $sectorNombre = str_replace('\"', '"', $sectorNombre);                        
            
            $areaNombre = str_replace("\'", "''", $areaNombre);
            $areaNombre = str_replace('\"', '"', $areaNombre);
            
            $areaDescripcion = str_replace("\'", "''", $areaDescripcion);
            $areaDescripcion = str_replace('\"', '"', $areaDescripcion);            
            
            $query = "Exec manInsertarArea ".$clienteId.",'".$organizacionNombre."','".$sectorNombre."','".$areaNombre."','".$areaDescripcion."',".$areaEstado.",'".$usuarioModificacion."'";
            $result = sql_db::sql_query($query);                
            $row = sql_db::sql_fetch_assoc($result);
            return $row;                                    
	}
	
        
	function manAnularArea($id){
            $query = "IF((Select areaEstado from Areas a (nolock) Where areaId = ".$id.") = 1)
                            BEGIN
                                    Update a set a.areaEstado = 0
                                    From Areas a 
                                    Where areaId = ".$id."
                            END
                    ELSE
                            BEGIN
                                    Update a set a.areaEstado = 1
                                    From Areas a 
                                    Where areaId = ".$id."
                            END	";        
            $result = sql_db::sql_query($query);  
            return $result;            
	}  
        
        function manBusquedaCascada($id){
            $query = "  DECLARE @count_usuarios int                                                
                                                                                         
                        SET @count_usuarios = (  Select  COUNT(*)
                                                 From Usuarios u (nolock)
                                                 Where u.areaId = ".$id." 
                                                 and u.usuarioEstado = 1)
                           
                        IF(@count_usuarios > 0)
                            BEGIN
                                Select 'No se puede anular esta área, ya que cuenta con usuarios activos.' as 'estado'
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