<?
session_start();
include("default.php");
$usuarioLog = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';

setlocale(LC_TIME, "spanish");
$fechaActual = htmlentities(strftime("%A %d de %B del %Y"));
$fechaActual = ucfirst($fechaActual);

if (($_POST["name"] != "") && ($_POST["pass1"] != "")) {

    //$_SESSION['usuario']=$_POST["name"];
    $usuario_logueado = $_POST["name"];
    $password_logueado = $_POST["pass1"];

    $query = "EXEC admLogin '" . $usuario_logueado . "','" . $password_logueado . "'";
    $result = $base_datos->sql_query($query);

    $row = $base_datos->sql_fetch_assoc($result);

    $id = $row["clienteId"] ? $row["clienteId"] : 0;
    $administradorId = $row["administradorId"] ? $row["administradorId"] : 0;
    $perfil_id = $row["perfilId"] ? $row["perfilId"] : 0;

    if ($id != 0) {
        $_SESSION['usuario'] = $usuario_logueado;
        $_SESSION['clienteId'] = $id;
        $_SESSION['administradorId'] = $administradorId;
        $_SESSION['perfilId'] = $perfil_id;
    } else {
        $error = "<span style='color:red'>Usuario/Password Incorrecto</b></span>";
    }
}

$usuario_id = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';

$link = "";

if($usuario_id == ''){
    $link = "style='display:none'";    
}
else{
    $link = "style='display:'";    
}

$plantilla->setTemplate("header");
$plantilla->setVars(array("USUARIO" => " $usuario_id ",
    "FECHA" => "$fechaActual",
    "DISPLAY" => "$link"
));
echo $plantilla->show();

if ($usuario_id == '') {
    $plantilla->setTemplate("login");
    $plantilla->setVars(array("ERROR" => "$error"));
    echo $plantilla->show();
} else {

    if ($_SESSION['perfilId'] == 1) {
        $menu1 = "display:block;";
    } else {
        $menu1 = "display:none;";
    }

    $plantilla->setTemplate("menu");
    $fecha = date("d-m-Y");
    $plantilla->setVars(array("MANT" => "$menu1"
    ));


    echo $plantilla->show();
}
$plantilla->setTemplate("footer");
echo $plantilla->show();
?>