<?php
session_start();

include("../default.php");
require('clases/perfiles.class.php');

$perfilId = $_GET['perfilId'];
$objPerfiles = new perfiles();

if ($objPerfiles->manAnularPerfil($perfilId) == true) {
    echo "";
} else {
    echo "Se ha producido un error. Por favor, inténtelo más tarde.";
}
?>