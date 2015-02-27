<?php
session_start();

include("../default.php");

setlocale(LC_TIME, "spanish");
$fechaActual = htmlentities(strftime("%A %d de %B del %Y"));
$fechaActual = ucfirst($fechaActual);

$nusuario = $_SESSION['usuario'];
$plantilla->setPath('../skins/saam/plantillas/');
$plantilla->setTemplate("header_2");
$plantilla->setVars(array("USUARIO" => " $nusuario ",
    "FECHA" => "$fechaActual"));
echo $plantilla->show();

$clienteId = $_SESSION['clienteId'];
$usuarioModificacion = $_SESSION['usuario'];

if ($clienteId == '') {
    echo "<script>window.location='../';</script>";
}

require('clases/administradores.class.php');
$objAdministrador = new administradores();

$consulta = $objAdministrador->manBuscarCliente($clienteId);
$cliente = mssql_fetch_array($consulta);
$clienteNombre = $cliente['clienteNombreCompleto'];

if (isset($_POST['submit'])) {

    $administradorNombres = mb_convert_encoding(trim($_POST['inAdmAdministradorNombres']), "ISO-8859-1", "UTF-8");
    $administradorApellidos = mb_convert_encoding(trim($_POST['inAdmAdministradorApellidos']), "ISO-8859-1", "UTF-8");
    $administradorEmail = mb_convert_encoding(trim($_POST['inAdmAdministradorEmail']), "ISO-8859-1", "UTF-8");
    //$perfilNombre = mb_convert_encoding(trim($_POST['inAdmAdministradorPerfil']), "ISO-8859-1", "UTF-8");
    $perfilNombre = "ADMINISTRADOR";
    $administradorLogin = mb_convert_encoding(trim($_POST['inAdmAdministradorLogin']), "ISO-8859-1", "UTF-8");
    $administradorPass = mb_convert_encoding(trim($_POST['inAdmAdministradorPass']), "ISO-8859-1", "UTF-8");
    //$login = split('@', $administradorEmail);
    //$password = texto_aleatorio();

    $administradorEstado = "1";

    //$resultado = $objAdministrador->manInsertarAdministrador($clienteId, $administradorNombres, $administradorApellidos, $administradorEmail, $perfilNombre, $login[0], $password, $administradorEstado, $usuarioModificacion);
    $resultado = $objAdministrador->manInsertarAdministrador($clienteId, $administradorNombres, $administradorApellidos, $administradorEmail, $perfilNombre, $administradorLogin, $administradorPass, $administradorEstado, $usuarioModificacion);

    $administradorNombres = str_replace("\'", "'", $administradorNombres);
    $administradorNombres = str_replace('\"', '"', $administradorNombres);

    $administradorApellidos = str_replace("\'", "'", $administradorApellidos);
    $administradorApellidos = str_replace('\"', '"', $administradorApellidos);

    $administradorEmail = str_replace("\'", "'", $administradorEmail);
    $administradorEmail = str_replace('\"', '"', $administradorEmail);

    $perfilNombre = str_replace("\'", "'", $perfilNombre);
    $perfilNombre = str_replace('\"', '"', $perfilNombre);

    $administradorLogin = str_replace("\'", "'", $administradorLogin);
    $administradorLogin = str_replace('\"', '"', $administradorLogin);

    $administradorPass = str_replace("\'", "'", $administradorPass);
    $administradorPass = str_replace('\"', '"', $administradorPass);

    if ($resultado) {
        if ($resultado['estado'] == "1") {
            ?>                        
            <script language="JavaScript">       
                location.href = "manAdministradores.php";
            </script>
            <?
        } else if ($resultado['estado'] == "0") {
            ?>                                                            
            <script language="JavaScript">       
                alert("Los datos del administrador ya existen.");
            </script>                        
            <?
        } else if ($resultado['estado'] == "2") {
            ?>                                                            
            <script language="JavaScript">       
                alert("El perfil seleccionado no existe.");
            </script>                        
            <?
        } else {
            echo 'Se produjo un error. Intente nuevamente.';
        }
    } else {
        echo 'Se produjo un error. Intente nuevamente.';
    }
}
?>

