<?
session_start();
include("../default.php");

ini_set('post_max_size', '1000M');
ini_set('upload_max_filesize', '1000M');
ini_set('max_execution_time', '100000');
ini_set('max_input_time', '100000');

$logo = "../images/EnvioPrueba.jpg";

$nusuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente = $_SESSION['clienteId'] ? $_SESSION['clienteId'] : 0;
$plantilla->setPath('../skins/saam/plantillas/');

setlocale(LC_TIME, "spanish");
$fechaActual = htmlentities(strftime("%A %d de %B del %Y"));
$fechaActual = ucfirst($fechaActual);

$plantilla->setTemplate("header_2");
$plantilla->setVars(array("USUARIO" => " $nusuario ",
    "FECHA" => "$fechaActual"));
echo $plantilla->show();


if (isset($_POST['submit'])) {

    $cliente = $_SESSION['clienteId'] ? $_SESSION['clienteId'] : 0;
    $tema = $_POST["tema_id"] ? $_POST["tema_id"] : $temaOld;

    $ntema = mb_convert_encoding($_POST["nombre_tema"], "ISO-8859-1", "UTF-8");
    $dtema = mb_convert_encoding($_POST["desc_tema"], "ISO-8859-1", "UTF-8");

    $emailAsunto = mb_convert_encoding($_POST["updEmailAsunto"], "ISO-8859-1", "UTF-8");
    $emailEncabezado = mb_convert_encoding($_POST["updEmailEncabezado"], "ISO-8859-1", "UTF-8");
    $emailPie = mb_convert_encoding($_POST["updEmailPie"], "ISO-8859-1", "UTF-8");

    $elimina = mb_convert_encoding($_POST["elimina"], "ISO-8859-1", "UTF-8");
    $archivo = mb_convert_encoding($_FILES['img_tema']['name'], "ISO-8859-1", "UTF-8");

    if ($elimina == "") {
        $elimina = 0;
    }

    $subido = 0;

    if ($archivo != "") {

        $tipo = $_FILES['img_tema']['type'];
        list($ancho, $alto) = getimagesize($_FILES['img_tema']['tmp_name']);

        $nombreimagen = mb_convert_encoding($_FILES['img_tema']['name'], "ISO-8859-1", "UTF-8");
        $arreglo = explode(".", $nombreimagen);
        $extension = $arreglo[1];

        if (($tipo == "image/png" || $tipo == "image/x-png" || $tipo == "image/jpeg" || $tipo == "image/pjpeg" || $tipo == "image/gif") && ($ancho == 700 && $alto <= 200)) {

            $error = $_FILES['img_tema']['error'];

            if ($error == UPLOAD_ERR_OK) {

                /** NOMBRE ARCHIVO * */
                $temaTemp = $tema;

                $fechaActual = date("Ymd_His");
                $temaTemp = $temaTemp . "_" . $fechaActual;

                $ruta = "../mantenedores/encabezados/" . $temaTemp . "." . $extension;

                $subido = copy($_FILES['img_tema']['tmp_name'], $ruta);

                if ($subido != 1) {
                    ?>
                    <script language="JavaScript">
                        alert("Error al subir el archivo. Inténtelo más tarde.");
                    </script>
                    <?
                    $edicion = "NO";
                } else {
                    $edicion = "SI";
                }
            }
        } else {
            $edicion = "NO";
            ?>
            <script language="JavaScript">
                alert("Archivo incorrecto. \nSólo se aceptan archivos del tipo PNG, JPEG y GIF.\n Medidas: 700px de ancho hasta 200px de largo.");
            </script>
            <?
        }
    } else {
        $edicion = "SI";
    }


    if ($edicion == "SI") {

        $ntema = str_replace("\'", "'", $ntema);
        $ntema = str_replace('\"', '"', $ntema);

        $dtema = str_replace("\'", "'", $dtema);
        $dtema = str_replace('\"', '"', $dtema);

        $emailAsunto = str_replace("\'", "'", $emailAsunto);
        $emailAsunto = str_replace('\"', '"', $emailAsunto);

        $emailEncabezado = str_replace("\'", "'", $emailEncabezado);
        $emailEncabezado = str_replace('\"', '"', $emailEncabezado);

        $emailPie = str_replace("\'", "'", $emailPie);
        $emailPie = str_replace('\"', '"', $emailPie);

        $ruta = substr($ruta, 2, strlen($ruta));

        $query = "EXEC capEditaTema $tema,'" . $ntema . "','" . $dtema . "'," . $cliente . ",'" . $administrador_id . "','" . $ruta . "'," . $elimina . ",'" . $emailAsunto . "','" . $emailEncabezado . "','" . $emailPie . "'";
        //echo $query;

        $result = $base_datos->sql_query($query);
        $row = $base_datos->sql_fetch_assoc($result);

        if ($result) {
            $estado = $row['estado'];

            if ($estado == "0") {

                if ($elimina == 1 || $subido == 1) {
                    $imagenAnterior = ".." . $row['imagenActual'];

                    if (file_exists($imagenAnterior)) {
                        unlink($imagenAnterior);
                    }
                }
                ?>
                <script language="JavaScript">
                    location.href = "listado_temas.php";
                </script>
                <?
            } else if ($estado == "1") {
                ?>
                <script language="JavaScript">
                    alert("El tema ingresado ya existe.");
                </script>
                <?
            } else {
                ?>
                <script language="JavaScript">
                    alert("Se ha producido un error. Por favor inténtelo más tarde.");
                </script>
                <?
            }
        }
    }

    $query = "EXEC CapTraeTema " . $tema . "," . $cliente . "";

    $result = $base_datos->sql_query($query);
    $row = $base_datos->sql_fetch_assoc($result);
    $itema = $row['temaImagen'] ? $row['temaImagen'] : ' ';
} else {

    $tema = $_POST['tema'] ? $_POST['tema'] : 0;

    if ($tema != 0) {
        $temaOld = $tema;
        $query = "EXEC CapTraeTema " . $tema . "," . $cliente . "";

        $result = $base_datos->sql_query($query);
        $row = $base_datos->sql_fetch_assoc($result);
        $ntema = $row['temaNombre'] ? $row['temaNombre'] : '';
        $dtema = $row['temaDescripcion'] ? $row['temaDescripcion'] : '';
        $itema = $row['temaImagen'] ? $row['temaImagen'] : ' ';

        $queryL = "Select LTRIM(RTRIM(clienteNombres)) + ' ' + LTRIM(RTRIM(clienteApellidos)) as 'clienteNombreCompleto'
                                From Clientes c (nolock)
                                Where c.clienteId = " . $cliente . "";

        $resultL = $base_datos->sql_query($queryL);
        $rowL = $base_datos->sql_fetch_assoc($resultL);
        $clienteNombre = $rowL['clienteNombreCompleto'] ? $rowL['clienteNombreCompleto'] : '';

        $queryPersonalizacion = "EXEC capListarPersonalizacion " . $clienteId . "," . $tema;

        $personalizacion = $base_datos->sql_query($queryPersonalizacion);
        $personalizacionData = $base_datos->sql_fetch_assoc($personalizacion);

        $emailAsunto = $personalizacionData['subject'];
        $emailEncabezado = $personalizacionData['encabezado'];
        $emailPie = $personalizacionData['footer'];
    }
}

