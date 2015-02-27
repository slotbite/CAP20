<?PHP
session_start();
//include ("../librerias/conexion.php");
require('clases/informes.class.php');
$objInforme = new informes();

$clienteId = $_SESSION['clienteId'];
$administradorId=$_SESSION['administradorId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}

$sector_id = trim($_POST['sector_id']);

$lista = $objInforme->ListarFechasInforme($administradorId);
                        if ($lista) {

                            while ($listaFechas = mssql_fetch_array($lista)) {
                                $filas=$filas+1;
                                ?>
                                <tr>
                                    <td>
                                    <div style='width:46px;text-align:center'>
                                    <input id='' type='checkbox'/>
                                    <input type='hidden' id='idFila' value='<? echo $listaFechas['planificacionInformeId'] ?>'/>
                                    <input type='hidden' id='mesFila' value='<? echo $listaFechas['PlanificacionMes'] ?>'/></div>
                                    </td>
                                    <td>
                                        <div style='width:138px'><? echo $listaFechas['nombreMes'] ?></div>
                                    </td>
                                    <td>
                                        <div style='width:182px;text-align:center'>
                                        <input id='cal-<?echo $filas?>' type='text' style='width:70px; text-align:right' readonly='readonly' value='<? echo $listaFechas['planificacionFecha'] ?>'/>
                                        <button id='btn-<?echo $filas?>' class='btn invisible'><img src='../skins/saam/img/calendar.png'/></button></div>
                                        
                                          
                                        <script type="text/javascript">
                                            var myCal12 = Calendar.setup({
                                                inputField: 'cal-<? echo $filas ?>',
                                                trigger: 'btn-<? echo $filas ?>',
                                                onSelect: function () {
                                                    this.hide();
                                                },
                                                showTime: 12,
                                                dateFormat: "%d-%m-%Y"
                                            });
                                            myCal12.setLanguage('es'); 
                                        </script> 
                                        
                                    </td>
                                </tr>    
                               
                                
                                <?php
                            }
                        }
                        ?>