<script type="text/javascript">
                                    
//    window.addEvent('domready', function() {
//                                                                                                                  
//        new Autocompleter.Request.HTML($('inAdmAdministradorPerfil'), '../librerias/autocompleterMantenedores.php', {
//            // class added to the input during request
//            'indicatorClass': 'autocompleter-loading', 
//            'minLength': 2,
//            'overflow': true,
//            'selectMode': 'type-ahead',
//
//            // send additional POST data, check the PHP code
//            'postData': {
//                'nombre_id'	:   'perfilId',
//                'nombre_campo' 	:   'perfilNombre',
//                'nombre_tablas' :   'From Perfiles p (nolock)',
//                'nombre_where'  :   'and clienteId = ' + $('clienteId').get('value') + ' and perfilEstado = 1'                                       
//            }
//        });
//                                                             
//    });                
                                    
</script>              

<script type="text/javascript">
                                                                                                    
    window.addEvent('domready', function() {  
        
        $('oNombres').setStyle('display', 'none');
        $('oApellidos').setStyle('display', 'none');        
        $('oEmail').setStyle('display', 'none');
        $('oEmail2').setStyle('display', 'none');
        //$('oPerfil').setStyle('display', 'none');
        $('oLogin').setStyle('display', 'none');
        $('oPass').setStyle('display', 'none');
        $('oPass2').setStyle('display', 'none');
        
//        $('mBoxContainerPer').setStyle('opacity', '0');
//        
//        $('btnCancelarPer').addEvent('click', function(){
//
//            var property = 'opacity';
//            var to = "0";
//
//            $('mBoxContainerPer').tween(property, to);
//
//        });
        
    });
    
</script>


<table width="100%">
    <tr>
        <td align="right">
            <a href="manAdministradores.php" class="volver">Volver</a>
        </td>
    </tr>    
</table>
<input type="hidden" name="clienteId" id="clienteId" value="<?php echo htmlentities($clienteId) ?>"/>

<h4>Ingresar Administrador</h4>
<br>

<div class="divElementos">

<div style="min-height: 400px">
    <div id="contenedor">

        <div class="divFormulario">

            <form id="frmAdmIngresar" name="frmAdmIngresar" method="post" action="manIngresarAdministrador.php" onKeyPress="return manDisableEnterKey(event)">                                                                                                           


                <table>
                    <tr>
                        <td width="100px" style="padding-bottom:6px">Cliente: </td>
                        <td width="250px" style="padding-bottom:6px"><?php echo htmlentities($clienteNombre) ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Nombres <span class="requerido">&nbsp;*</span>:</td>
                        <td><input type="text" name="inAdmAdministradorNombres" id="inAdmAdministradorNombres" class="campo" style="text-transform:uppercase;" maxlength="50" value= "<?php echo htmlentities($administradorNombres) ?>" /></td>
                        <td></td>
                        <td><label id="oNombres" style="color: #FF0000; font-size: 12px">Ingrese los nombres del Administrador</label></td>
                    </tr>
                    <tr>
                        <td>Apellidos <span class="requerido">&nbsp;*</span>:</td>
                        <td><input type="text" name="inAdmAdministradorApellidos" id="inAdmAdministradorApellidos" class="campo" style="text-transform:uppercase;" maxlength="50" value= "<?php echo htmlentities($administradorApellidos) ?>" /> </td>
                        <td></td>
                        <td><label id="oApellidos" style="color: #FF0000; font-size: 12px">Ingrese los apellidos del Administrador</label></td>
                    </tr>
                    <tr>
                        <td>Email <span class="requerido">&nbsp;*</span>:</td>
                        <td><input type="text" name="inAdmAdministradorEmail" id="inAdmAdministradorEmail" class="campo" maxlength="50" value= "<?php echo htmlentities($administradorEmail) ?>" /> </td>
                        <td></td>
                        <td><label id="oEmail" style="color: #FF0000; font-size: 12px">Ingrese e-mail del Administrador</label> <label id="oEmail2" style="color: #FF0000; font-size: 12px">Formato incorrecto.</label></td>
                    </tr>
