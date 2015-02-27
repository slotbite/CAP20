<?PHP
session_start();
include ("../librerias/conexion.php");
require('clases/informes.class.php');
$objInforme = new informes();

$clienteId = $_SESSION['clienteId'];
$administradorId=$_SESSION['administradorId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}

$sector_id = trim($_POST['sector_id']);

$listaUsuarios = $objInforme->MostrarUsuariosContactosSector($sector_id,$administradorId);
                        if ($listaUsuarios) {

                            while ($listaUsuario = mssql_fetch_array($listaUsuarios)) {
                                ?>
                                <tr id="fila-<?php echo $listaUsuario['usuarioId'] ?>">
                                    <td style='width:33px;' align="center"><input type='checkbox'/><input type='hidden' id='usuarioId' value='<?php echo htmlentities($listaUsuario['usuarioId']) ?>'/></td>
                                    <td style='width:147px;'><?php echo htmlentities($listaUsuario['nombre']) ?></td>                                                                                          
                                    <td style='width:147px;'><?php echo htmlentities($listaUsuario['usuarioEmail']) ?></td>
                                    <td style='width:147px;'><?php echo htmlentities($listaUsuario['organizacionNombre']) ?></td>
                                    <td style='width:147px;'><?php echo htmlentities($listaUsuario['sectorNombre']) ?></td>
                                    <td style='width:147px;'><?php echo htmlentities($listaUsuario['areaNombre']) ?></td>
                                    <td style='width:147px;'><?php echo htmlentities($listaUsuario['cargoNombre']) ?></td>                                                                                  
                                </tr>

                                <?php
                            }
                        }
                        ?>
