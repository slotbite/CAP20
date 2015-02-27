<?php

class informes {

    //constructor	                    

    function BuscarSectoresInforme() {
        $query = "Select * from IndSectoresInformes Order by sectorNombre";
        $result = sql_db::sql_query($query);
        return $result;
    }
    
     function validarSectoresInforme($nombre) {
        $query = "Select * from IndSectoresInformes where sectorNombre='$nombre'";
        $result = sql_db::sql_query($query);
        return $result;
    }
    
     function MostrarUsuariosContactosSector($sectorId,$administradorId){
        $query = "  Select u.usuarioId, LTRIM(RTRIM(u.usuarioNombres)) + ' ' + LTRIM(RTRIM(u.usuarioApellidos)) as 'nombre', u.usuarioEmail, o.organizacionNombre, s.sectorNombre, a.areaNombre, c.cargoNombre, u.usuarioRut
                    From UsuariosContactos up (nolock)
                    inner join Usuarios u (nolock) on up.usuarioId = u.usuarioId
                    left join Organizaciones o (nolock) on u.organizacionId = o.organizacionId
                    left join Sectores s (nolock) on u.sectorId = s.sectorId and s.organizacionId = o.organizacionId
                    left join Areas a (nolock) on u.areaId = a.areaId and a.sectorId = s.sectorId and a.organizacionId = o.organizacionId
                    left join Cargos c (nolock) on u.cargoId = c.cargoId                     
                    Where up.sectorId = " . $sectorId ." and up.administradorId=".$administradorId."
                    Order by u.usuarioApellidos asc";
        $result = sql_db::sql_query($query);
        return $result;
    }    
    
    function limpiarSectoresInforme($sectorId, $administradorId){
        $query = "EXEC UsuariosContactosLimpiar $sectorId,$administradorId";
        $result = sql_db::sql_query($query);
    }
    
    function guardarSectoresInforme($sectorId,$personalId,$administradorId){
        $query = "EXEC UsuariosContactosInsertar $personalId,$sectorId,$administradorId";
        $result = sql_db::sql_query($query);
    }
    
    function guardarFechasInforme($planificacionInformeId,$planificacionMes,$planificacionFecha,$administradorId){
        $query = "EXEC MantPlanificacionInformes $planificacionInformeId,$planificacionMes,'$planificacionFecha',$administradorId";
        $result = sql_db::sql_query($query);
    }
    
     function ListarFechasInforme($administradorId){
        $query = "EXEC mantInformesListarFechas $administradorId";
        $result = sql_db::sql_query($query);
        return $result;
    }
    
    function LimpiarFechasInforme($administradorId,$lista){
        $query = "delete from PlanificacionesInformes where administradorId=$administradorId and planificacionInformeId in($lista)";
        $result = sql_db::sql_query($query);
    }
    
    function ListarPlanificaciones(){
       $query = "SELECT * FROM PlanificacionesInformes where convert(varchar,planificacionFecha,112)=convert(varchar,GETDATE(),112) and planificacionEstadoEnvio=0";
        $result = sql_db::sql_query($query);
        return $result; 
    }
    
    function ListarContactosPlanificaciones($administradorId,$sectorId){
        $query = "SELECT U.usuarioNombres+' '+U.usuarioApellidos AS UsuarioNombre 
        ,u.usuarioEmail FROM UsuariosContactos UC 
        left join Usuarios U on UC.usuarioId=U.usuarioId 
        where UC.administradorId=$administradorId  
        and UC.SectorId=$sectorId AND U.usuarioEstado=1";
        //ECHO $query;
        $result = sql_db::sql_query($query);
        return $result; 
    }
    
     function ListarSectoresPlanificaciones($administradorId){
       $query = "SELECT DISTINCT UC.SectorId,replace(VS.sectorNombre,' ','_') as sectorNombre FROM UsuariosContactos UC 
                 LEFT JOIN IndSectoresInformes VS ON UC.SectorId=VS.sectorId 
                 where administradorId=$administradorId AND UC.SectorId NOT IN(44,32,0,40)";
        $result = sql_db::sql_query($query);
        return $result; 
    }
}

?>
