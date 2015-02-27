<?php 

class cargos{
 //constructor	                    
        
        function manBuscarCliente($clienteId) {
            $query = "      Select LTRIM(RTRIM(clienteNombres)) + ' ' + LTRIM(RTRIM(clienteApellidos)) as 'clienteNombreCompleto'
                            From Clientes c (nolock)                        
                            Where c.clienteId = " . $clienteId . "";
            $result = sql_db::sql_query($query);
            return $result;
        }
    
        function manMostrarCargos($clienteId, $cargoNombre){                                                      
            
            $cargoNombre = str_replace("\'", "''", $cargoNombre);
            $cargoNombre = str_replace('\"', '"', $cargoNombre);
            
            $query = "  Select *
                        From Cargos c (nolock)
                        Where c.clienteId = ".$clienteId."";                                
            
            if($cargoNombre != ""){
                $query = $query . " and c.cargoNombre = '".$cargoNombre."'";
            }
            
            $query = $query . " Order by c.cargoId Desc";
            
            $result = sql_db::sql_query($query);                    
            return $result;
	}
        
        function manMostrarCargo($id){               
            $query = "  Select *
                        From Cargos c (nolock)
                        Where c.cargoId = ".$id;
            $result = sql_db::sql_query($query);                
            return $result;
	}        
        
        function manEditarCargo($clienteId, $cargoId, $cargoNombre, $cargoDescripcion, $cargoEstado, $usuarioModificacion){ 
            
            $cargoNombre = str_replace("\'", "''", $cargoNombre);
            $cargoNombre = str_replace('\"', '"', $cargoNombre);
            
            $cargoDescripcion = str_replace("\'", "''", $cargoDescripcion);
            $cargoDescripcion = str_replace('\"', '"', $cargoDescripcion);
            
            $query = "Exec manEditarCargo ".$clienteId.",".$cargoId.",'".$cargoNombre."','".$cargoDescripcion."',".$cargoEstado.",'".$usuarioModificacion."'";
            $result = sql_db::sql_query($query);                
            $row = sql_db::sql_fetch_assoc($result);
            return $row;                                    	            
        }                                                                       
                
	function manInsertarCargo($clienteId, $cargoNombre, $cargoDescripcion, $cargoEstado, $usuarioModificacion){
            
            $cargoNombre = str_replace("\'", "''", $cargoNombre);
            $cargoNombre = str_replace('\"', '"', $cargoNombre);
            
            $cargoDescripcion = str_replace("\'", "''", $cargoDescripcion);
            $cargoDescripcion = str_replace('\"', '"', $cargoDescripcion);
            
            $query = "Exec manInsertarCargo ".$clienteId.",'".$cargoNombre."','".$cargoDescripcion."',".$cargoEstado.",'".$usuarioModificacion."'";
            $result = sql_db::sql_query($query);                
            $row = sql_db::sql_fetch_assoc($result);
            return $row;                                    
	}
	       
	function manAnularCargo($id){
            $query = "IF((Select cargoEstado from Cargos c (nolock) Where cargoId = ".$id.") = 1)
                            BEGIN
                                    Update c set c.cargoEstado = 0
                                    From Cargos c
                                    Where cargoId = ".$id."
                            END
                    ELSE
                            BEGIN
                                    Update c set c.cargoEstado = 1
                                    From Cargos c
                                    Where cargoId = ".$id."
                            END	";        
            $result = sql_db::sql_query($query);  
            return $result;            
	}                                               
}
?>