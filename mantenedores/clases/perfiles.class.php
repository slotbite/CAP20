<?php 

class perfiles{
 //constructor	                    
        
        function manBuscarCliente($clienteId) {
            $query = "      Select LTRIM(RTRIM(clienteNombres)) + ' ' + LTRIM(RTRIM(clienteApellidos)) as 'clienteNombreCompleto'
                            From Clientes c (nolock)                        
                            Where c.clienteId = " . $clienteId . "";
            $result = sql_db::sql_query($query);
            return $result;
        }
    
        function manMostrarPerfiles($clienteId, $perfilNombre){                                                      
            
            $perfilNombre = str_replace("\'", "''", $perfilNombre);
            $perfilNombre = str_replace('\"', '"', $perfilNombre);
            
            if($perfilNombre != ""){            
                $query = "  Select *
                            from Perfiles p (nolock)
                            Where p.perfilNombre = '".$perfilNombre."'
                            --and p.clienteId = ".$clienteId."
                            ORDER BY perfilId DESC";
                
            }
            else{
                $query = "  Select *
                            from Perfiles p (nolock)                                                        
                            ORDER BY perfilId DESC";
            }                                   
            
            $result = sql_db::sql_query($query);            
            return $result;
	}
        
        function manMostrarPerfil($id){               
            $query = "  Select *
                        From Perfiles p (nolock)                        
                        Where p.perfilId = ".$id;
            $result = sql_db::sql_query($query);                
            return $result;
	}
            
        function manEditarPerfil($clienteId, $perfilId, $perfilNombre, $perfilDescripcion, $perfilEstado, $usuarioModificacion){                              
            
            $perfilNombre = str_replace("\'", "''", $perfilNombre);
            $perfilNombre = str_replace('\"', '"', $perfilNombre);
                    
            $perfilDescripcion = str_replace("\'", "''", $perfilDescripcion);
            $perfilDescripcion = str_replace('\"', '"', $perfilDescripcion);
            
            $query = "Exec manEditarPerfil ".$perfilId.",'".$perfilNombre."','".$perfilDescripcion."',".$perfilEstado.",'".$usuarioModificacion."'";
            $result = sql_db::sql_query($query);                
            $row = sql_db::sql_fetch_assoc($result);
            return $row;                                    	            
        }                                                                       
        
	function manInsertarPerfil($clienteId, $perfilNombre, $perfilDescripcion, $perfilEstado, $usuarioModificacion){
            
            $perfilNombre = str_replace("\'", "''", $perfilNombre);
            $perfilNombre = str_replace('\"', '"', $perfilNombre);
                    
            $perfilDescripcion = str_replace("\'", "''", $perfilDescripcion);
            $perfilDescripcion = str_replace('\"', '"', $perfilDescripcion);
            
            $query = "Exec manInsertarPerfil '".$perfilNombre."','".$perfilDescripcion."',".$perfilEstado.",'".$usuarioModificacion."'";
            $result = sql_db::sql_query($query);                
            $row = sql_db::sql_fetch_assoc($result);
            return $row;                                    
	}
	        
	function manAnularPerfil($id){
            $query = "IF((Select perfilEstado from Perfiles p (nolock) Where perfilId = ".$id.") = 1)
                            BEGIN
                                    Update p set p.perfilEstado = 0
                                    From Perfiles p
                                    Where perfilId = ".$id."
                            END
                    ELSE
                            BEGIN
                                    Update p set p.perfilEstado = 1
                                    From Perfiles p 
                                    Where perfilId = ".$id."
                            END	";        
            $result = sql_db::sql_query($query);  
            return $result;            
	}                                 
}
?>