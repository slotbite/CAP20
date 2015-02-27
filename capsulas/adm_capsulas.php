<?
session_start();
include("../default.php");
$plantilla->setPath('../skins/saam/plantillas/');

$nusuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';

setlocale(LC_TIME, "spanish");
$fechaActual = htmlentities(strftime("%A %d de %B del %Y"));
$fechaActual = ucfirst($fechaActual);

$plantilla->setTemplate("header_2");
$plantilla->setVars(array("USUARIO" => " $nusuario ",
    "FECHA" => "$fechaActual"));
echo $plantilla->show();
?>
<script type="text/javascript" src="../scripts/overlay.js"></script>
<script type="text/javascript" src="../scripts/multiBox.js"></script>
<link type="text/css" rel="stylesheet" href="../skins/saam/plantillas/multiBox.css" />
<script type="text/javascript" language="javascript">
    var searchBoxPpal;
    window.addEvent('domready', function(){
        searchBoxPpal = new multiBox({
            mbClass: '.mb',//class you need to add links that you want to trigger multiBox with (remember and update CSS files)
            container: $(document.body),//where to inject multiBox
            showNumbers: false,
            descClassName: 'multiBoxDesc',//the class name of the description divs
            path: './Files/',//path to mp3 and flv players
            useOverlay: true,//use a semi-transparent background. default: false;
            maxSize: {w:900, h:540},//max dimensions (width,height) - set to null to disable resizing
            addDownload: false,//do you want the files to be downloadable?
            pathToDownloadScript: './Scripts/forceDownload.asp',//if above is true, specify path to download script (classicASP and ASP.NET versions included)
            addRollover: true,//add rollover fade to each multibox link
            addOverlayIcon: true,//adds overlay icons to images within multibox links
            addChain: true,//cycle through all images fading them out then in
            recalcTop: true,//subtract the height of controls panel from top position
            addTips: false,//adds MooTools built in 'Tips' class to each element (see: http://mootools.net/docs/Plugins/Tips)
            autoOpen: 0//to auto open a multiBox element on page load change to (1, 2, or 3 etc)
        });

    });
</script>
<div>
    <?
    $usuario_id = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';

    if ($usuario_id == '') {
        echo "<script>window.location='../index.php';</script>";
        ?>
        <?
    } else {
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


        echo $plantilla->show();

        $plantilla->setTemplate("adm_capsulas");
        echo $plantilla->show();
    }
    ?>
</div>
<?
$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>

<script>
    function Envio(){
        window.location='../reportes/ListaEnvios_00.php';
    }
    function Volver(){
        window.location = '../index.php'
    }
</script>