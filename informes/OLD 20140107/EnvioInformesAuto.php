<?php
include ("../librerias/conexion.php");
include ("../librerias/config.php");
include ("../librerias/funciones_correo.php");
require('clases/informes.class.php');

date_default_timezone_set('Europe/London');
set_include_path('../Classes/');


include 'PHPExcel/IOFactory.php';

echo "Envio automatico<br>";

$objInforme = new informes();
$consulta = $objInforme->ListarPlanificaciones();

$queryMail 	= "EXEC CapXLSTextoMail";
$resultMail = $base_datos->sql_query($queryMail);
$rowMail	= $base_datos->sql_fetch_assoc($resultMail);
$subject=$rowMail['SUBJECT'] ? $rowMail['SUBJECT']:'';
$body=$rowMail['BODY'] ? $rowMail['BODY']:'';
$footer=$rowMail['FOOTER'] ? $rowMail['FOOTER']:'';
$mensajeCorreo=$body.$footer;

if ($consulta) {
   while ($row = $base_datos->sql_fetch_assoc($consulta)) {
       $administradorId=$row['administradorId'];
       $mm=$row['planificacionMes'];
       $yyyy=date("Y");
            //SECTOR "REMOLCADORES"
              
            echo "<br><br>" . "SECTOR REMOLCADORES" . "<br><br>";                        
       
            $sectorId=0;
            $sectorNombre="Remolcadores";
            $contactoEmail='';
            $consulta1 = $objInforme->ListarContactosPlanificaciones($administradorId,$sectorId);
            if ($consulta1) {
                
                $asunto = $subject;
               
                include("GenerarExcel_RAM.php");
                
                while ($row1 = $base_datos->sql_fetch_assoc($consulta1)) {
                    ECHO $row1['UsuarioNombre']."<BR>";
                    $contactoEmail=$contactoEmail.$row1['usuarioEmail'].",";
               }               
               
                $contactoEmail=substr($contactoEmail,0,strlen($contactoEmail)-1);
                $asunto=$asunto.' '.$sectorNombre;
                
                echo $contactoEmail . "<br>";
                
                $resultadoEnvio = enviarCorreoInformes($correoDe, $correoDeNombre, $contactoEmail, $asunto, $mensajeCorreo,'RepFlota_Remolcadores.xls');
                
                echo $resultadoEnvio . "<br>";;
                
            }
            

            //SECTOR "AEP"
            
            echo "<br><br>" . "SECTOR AEP" . "<br><br>";
            
            $sectorId=44;
            $sector='AEP';
            $sectorNombre="AEP";
            $contactoEmail='';
            $consulta2 = $objInforme->ListarContactosPlanificaciones($administradorId,$sectorId);
            if ($consulta2) {
                
                $asunto = $subject;
               
                include("GenerarExcel_AEP_ISM.php");
                 
                while ($row2 = $base_datos->sql_fetch_assoc($consulta2)) {
                    $contactoEmail=$contactoEmail.$row2['usuarioEmail'].",";
                }
                $contactoEmail=substr($contactoEmail,0,strlen($contactoEmail)-1);
                $asunto=$asunto.' '.$sectorNombre;
                
                echo $contactoEmail . "<br>";
                
                $resultadoEnvio = enviarCorreoInformes($correoDe, $correoDeNombre, $contactoEmail, $asunto, $mensajeCorreo,'RepEmpresa_AEP.xls');
                
                echo $resultadoEnvio . "<br>";;
            }
           
  
            //SECTOR "ISM"
            
            echo "<br><br>" . "SECTOR ISM" . "<br><br>";
            
            $sectorId=32;
            $sector='ISM';
            $sectorNombre="ISM VAP";
            $contactoEmail='';
            $consulta3 = $objInforme->ListarContactosPlanificaciones($administradorId,$sectorId);
            if ($consulta3) {
                
                $asunto = $subject;
                
                include("GenerarExcel_AEP_ISM.php");
                
                while ($row3 = $base_datos->sql_fetch_assoc($consulta3)) {
                   $contactoEmail=$contactoEmail.$row3['usuarioEmail'].",";
                }
                $contactoEmail=substr($contactoEmail,0,strlen($contactoEmail)-1);
                $asunto=$asunto.' '.$sectorNombre;
                
                echo $contactoEmail . "<br>";
                
                $resultadoEnvio = enviarCorreoInformes($correoDe, $correoDeNombre, $contactoEmail, $asunto, $mensajeCorreo,'RepEmpresa_ISM.xls');
                
                echo $resultadoEnvio . "<br>";;
            }
            

            echo "<br><br>" . "SECTOR OTROS" . "<br><br>";
            
            
            //LOS OTROS SECTORES:
            $consultaSect = $objInforme->ListarSectoresPlanificaciones($administradorId);
            if ($consultaSect) {
                while ($rowSect = $base_datos->sql_fetch_assoc($consultaSect)) {
                    $sector=$rowSect['SectorId'];
                    $sectorNombre=$rowSect['sectorNombre'];
                    $sectorId=$sector;
                    $consulta4 = $objInforme->ListarContactosPlanificaciones($administradorId,$sectorId);
                    if ($consulta4) {
                        
                        $asunto = $subject;
                       
                        include("GenerarExcelSector.php");
                        
                        $contactoEmail='';
                        
                        while ($row4 = $base_datos->sql_fetch_assoc($consulta4)) {
//                            ECHO $row4['UsuarioNombre'];
//                            ECHO "<BR>";
                            $contactoEmail=$contactoEmail.$row4['usuarioEmail'].",";
                        }
                        
                        $contactoEmail=substr($contactoEmail,0,strlen($contactoEmail)-1);
                        
                        $asunto=$asunto.' '.str_replace('_',' ',$sectorNombre);
                        
                        echo $contactoEmail . "<br>";
                        
                        $resultadoEnvio = enviarCorreoInformes($correoDe, $correoDeNombre, $contactoEmail, $asunto, $mensajeCorreo,'RepGerencia_'.$sectorNombre.'.xls');
                        
                        echo $resultadoEnvio . "<br>";;

                    }
                 
                }
            }
        
    }
}
?>
