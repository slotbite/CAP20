<?php 

class organizaciones{
 //constructor	                    
        
        function manBuscarCliente($clienteId) {
            $query = "      Select LTRIM(RTRIM(clienteNombres)) + ' ' + LTRIM(RTRIM(clienteApellidos)) as 'clienteNombreCompleto'
                            From Clientes c (nolock)                        
                            Where c.clienteId = " . $clienteId . "";
            $result = sql_db::sql_query($query);
            return $result;
        }
    
        function manMostrarOrganizaciones($clienteId, $organizacionNombre){                                                      
            
            $organizacionNombre = str_replace("\'", "''", $organizacionNombre);
            $organizacionNombre = str_replace('\"', '"', $organizacionNombre);
            
            $query = "SELECT * FROM Organizaciones o (nolock) Where o.clienteId = ".$clienteId."";                                
            
            if($organizacionNombre != ""){
                $query = $query . " and o.organizacionNombre = '".$organizacionNombre."'";
            }
            
            $query = $query . " ORDER BY organizacionId DESC";
            
            $result = sql_db::sql_query($query);                    
            return $result;
	}
        
        function manMostrarOrganizacion($id, $clienteId){               
            //$query = "SELECT * FROM Organizaciones o (nolock) left join PersonalizacionesMails p (nolock) on o.organizacionId = p.organizacionId and o.clienteId = p.clienteId Where o.organizacionId = ".$id." and o.clienteId = ".$clienteId;
            $query = "SELECT * FROM Organizaciones o (nolock) Where o.organizacionId = ".$id." and o.clienteId = ".$clienteId;
            $result = sql_db::sql_query($query);                
            return $result;
	}
        
        function manBuscarPersonalizacionOrganizacion($id, $clienteId){               
            $query = "SELECT * FROM PersonalizacionesMails (nolock) Where organizacionId = ".$id." and clienteId = ".$clienteId;
            $result = sql_db::sql_query($query);                
            return $result;
	}
            
        function manEditarOrganizaciones($clienteId, $organizacionId, $organizacionNombre, $organizacionLogo, $organizacionDescripcion, $organizacionEstado, $usuarioModificacion){                              
            
            $organizacionNombre = str_replace("\'", "''", $organizacionNombre);
            $organizacionNombre = str_replace('\"', '"', $organizacionNombre);
        
            $organizacionDescripcion = str_replace("\'", "''", $organizacionDescripcion);
            $organizacionDescripcion = str_replace('\"', '"', $organizacionDescripcion);                        
            
            $query = "Exec manEditarOrganizacion ".$clienteId.",".$organizacionId.",'".$organizacionNombre."','".$organizacionLogo."','".$organizacionDescripcion."',".$organizacionEstado.",'".$usuarioModificacion."'";
            $result = sql_db::sql_query($query);                
            $row = sql_db::sql_fetch_assoc($result);
            return $row;                                    
	}                                                                       
        
	function manInsertarOrganizaciones($clienteId, $organizacionNombre, $organizacionLogo, $organizacionDescripcion, $organizacionEstado, $usuarioModificacion){
            
            $organizacionNombre = str_replace("\'", "''", $organizacionNombre);
            $organizacionNombre = str_replace('\"', '"', $organizacionNombre);
        
            $organizacionDescripcion = str_replace("\'", "''", $organizacionDescripcion);
            $organizacionDescripcion = str_replace('\"', '"', $organizacionDescripcion);                                        
            
            $query = "Exec manInsertarOrganizacion ".$clienteId.",'".$organizacionNombre."','".$organizacionLogo."','".$organizacionDescripcion."',".$organizacionEstado.",'".$usuarioModificacion."'";            
            $result = sql_db::sql_query($query);                
            $row = sql_db::sql_fetch_assoc($result);
            return $row;                                    
	}
				
	function manEliminarOrganizacion($id){
            $query = "  IF((Select organizacionEstado from Organizaciones o (nolock) Where organizacionId = ".$id.") = 1)
                            BEGIN
                                    Update o set o.organizacionEstado = 0
                                    From Organizaciones o 
                                    Where organizacionId = ".$id."
                            END
                        ELSE
                            BEGIN
                                    Update o set o.organizacionEstado = 1
                                    From Organizaciones o 
                                    Where organizacionId = ".$id."
                            END	";        
            $result = sql_db::sql_query($query);  
            return $result;            
	}
        
        function manBusquedaCascada($id){
            $query = "  DECLARE @count_sectores int
                        DECLARE @count_areas int
                        DECLARE @count_usuarios int
                        
                        SET @count_sectores = (	Select count(*)
						From Sectores s (nolock)
						Where s.organizacionId = ".$id." 
                                                and s.sectorEstado = 1)

                        SET @count_areas = (    Select count(*)
                                                From Areas a (nolock)
                                                Where a.organizacionId = ".$id." 
                                                and a.areaEstado = 1)                       
                                                
                        SET @count_usuarios = (  Select  COUNT(*)
                                                 From Usuarios u (nolock)
                                                 Where u.organizacionId = ".$id." 
                                                 and u.usuarioEstado = 1)
                           
                        IF(@count_usuarios > 0)
                            BEGIN
                                Select 'No se puede anular esta organización, ya que cuenta con usuarios activos.' as 'estado'
                            END
                        ELSE
                            IF(@count_areas > 0)
                                BEGIN
                                    Select 'No se puede anular esta organización, ya que cuenta con áreas activas.' as 'estado'
                                END
                            ELSE	
                                BEGIN
                                    IF(@count_sectores > 0)
                                        BEGIN
                                                Select 'No se puede anular esta organización, ya que cuenta con gerencias/agencias activas.' as 'estado'			
                                        END
                                    ELSE
                                        BEGIN
                                                Select 'OK' as 'estado'
                                        END
                                END";                                              
            $result = sql_db::sql_query($query);  
            return $result;
        }
}
?>