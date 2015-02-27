<?php 

class clientes{
 //constructor	                    
               
    
        function manMostrarClientes($clienteNombres, $clienteApellidos){                                                      
            
            $clienteNombres = str_replace("\'", "''", $clienteNombres);
            $clienteNombres = str_replace('\"', '"', $clienteNombres);
            
            $clienteApellidos = str_replace("\'", "''", $clienteApellidos);
            $clienteApellidos = str_replace('\"', '"', $clienteApellidos);
                                    
            if($clienteNombres != "" && $clienteApellidos != ""){
                $query = "  Select *
                From Clientes c (nolock)
                Where c.clienteNombres = '".$clienteNombres."' 
                and   c.clienteApellidos = '".$clienteApellidos."'
                Order by clienteId desc";                                
            }                                                            
            
            if($clienteNombres == "" && $clienteApellidos == ""){
                $query = "  Select *
                From Clientes c (nolock)                
                Order by clienteId desc";                                
            }
            
            if($clienteNombres != "" && $clienteApellidos == ""){
                $query = "  Select *
                From Clientes c (nolock)
                Where c.clienteNombres = '".$clienteNombres."'                 
                Order by clienteId desc";                                
            }
            
            if($clienteNombres == "" && $clienteApellidos != ""){
                $query = "  Select *
                From Clientes c (nolock)
                Where c.clienteApellidos = '".$clienteApellidos."'
                Order by clienteId desc";                                
            }
            
            
            $result = sql_db::sql_query($query);                    
            return $result;
	}
        
        function manMostrarCliente($id){               
            $query = "  Select *
                        From Clientes c (nolock)
                        Where c.clienteId = ".$id;
            $result = sql_db::sql_query($query);                
            return $result;
	}        
        
        function manEditarCliente($clienteId, $clienteNombres, $clienteApellidos, $clienteEmail, $clienteDireccion, $clienteFono, $clienteMultimedia, $clienteEstado, $usuarioModificacion){                              
            
            $clienteNombres = str_replace("\'", "''", $clienteNombres);
            $clienteNombres = str_replace('\"', '"', $clienteNombres);
            
            $clienteApellidos = str_replace("\'", "''", $clienteApellidos);
            $clienteApellidos = str_replace('\"', '"', $clienteApellidos);
            
            $clienteEmail = str_replace("\'", "''", $clienteEmail);
            $clienteEmail = str_replace('\"', '"', $clienteEmail);
            
            $clienteDireccion = str_replace("\'", "''", $clienteDireccion);
            $clienteDireccion = str_replace('\"', '"', $clienteDireccion);
            
            $clienteFono = str_replace("\'", "''", $clienteFono);
            $clienteFono = str_replace('\"', '"', $clienteFono);                      
            
            $query = "Exec manEditarCliente ".$clienteId.",'".$clienteNombres."','".$clienteApellidos."','".$clienteEmail."','".$clienteDireccion."','".$clienteFono."','".$clienteMultimedia."',".$clienteEstado.",'".$usuarioModificacion."'";
            $result = sql_db::sql_query($query);                
            $row = sql_db::sql_fetch_assoc($result);
            return $row;                                    	            
        }                                                                       
              
	function manInsertarCliente($clienteNombres, $clienteApellidos, $clienteEmail, $clienteDireccion, $clienteFono, $clienteMultimedia, $clienteEstado, $usuarioModificacion){
            
            $clienteNombres = str_replace("\'", "''", $clienteNombres);
            $clienteNombres = str_replace('\"', '"', $clienteNombres);
            
            $clienteApellidos = str_replace("\'", "''", $clienteApellidos);
            $clienteApellidos = str_replace('\"', '"', $clienteApellidos);
            
            $clienteEmail = str_replace("\'", "''", $clienteEmail);
            $clienteEmail = str_replace('\"', '"', $clienteEmail);
            
            $clienteDireccion = str_replace("\'", "''", $clienteDireccion);
            $clienteDireccion = str_replace('\"', '"', $clienteDireccion);
            
            $clienteFono = str_replace("\'", "''", $clienteFono);
            $clienteFono = str_replace('\"', '"', $clienteFono);
            
            $query = "Exec manInsertarCliente '".$clienteNombres."','".$clienteApellidos."','".$clienteEmail."','".$clienteDireccion."','".$clienteFono."','".$clienteMultimedia."',".$clienteEstado.",'".$usuarioModificacion."'";
            $result = sql_db::sql_query($query);                
            $row = sql_db::sql_fetch_assoc($result);
            return $row;                                    
	}
	       
	function manAnularCliente($id){
            $query = "IF((Select clienteEstado from Clientes c (nolock) Where clienteId = ".$id.") = 1)
                            BEGIN
                                    Update c set c.clienteEstado = 0
                                    From Clientes c
                                    Where clienteId = ".$id."
                            END
                    ELSE
                            BEGIN
                                    Update c set c.clienteEstado = 1
                                    From Clientes c
                                    Where clienteId = ".$id."
                            END	";        
            $result = sql_db::sql_query($query);  
            return $result;            
	}                                                                
}
?>