//if ($errorimagen == 1) {
//
//}
?>

<!--    <script>ErrorImagen('//<? echo $_POST["nombre_tema"]; ?>','<? echo $_POST["desc_tema"]; ?>',<? echo $_POST["tema_id"]; ?>);</script>-->

<script type="text/javascript" src="../scripts/Funciones.js"></script>

<div>

    <script type="text/javascript" type="text/javascript">
                    function ValidarTema() {
                        var valido = true;
                        var nombreTema = document.getElementById("nombre_tema").value;
                        var descTema = document.getElementById("desc_tema").value;
                        var elDiv = document.getElementById("mensaje");
                        var archivo1 = document.getElementById('img_tema');

                        var tipoIncorrecto = 0;

                        var asunto = document.getElementById("updEmailAsunto").value;
                        var encabezado = document.getElementById("updEmailEncabezado").value;
                        var pie = document.getElementById("updEmailPie").value;

                        if (archivo1.value != '') {
                            var fullName = archivo1.value;
                            var splitName = fullName.split(".");
                            var fileType = splitName[1];
                            fileType = fileType.toLowerCase();

                            if (fileType != 'gif' && fileType != 'jpg' && fileType != 'png') {
                                elDiv.innerHTML = "<span style='color:red'><b>Solo se permiten imagenes GIF, JPG y PNG</b></span>";
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

//                        if (asunto == '') {
//                            valido = false;
//                        }

                        if (encabezado == '') {
                            valido = false;
                        }

                        if (pie == '') {
                            valido = false;
                        }

                        if (valido == true) {
                            //document.forms["FormTema"].submit();
                            document.getElementById("btnGuardar").click();
                        } else {
                            if (nombreTema == '' || descTema == '') {
                                elDiv.innerHTML = "<span style='color:red'><b>Debe ingresar el Nombre y la Descripci&oacute;n del Nuevo Tema</b></span>";
                            }
                            else {

                                if (tipoIncorrecto != 1) {
                                    elDiv.innerHTML = "";
                                }
                            }

//                            if (asunto == '') {
//                                document.getElementById("oAsunto").style.display = "";
//                            }
//                            else {
//                                document.getElementById("oAsunto").style.display = "none";
//                            }

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

                    function ErrorImagen(nombre, desc, tema) {
                        document.getElementById("nombre_tema").value = nombre;
                        document.getElementById("desc_tema").value = desc;
                        document.getElementById("tema_id").value = tema;
                        document.getElementById('mensaje').innerHTML = "<span style='color:red'><b>ERROR: La imagen debe ser de 700 x 200 pixeles</b></span>";
                    }

                    //function ErrorTema(){
                    //    alert('Ya Existe un tema con ese nombre');
                    //    document.getElementById("redir").submit();
                    //    }

                    function Volver() {
                        window.location = 'listado_temas.php'
                    }
    </script>

    <style>

        .tablaTema{
            border:1px solid #4B6C9F;
            margin:0px;
            border-collapse:collapse;
        }


        .tdCabeceraTemaA{
            background-color:#4B6C9F;
            color:white;
            vertical-align:middle;
            width:700px;
            border-bottom:1px solid #4B6C9F;
            text-align:center;
            height:150px;
        }

        .tdCabeceraTemaB{
            width:700px;
            height:150px;
            border:1px solid #4B6C9F;
            padding: 0px;
        }



    </style>

    <div style="height: auto">
        <table width="100%">
            <tr>
                <td align="right">
                    <a href="#" class="volver" onclick="Volver()">Volver</a>
                </td>
            </tr>
        </table>
        <h4>Editar Tema</h4>
        <form method="post" id="FormTema"  action="editar_tema.php" enctype="multipart/form-data">
            <div class="divFormulario" style="margin:10px;" width="100%">
                <table border="0" style="text-align:left;" width="850px">
                    <tr>
                        <td width="150px">
                            Cliente
                        </td>
                        <td width="700px">
                            <? echo $clienteNombre; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Tema<span class="requerido">&nbsp;*</span>
                        </td>

                        <td>
                            <input id="nombre_tema" name="nombre_tema" type="text" class="visible" style="width:300px" maxlength="50" value="<? echo htmlentities($ntema); ?>"/>
                            <input id="tema_id" name="tema_id" type="hidden" value="<? echo $tema; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Descripci&oacute;n<span class="requerido">&nbsp;*</span>
                        </td>
                        <td>
                            <input id="desc_tema" name="desc_tema" type="text" class="visible" style="width:300px" maxlength="250" value="<? echo htmlentities($dtema); ?>"/>
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
                            Eliminar Imagen Guardada
                            <? if (trim($itema) != '') { ?>
                                <input id="elimina" name="elimina" type="checkbox" value="1" <?
                                if ($elimina == "1") {
                                    echo "checked";
                                }
                                ?> />
                                   <? } else { ?>
                                <input id="elimina" name="elimina" type="checkbox" disabled/>
                            <? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            &nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            Vista Actual
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #ffffff;" colspan="2">

                            <table class="tablaTema" CELLSPACING='0' CELLSPADDING='0'>
                                <tr>

                                    <? if (trim($logo) == "") { ?>
                                        <td id="tdCabeceraTema1" class="tdCabeceraTemaA">
                                            <span style='font-weight:bolder;font-size:16px;'>C&Aacute;PSULAS DE CONOCIMIENTO</span>
                                        </td>
                                    <? } else {
                                        ?>
                                        <td id="tdCabeceraTema2" class="tdCabeceraTemaB">
                                            <img id="temaUrl" src='..<? echo htmlentities($itema); ?>' border='0' height='150px' width="700px" alt="<? echo htmlentities($ntema) ?>" title="<? echo htmlentities($ntema)
                                        ?>"/>
                                        </td>
                                    <? } ?>
                                    <td style='width:250px;border:1px solid #4B6C9F;' align='center'>
                                        <img src='../css/imagenes/headerFooter/logo.png' border='0'>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 100px" colspan="2" align="left">
                                        <h3>Bienvenido(a)</h3>
                                        <b>Nombre de usuario</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="tdTituloCapsula" align="center" colspan="2">
                                        <h1>NOMBRE CÁPSULA</h1>
                                    </td>
                                </tr>
                            </table>

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
                        <td width="100px">Asunto <span class="requerido"></span></td><td><input type="text" name="updEmailAsunto" id="updEmailAsunto" class="campoDescripcion" maxlength="250" value= "<?php echo htmlentities($emailAsunto) ?>" /></td><td valign="top"><label id="oAsunto" style="color: #FF0000; font-size: 12px; display:none">Debe ingresar un asunto para el mail</label></td>
                    </tr>
                    <tr>
                        <td valign="top">Encabezado <span class="requerido">&nbsp;*</span></td><td><textarea name="updEmailEncabezado" id="updEmailEncabezado" maxlength="1000" style="width:400px; height: 100px; resize: none; font-family: Arial; font-size: 13px"><?php echo htmlentities($emailEncabezado) ?></textarea></td><td valign="top"><label id="oEncabezado" style="color: #FF0000; font-size: 12px; display:none">Debe ingresar un Encabezado para el mail</label></td>
                    </tr>
                    <tr>
                        <td valign="top">Pie de página <span class="requerido">&nbsp;*</span></td><td><textarea name="updEmailPie" id="updEmailPie" maxlength="250" style="width:400px; height: 60px; resize: none; font-family: Arial; font-size: 13px"><?php echo htmlentities($emailPie) ?></textarea></td><td valign="top"><label id="oPie" style="color: #FF0000; font-size: 12px; display:none">Debe ingresar el pie de p&aacute;gina para el mail</label></td>
                    </tr>

                </table>

            </div>



            <table border="0" style="margin:8px;">
                <tr>
                    <td valign="top" align="left" colspan="2">
                        <input type="button" value="Guardar" onclick="ValidarTema();" class="btn azul"/>
                        <input type="button" value="Cancelar" onclick="Volver()" class="btn" />
                    </td>
                </tr>
            </table>
    </div>
    <input id="btnGuardar" type="submit" name="submit" value="Guardar"  style='display:none'/>
</form>


</div>

<?
$plantilla->setTemplate("footer_2");
echo $plantilla->show();




