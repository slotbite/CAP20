<?
session_start();
include("../default.php");

ini_set('post_max_size', '100M');
ini_set('upload_max_filesize', '100M');
ini_set('max_execution_time', '1000');
ini_set('max_input_time', '1000');

$nusuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente = $_SESSION['clienteId'] ? $_SESSION['clienteId'] : 0;
$plantilla->setPath('../skins/saam/plantillas/');

setlocale(LC_TIME, "spanish");
$fechaActual = htmlentities(strftime("%A %d de %B del %Y"));
$fechaActual = ucfirst($fechaActual);

$plantilla->setTemplate("header_2");
$plantilla->setVars(array("USUARIO" => " $nusuario ",
    "FECHA" => "$fechaActual"));

//
echo $plantilla->show();
?>
<link rel="stylesheet" type="text/css" href="../skins/saam/plantillas/style.css" media="screen" />


<link rel="stylesheet" type="text/css" href="../skins/saam/plantillas/style.css" media="screen" />
<div>
    <?
    $usuario_id = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';

    if ($usuario_id == '') {
        echo "<script>window.location='../index.php';</script>";
        ?>
        <?
    } else {
        $Cliente_id = $_SESSION["clienteId"] ? $_SESSION["clienteId"] : 0;

        $plantilla->setTemplate("menu2");
        $fecha = date("d-m-Y");

        if ($_SESSION['perfilId'] == 1) {
            $menu1 = "display:block;";
        } else {
            $menu1 = "display:none;";
        }

        $plantilla->setTemplate("menu2");
        $fecha = date("d-m-Y");
        $plantilla->setVars(array("USUARIO" => "$usuario_id",
            "FECHA" => "$fecha",
            "MANT" => "$menu1"
        ));

        $queryL = "Select LTRIM(RTRIM(clienteNombres)) + ' ' + LTRIM(RTRIM(clienteApellidos)) as 'clienteNombreCompleto'
                            From Clientes c (nolock)                        
                            Where c.clienteId = " . $Cliente_id . "";


        $resultL = $base_datos->sql_query($queryL);
        $rowL = $base_datos->sql_fetch_assoc($resultL);
        $clienteNombre = $rowL['clienteNombreCompleto'] ? $rowL['clienteNombreCompleto'] : '';


        // echo $plantilla->show();
    }
    ?>
    <script type="text/javascript" src="../scripts/Funciones.js"></script>
    <script type="text/javascript" type="text/javascript">
        function ValidarTema() {
            var valido = true;
            var nombreTema = document.getElementById("nombre_tema").value;
            var descTema = document.getElementById("desc_tema").value;
            var elDiv = document.getElementById("mensaje");

            var asunto = document.getElementById("inEmailAsunto").value;
            var encabezado = document.getElementById("inEmailEncabezado").value;
            var pie = document.getElementById("inEmailPie").value;

            var tipoIncorrecto = 0;

            var archivo1 = document.getElementById('img_tema');
            if (archivo1.value != '') {
                var fullName = archivo1.value;
                var splitName = fullName.split(".");
                var fileType = splitName[1];
                fileType = fileType.toLowerCase();

                if (fileType != 'gif' && fileType != 'jpg' && fileType != 'png') {
                    elDiv.innerHTML = "<span style='color:red'>Solo se permiten imagenes GIF, JPG y PNG</span>";
                    valido = false;
                    tipoIncorrecto = 1;
                }
            }


            nombreTema = nombreTema.trim();
            descTema = descTema.trim();

            asunto = asunto.trim();
            encabezado = encabezado.trim();
            pie = pie.trim();


            if (nombreTema == '' || descTema == '') {
                valido = false;
            }

//            if (asunto == '') {
//                valido = false;
//            }

            if (encabezado == '') {
                valido = false;
            }

            if (pie == '') {
                valido = false;
            }


            if (valido == true) {
                document.forms["FormTema"].submit();
            } else {

                if (nombreTema == '' || descTema == '') {
                    elDiv.innerHTML = "<span style='color:red'>Debe ingresar el Nombre y la Descripci&oacute;n del Nuevo Tema</span>";
                }
                else {
                    if (tipoIncorrecto != 1) {
                        elDiv.innerHTML = "";
                    }
                }

//                if (asunto == '') {
//                    document.getElementById("oAsunto").style.display = "";
//                }
//                else {
//                    document.getElementById("oAsunto").style.display = "none";
//                }

                if (encabezado == '') {
                    document.getElementById("oEncabezado").style.display = "";
                }
                else {
                    document.getElementById("oEncabezado").style.display = "none";
                }

                if (pie == '') {
                    document.getElementById("oPie").style.display = "";
                }
                else {
                    document.getElementById("oPie").style.display = "none";
                }

            }

        }

        function ErrorImagen(nombre, desc) {
            document.getElementById("nombre_tema").value = nombre;
            document.getElementById("desc_tema").value = desc;
            document.getElementById('mensaje').innerHTML = "<span style='color:red'><b>ERROR: La imagen debe ser de 700px de ancho hasta 200px de largo.</b></span>";
        }

        function ErrorTema() {
            document.getElementById('mensaje').innerHTML = "<span style='color:red'><b>ERROR: Ya existe un Tema con ese nombre</b></span>";
        }

        function Volver() {
            window.location = 'listado_temas.php'
        }
    </script>
    <?
    if (($_POST["nombre_tema"] != "") && ($_POST["desc_tema"] != "")) {
        $errorimagen = 0;
        $imagen = $_POST["img_tema"] ? $_POST["img_tema"] : "";

        $administrador_id = $_SESSION['administradorId'];
        $nombre_tema = mb_convert_encoding($_POST["nombre_tema"], "ISO-8859-1", "UTF-8");
        $desc_tema = mb_convert_encoding($_POST["desc_tema"], "ISO-8859-1", "UTF-8");

        $emailAsunto = mb_convert_encoding($_POST["inEmailAsunto"], "ISO-8859-1", "UTF-8");
        $emailEncabezado = mb_convert_encoding($_POST["inEmailEncabezado"], "ISO-8859-1", "UTF-8");
        $emailPie = mb_convert_encoding($_POST["inEmailPie"], "ISO-8859-1", "UTF-8");


        $nombre_tema = str_replace("\'", "'", $nombre_tema);
        $nombre_tema = str_replace('\"', '"', $nombre_tema);

        $desc_tema = str_replace("\'", "'", $desc_tema);
        $desc_tema = str_replace('\"', '"', $desc_tema);


        $emailAsunto = str_replace("\'", "'", $emailAsunto);
        $emailAsunto = str_replace('\"', '"', $emailAsunto);

        $emailEncabezado = str_replace("\'", "'", $emailEncabezado);
        $emailEncabezado = str_replace('\"', '"', $emailEncabezado);

        $emailPie = str_replace("\'", "'", $emailPie);
        $emailPie = str_replace('\"', '"', $emailPie);


        if (isset($_FILES['img_tema']['tmp_name'])) {
            $uploaddir = "../mantenedores/encabezados/";
            if (file_exists($uploaddir) == false) {
                mkdir($uploaddir, 0700);
            }

            list($ancho, $alto) = getimagesize($_FILES['img_tema']['tmp_name']);

            if ($ancho == 700 && $alto <= 200) {
                $nombreimagen = mb_convert_encoding($_FILES['img_tema']['name'], "ISO-8859-1", "UTF-8");
                $arreglo1 = explode(".", $nombreimagen);
                $extension = $arreglo1[1];

                //maximo tema_id +1
                $queryL = "select isnull(max(temaid),0)+1 as temaid from temas where clienteId=" . $cliente . "";
                $resultL = $base_datos->sql_query($queryL);
                $rowL = $base_datos->sql_fetch_assoc($resultL);
                $temaTemp = $rowL['temaid'] ? $rowL['temaid'] : '';

                $nombrefinal = $cliente . "_" . $temaTemp . "." . $extension;

                $uploadfile = $uploaddir . basename($nombrefinal);
                $error = $_FILES['img_tema']['error'];
                $subido = false;
                if ($error == UPLOAD_ERR_OK) {
                    $subido = copy($_FILES['img_tema']['tmp_name'], $uploadfile);
                }
            } else {

                $errorimagen = 1;
            }

            if (trim($_FILES['img_tema']['tmp_name']) == "") {
                $errorimagen = 0;
            }
        }



        if ($errorimagen == 0) {
            $archivo = "";
            if (isset($uploadfile)) {
                $archivo = substr($uploadfile, 2, strlen($uploadfile));
            }
            $query = "EXEC capInsertaTema '" . $nombre_tema . "','" . $desc_tema . "'," . $cliente . ",'" . $administrador_id . "','" . $archivo . "','" . $emailAsunto . "','" . $emailEncabezado . "','" . $emailPie . "'";

            $result = $base_datos->sql_query($query);

            $row = $base_datos->sql_fetch_assoc($result);

            $errorIns = $row["errorIns"] ? $row["errorIns"] : 0;
            $id = $row["temaId"] ? $row["temaId"] : 0;

            if ($errorIns == 0) {
                $nombre_tema = mb_convert_encoding($nombre_tema, "UTF-8", "ISO-8859-1");
                /* echo "<script>parent.document.getElementById('nombre_tema').value='".$nombre_tema."'</script>";
                  echo "<script>parent.document.getElementById('tema_id').value='".$id."'</script>";
                  echo "<script>parent.searchBox.close()</script>"; */
                echo "<script>Volver()</script>";
            }
        }
    }
    ?>


    <table width="100%">
        <tr>
            <td align="right">
                <a href="#" class="volver" onclick="Volver()">Volver</a>
            </td>
        </tr>    
    </table>  
    <h4>Ingresar Tema</h4>
    <form method="post" id="FormTema" action="crear_tema_Mant.php" enctype="multipart/form-data">

        <div class="divFormulario" style="margin:10px;" width="100%">
            <table border="0" style="text-align:left;" >
                <tr>
                    <td>Cliente</td>
                    <td><? echo $clienteNombre; ?></td>
                </tr>    
                <tr>
                    <td style="width:150px;">
                        Tema<span class="requerido">&nbsp;*</span>
                    </td>

                    <td>
                        <input id="nombre_tema" name="nombre_tema" type="text" class="visible" style="width:300px" maxlength="50" />
                    </td>
                </tr>
                <tr>
                    <td>
                        Descripción<span class="requerido">&nbsp;*</span>
                    </td>

                    <td>
                        <input id="desc_tema" name="desc_tema" type="text" class="visible" style="width:300px" maxlength="250" />
                    </td>
                </tr>
                <tr>
                    <td>
                        Imagen Encabezado
                    </td>

                    <td>
                        <input id="img_tema" name="img_tema" type="file" class="visible" style="width:300px" maxlength="250" />
                    </td>
                </tr>    
                <tr>
                    <td colspan="2">
                        <div id="mensaje" style="color:red;font-size:11px;height:30px;"></div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="divFormulario" style="margin:10px;" width="100%">

            <font color="#003366">Personalización de E-mail</font>            
            <br><br>

            <table>                    
                <tr>
                    <td width="100px">Asunto <span class="requerido"></span></td><td><input type="text" name="inEmailAsunto" id="inEmailAsunto" class="campoDescripcion" maxlength="250" value= "<?php echo htmlentities($emailAsunto) ?>" /></td><td valign="top"><label id="oAsunto" style="color: #FF0000; font-size: 12px; display:none">Debe ingresar un asunto para el mail</label></td>
                </tr>
                <tr>
                    <td valign="top">Encabezado <span class="requerido">&nbsp;*</span></td><td><textarea name="inEmailEncabezado" id="inEmailEncabezado" maxlength="1000" style="width:400px; height: 100px; resize: none; font-family: Arial; font-size: 13px"><?php echo htmlentities($emailEncabezado) ?></textarea></td><td valign="top"><label id="oEncabezado" style="color: #FF0000; font-size: 12px; display:none">Debe ingresar un Encabezado para el mail</label></td>
                </tr>
                <tr>
                    <td valign="top">Pie de página <span class="requerido">&nbsp;*</span></td><td><textarea name="inEmailPie" id="inEmailPie" maxlength="250" style="width:400px; height: 60px; resize: none; font-family: Arial; font-size: 13px"><?php echo htmlentities($emailPie) ?></textarea></td><td valign="top"><label id="oPie" style="color: #FF0000; font-size: 12px; display:none">Debe ingresar el pie de p&aacute;gina para el mail</label></td>
                </tr>

            </table>

        </div>

        <table border="0" style="margin:8px;">
            <tr>
                <td valign="top" align="left">
                    <input type="button" value="Ingresar" onclick="ValidarTema();" class="btn azul"/>
                    <input type="button" value="Cancelar" onclick="Volver()" class="btn" />
                </td>
            </tr>
        </table>


    </form>
</div>
<?
$plantilla->setTemplate("footer_2");
echo $plantilla->show();
if ($errorimagen == 1) {
    ?>
    <script>ErrorImagen('<? echo $_POST["nombre_tema"]; ?>', '<? echo $_POST["desc_tema"]; ?>');</script>
    <?
}

if ($errorIns == 1) {
    ?>
    <script>ErrorTema();</script>
    <?
}
?>