<!--                    <tr>
                        <td>Perfil:</td>
                        <td><input type="text" name="inAdmAdministradorPerfil" id="inAdmAdministradorPerfil" class="campo" maxlength="50" value= "" /> </td>
                        <td width="20px"><img id="lupaSec" src='../skins/saam/img/lupa.png' style="cursor: pointer" onclick="manSeleccionarPerfiles()"/></td>
                        <td><label id="oPerfil" style="color: #FF0000; font-size: 12px">(*)</label></td>
                        <td valign="top">
                            <div id ="mBoxContainerPer" class="BoxContainer" style="position: absolute;" height="auto">

                                <div class="BoxTitle">Perfiles</div>    

                                <div id="BoxContentPer" class="BoxContent " style="width: auto; height: auto;">                        

                                </div>

                                <div class="BoxFooterContainer" align="right">

                                    <input id="btnCancelarPer" type="button" name="button" class="btn" value="Cancelar" />
                                    <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="manSeleccionarPerfil('inAdmAdministradorPerfil')" />

                                </div>

                            </div>
                        </td>
                    </tr>-->
                    <tr>
                        <td>Login <span class="requerido">&nbsp;*</span>:</td>
                        <td><input type="text" name="inAdmAdministradorLogin" id="inAdmAdministradorLogin" class="campo" maxlength="50" value= "<?php echo htmlentities($administradorLogin) ?>" /> </td>
                        <td></td>
                        <td><label id="oLogin" style="color: #FF0000; font-size: 12px">Ingrese nombre de usuario para el Administrador</label></td>
                    </tr>
                    <tr>
                        <td>Password <span class="requerido">&nbsp;*</span>:</td>
                        <td><input type="text" name="inAdmAdministradorPass" id="inAdmAdministradorPass" class="campo" maxlength="50" value= "<?php echo htmlentities($administradorPass) ?>" /> </td>
                        <td></td>
                        <td><label id="oPass" style="color: #FF0000; font-size: 12px">Ingrese contrase&ntilde;a para el Administrador</label> <label id="oPass2" style="color: #FF0000; font-size: 12px">Mínimo 5 caracteres.</label></td>
                    </tr>

                </table>                                                                                                                                           
        </div>                                                                           

        <p>   
            <br>
            <input type="button" name="button" value="Ingresar" class="btn azul" onclick="manValidarFormularioAdministradores('inAdmAdministradorNombres','inAdmAdministradorApellidos','inAdmAdministradorEmail','inAdmAdministradorPerfil','inAdmAdministradorLogin', 'inAdmAdministradorPass', 'btIngresarAdm')" />                                
            <label></label>                                
            <input type="button" name="cancelar" id="cancelar" value="Cancelar" class="btn" onclick="location.href = 'manAdministradores.php'" />
            <input type="submit" name="submit" id="btIngresarAdm" value="Ingresar" style="display: none" /> 
        </p>

        </form>

    </div>
</div>                                 
    
</div>


<?php
$plantilla->setTemplate("footer_2");
echo $plantilla->show();

/**
 * function texto_aleatorio (integer $long = 5, boolean $lestras_min = true, boolean $letras_max = true, boolean $num = true))
 *
 * Permite generar contrasenhas de manera aleatoria.
 *
 * @$long: Especifica la longitud de la contrasenha
 * @$letras_min: Podra usar letas en minusculas
 * @$letras_max: Podra usar letas en mayusculas
 * @$num: Podra usar numeros
 *
 * return string
 */
function texto_aleatorio($long = 5, $letras_min = true, $letras_max = true, $num = true) {
    $salt = $letras_min ? 'abchefghknpqrstuvwxyz' : '';
    $salt .= $letras_max ? 'ACDEFHKNPRSTUVWXYZ' : '';
    $salt .= $num ? (strlen($salt) ? '2345679' : '0123456789') : '';

    if (strlen($salt) == 0) {
        return '';
    }

    $i = 0;
    $str = '';

    srand((double) microtime() * 1000000);

    while ($i < $long) {
        $num = rand(0, strlen($salt) - 1);
        $str .= substr($salt, $num, 1);
        $i++;
    }

    return $str;
}
?>