<?php 

class administradores{
 //constructor	                    
        
        function manBuscarCliente($clienteId) {
            $query = "      Select LTRIM(RTRIM(clienteNombres)) + ' ' + LTRIM(RTRIM(clienteApellidos)) as 'clienteNombreCompleto'
                            From Clientes c (nolock)                        
                            Where c.clienteId = " . $clienteId . "";
            $result = sql_db::sql_query($query);
            return $result;
        }
    
        function manMostrarAdministradores($clienteId, $administradorNombres, $administradorApellidos){                                                      
            
            $administradorNombres = str_replace("\'", "''", $administradorNombres);
            $administradorNombres = str_replace('\"', '"', $administradorNombres);
            
            $administradorApellidos = str_replace("\'", "''", $administradorApellidos);
            $administradorApellidos = str_replace('\"', '"', $administradorApellidos);
            
            $query = "  Select a.*, p.perfilNombre
                        From Administradores a (nolock)
                        inner join Perfiles p (nolock) on a.perfilId = p.perfilId
                        Where	a.clienteId = ".$clienteId."";                                
            
            if($administradorNombres != ""){
                $query = $query . " and a.administradorNombres = '".$administradorNombres."'";
            }
            
            if($administradorApellidos != ""){
                $query = $query . " and a.administradorApellidos = '".$administradorApellidos."'";
            }
            
            $query = $query . " Order by a.administradorId desc";
            
            $result = sql_db::sql_query($query);                    
            return $result;
	}
        
         
        function manMostrarAdministrador($id){               
            $query = "  Select a.*, p.perfilNombre
                        From Administradores a (nolock)
                        inner join Perfiles p (nolock) on a.perfilId = p.perfilId
                        Where a.administradorId = ".$id;
            $result = sql_db::sql_query($query);                
            return $result;
	}        
        
        function manEditarAdministrador($clienteId, $administradorId, $administradorNombres, $administradorApellidos, $administradorEmail, $perfilNombre, $login, $password, $administradorEstado, $usuarioModificacion){                              
            
            $administradorNombres = str_replace("\'", "''", $administradorNombres);
            $administradorNombres = str_replace('\"', '"', $administradorNombres);
            
            $administradorApellidos = str_replace("\'", "''", $administradorApellidos);
            $administradorApellidos = str_replace('\"', '"', $administradorApellidos);                        
            
            $administradorEmail = str_replace("\'", "''", $administradorEmail);
            $administradorEmail = str_replace('\"', '"', $administradorEmail);
            
            $perfilNombre = str_replace("\'", "''", $perfilNombre);
            $perfilNombre = str_replace('\"', '"', $perfilNombre);
            
            $login = str_replace("\'", "''", $login);
            $login = str_replace('\"', '"', $login);
            
            $password = str_replace("\'", "''", $password);
            $password = str_replace('\"', '"', $password);                        
            
            $query = "Exec manEditarAdministrador ".$clienteId.",".$administradorId.",'".$administradorNombres."','".$administradorApellidos."','".$administradorEmail."','".$perfilNombre."','".$login."','".$password."',".$administradorEstado.",'".$usuarioModificacion."'";
            $result = sql_db::sql_query($query);                
            $row = sql_db::sql_fetch_assoc($result);
            return $row;                                    	            
        }                                                                       
             
	function manInsertarAdministrador($clienteId, $administradorNombres, $administradorApellidos, $administradorEmail, $perfilNombre, $login, $password, $administradorEstado, $usuarioModificacion){
            
            $administradorNombres = str_replace("\'", "''", $administradorNombres);
            $administradorNombres = str_replace('\"', '"', $administradorNombres);
            
            $administradorApellidos = str_replace("\'", "''", $administradorApellidos);
            $administradorApellidos = str_replace('\"', '"', $administradorApellidos);                        
            
            $administradorEmail = str_replace("\'", "''", $administradorEmail);
            $administradorEmail = str_replace('\"', '"', $administradorEmail);
            
            $perfilNombre = str_replace("\'", "''", $perfilNombre);
            $perfilNombre = str_replace('\"', '"', $perfilNombre);
            
            $login = str_replace("\'", "''", $login);
            $login = str_replace('\"', '"', $login);
            
            $password = str_replace("\'", "''", $password);
            $password = str_replace('\"', '"', $password);             
            
            $query = "Exec manInsertarAdministrador ".$clienteId.",'".$administradorNombres."','".$administradorApellidos."','".$administradorEmail."','".$perfilNombre."','".$login."','".$password."',".$administradorEstado.",'".$usuarioModificacion."'";
            $result = sql_db::sql_query($query);                
            $row = sql_db::sql_fetch_assoc($result);
            return $row;                                    
	}
             
	function manAnularAdministrador($id){
            $query = "IF((Select administradorEstado from Administradores a (nolock) Where administradorId = ".$id.") = 1)
                            BEGIN
                                    Update a set a.administradorEstado = 0
                                    From Administradores a
                                    Where administradorId = ".$id."
                            END
                    ELSE
                            BEGIN
                                    Update a set a.administradorEstado = 1
                                    From Administradores a
                                    Where administradorId = ".$id."
                            END	";        
            $result = sql_db::sql_query($query);  
            return $result;            
	}             
                                                                            
}
?>