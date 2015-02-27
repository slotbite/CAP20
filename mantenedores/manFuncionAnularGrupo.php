<?php

session_start();

include("../default.php");
require('clases/grupos.class.php');
$objGrupo = new grupos();
$grupoId = $_GET['grupoId'];
if ($objGrupo->manAnularGrupo($grupoId) == true) {
    echo "";
} else {
    echo "Se ha producido un error. Por favor, inténtelo más tarde.";
}
?>