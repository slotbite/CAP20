<?
session_start();
include("../default.php");
$plantilla->setPath('../skins/saam/plantillas/');

$nusuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$capsula_id = $_SESSION['capsulaId2'] ? $_SESSION['capsulaId2'] : 0;
$capsulaVersion = $_SESSION['capsulaVersion2'] ? $_SESSION['capsulaVersion2'] : 0;
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] : 0;

$plantilla->setTemplate("header_3");
//
echo $plantilla->show();


$queryTipo = "EXEC capVerCapsula " . $clienteId . "," . $capsula_id . "," . $capsulaVersion . " ";
$result = $base_datos->sql_query($queryTipo);
$row = $base_datos->sql_fetch_assoc($result);
$nombre = $row["capsulaNombre"];
$tipo = $row["capsulaTipo"];



$queryEnvio = "EXEC envTraeEnvioId " . $clienteId . "";
$resultE = $base_datos->sql_query($queryEnvio);
$rowE = $base_datos->sql_fetch_assoc($resultE);
$envioId = $rowE["envioId"];

$queryFecha = "EXEC envTraeDuracionCapsula " . $clienteId . "";
$resultF = $base_datos->sql_query($queryFecha);
$rowF = $base_datos->sql_fetch_assoc($resultF);
$dias = $rowF["plazoDias"];
?>
<script type="text/javascript" src="../scripts/tablesort.js"></script>

<?
$Cliente_id = $_SESSION["clienteId"] ? $_SESSION["clienteId"] : 0;
$usuario_id = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';

if ($usuario_id == '') {
    echo "<script>window.location='../index.php';</script>";
    ?>
    <?
} else {

// $plantilla->setTemplate("menu2");
// $fecha=date("d-m-Y");
// $plantilla->setVars(array(	"USUARIO" =>"$usuario_id",
    // "FECHA"	  =>	"$fecha"
    // ));
// echo $plantilla->show();

    $fechaActual = date("d-m-Y");
//Le sumo los dias de la consulta a la fecha actual:
    $date = strtotime(date("d-m-Y", strtotime($fechaActual)) . " +$dias day");
    $fechacierre = date('d-m-Y', $date);

    $plantilla->setTemplate("envio_capsula_1w");
    $plantilla->setVars(array("USUARIO" => "$usuario_id",
        "CLIENTE" => "$Cliente_id",
        "NOMBRE" => "$nombre",
        "CAPSULA_ID" => "$capsula_id",
        "CAPSULA_VER" => "$capsulaVersion",
        "PERSONALIZACIONID" => "$personalizacionId",
        "ENVIOID" => "$envioId",
        "FECHACIERRE" => "$fechacierre"
    ));
    echo $plantilla->show();
}
?>
</div>
<?
// $plantilla->setTemplate("footer_2");
// echo $plantilla->show();
?>
<script>
    var cantidadenvios=0;
    var enviados=0;
    var elRequest2={};
    window.addEvent('domready', function (){
        //estilos para mis "TABS":
        $('Usuarios').setStyle('display', 'block');
        $('Evaluaciones').setStyle('display', 'none');
        // $('Usuarios1').setStyle('background-color','#003366');
        // $('Usuarios1').setStyle('color','white');

        // $('Evaluaciones1').setStyle('background-color','white');
        // $('Evaluaciones1').setStyle('color','black');

        $('Usuarios1').setStyle('cursor','default');
        $('Evaluaciones1').setStyle('cursor','default');
        $('lupaGrupo').setStyle('visibility', 'hidden');
    });

    $('Usuarios1').addEvent('click', function(){
        $('Usuarios').setStyle('display', 'block');
        $('Evaluaciones').setStyle('display', 'none');
        // $('Usuarios1').setStyle('background-color','#003366');
        // $('Usuarios1').setStyle('color','white');
        // $('Evaluaciones1').setStyle('background-color','white');
        // $('Evaluaciones1').setStyle('color','black');
        var tabla1= $('encabezado_grilla');
        tabla1.rows[0].cells[0].getElementsByTagName('input')[0].disabled=false;
    });

    $('Evaluaciones1').addEvent('click', function(){
        $('Usuarios').setStyle('display', 'none');
        $('Evaluaciones').setStyle('display', 'block');
        // $('Evaluaciones1').setStyle('background-color','#003366');
        // $('Evaluaciones1').setStyle('color','white');
        // $('Usuarios1').setStyle('background-color','white');
        // $('Usuarios1').setStyle('color','black');
        var tabla1= $('encabezado_grilla');
        tabla1.rows[0].cells[0].getElementsByTagName('input')[0].disabled=true;
    });

    $('radio2').addEvent('click', function(){
        $('organizacionNombre').value='';
        $('sectorNombre').value='';
        $('areaNombre').value='';
        $('organizacionNombre').disabled = true;
        $('sectorNombre').disabled = true;
        $('areaNombre').disabled = true;
        $('Grupo').disabled = false;
        $('lupaGrupo').setStyle('visibility', 'visible');
        $('lupaOrg').setStyle('visibility', 'hidden');
        $('lupaSect').setStyle('visibility', 'hidden');
        $('lupaArea').setStyle('visibility', 'hidden');


    });

    $('radio1').addEvent('click', function(){
        $('Grupo').value='';
        $('organizacionNombre').disabled = false;
        $('sectorNombre').disabled = false;
        $('areaNombre').disabled = false;
        $('Grupo').disabled = true;

        $('lupaGrupo').setStyle('visibility', 'hidden');
        $('lupaOrg').setStyle('visibility', 'visible');
        $('lupaSect').setStyle('visibility', 'visible');
        $('lupaArea').setStyle('visibility', 'visible');
    });


    window.addEvent('domready', function() {
        $('mBoxContainerOrg').set("opacity", 0);
        $('mBoxContainerSec').set("opacity", 0);
        $('mBoxContainerAre').set("opacity", 0);
        $('mBoxContainerGru').set("opacity", 0);
    });

    window.addEvent('domready', function (){

        new Autocompleter.Request.HTML($('Grupo'), '../librerias/autocompleter.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading',
            'minLength': 1,
            'overflow': true,
            'selectMode': 'type-ahead',

            'postData': {
                'nombre_id'			:	'grupoId',
                'nombre_campo' 		: 	'grupoNombre',
                'nombre_tabla' 		: 	'Grupos',
                'nombre_estado'		:   'grupoEstado'
            }
        });

        new Autocompleter.Request.HTML($('Evaluacion'), '../librerias/autocompleter.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading',
            'minLength': 1,
            'overflow': true,
            'selectMode': 'type-ahead',

            'postData': {
                'nombre_id'		:	'evaluacionId',
                'nombre_campo' 		: 	'evaluacionNombre',
                'nombre_tabla' 		: 	'Evaluaciones',
                'nombre_estado'		:   'evaluacionEstado'
            }
        });


    });


    window.addEvent('domready', function() {
        $('btnCancelarOrg').addEvent('click', function(){

            var property = 'opacity';
            var to = "0";

            $('mBoxContainerOrg').tween(property, to);

        });

        $('btnCancelarSec').addEvent('click', function(){

            var property = 'opacity';
            var to = "0";

            $('mBoxContainerSec').tween(property, to);

        });

        $('btnCancelarAre').addEvent('click', function(){

            var property = 'opacity';
            var to = "0";

            $('mBoxContainerAre').tween(property, to);

        });

        $('btnCancelarGru').addEvent('click', function(){

            var property = 'opacity';
            var to = "0";

            $('mBoxContainerGru').tween(property, to);

        });

        $('btnCancelarEva').addEvent('click', function(){

            var property = 'opacity';
            var to = "0";

            $('mBoxContainerEva').tween(property, to);

        });
    });

    $('ListarU').addEvent('click', function(){
        $('ListaUsuarios').set('html','');
        $('listado').setStyle('visibility', 'hidden');
        $('ErrorBusqueda').set('html','');
        var valorRadio=$('radio1').checked;
        var orgNombre=$('organizacionNombre').value;
        var secNombre=$('sectorNombre').value;
        var areaNombre=$('areaNombre').value;



        if(valorRadio==true){

            var elRequest1 = new Request({
                url		: '../librerias/listar_usuarios_envio_organizaciones.php',
                method  :'post',
                onSuccess: function(html) {
                    $('ListaUsuarios').set('html','');
                    //Limpiar el Div y recargar la lista!!!
                    $('ListaUsuarios').set('html',html);
                    //var myTable = new HtmlTable($('ListadeUsuarios'), {sortable: true});
                    fdTableSort.init('ListadeUsuarios');
                    $('listado').setStyle('visibility', 'visible');
                },
                //Si Falla
                onFailure: function() {
                    $('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
                }
            });
            elRequest1.send(	"orgNombre=" 		        + orgNombre +
                "&secNombre="				+secNombre +
                "&areaNombre="				+areaNombre
        );

        }else{
            if($('GrupoID').value=='0'||$('GrupoID').value==''){
                $('ErrorBusqueda').set('html', "<span style='color:red'><b>Debe ingresar un nombre de Grupo para buscar</b></span>");
            }else{
                var grupoNombre=$('GrupoID').value;

                var elRequest = new Request({
                    url		: '../librerias/listar_usuarios_envio_grupo.php',
                    method  :'post',
                    onSuccess: function(html) {
                        //Limpiar el Div y recargar la lista!!!
                        $('ListaUsuarios').set('html',html);
                        //var myTable = new HtmlTable($('ListadeUsuarios'), {sortable: true});
                        fdTableSort.init('ListadeUsuarios');
                        $('listado').setStyle('visibility', 'visible');
                    },
                    //Si Falla
                    onFailure: function() {
                        $('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
                    }
                });
                elRequest.send(	"grupoNombre=" + grupoNombre
            );
            }
        }
    });

    $('ListarE').addEvent('click', function(){
        $('ListaUsuarios').set('html','');
        $('listado').setStyle('visibility', 'hidden');
        $('ErrorBusqueda').set('html','');

        if($('EvaluacionID').value==''){
            $('ErrorBusqueda').set('html', "<span style='color:red'><b>Debe ingresar un nombre de Evaluacion para buscar</b></span>");
        }else{
            var evalNombre=$('EvaluacionID').value;

            var elRequest = new Request({
                url		: '../librerias/listar_usuarios_envio_evaluaciones.php',
                method  :'post',
                onSuccess: function(html) {
                    //Limpiar el Div y recargar la lista!!!
                    $('ListaUsuarios').set('html',html);
                    fdTableSort.init('ListadeUsuarios');
                    $('listado').setStyle('visibility', 'visible');
                },
                //Si Falla
                onFailure: function() {
                    $('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
                }
            });
            elRequest.send(	"evaluacionId=" + evalNombre
        );
        }


    });

    $('Grupo').addEvent('blur', function(){
        var elRequest = new Request({
            url		: '../librerias/traer_id_grupo.php',
            method  :'post',
            onSuccess: function(html) {
                //Limpiar el Div
                $('ErrorBusqueda').set('html', "");

                if(html=='0'){
                    $('ErrorBusqueda').set('html', "<span style='color:red'><b>Debe ingresar un Nombre de Grupo Existente</b></span>");
                }else{
                    $('GrupoID').set('value',html);
                }

            },
            //Si Falla
            onFailure: function() {
                $('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
            }
        });
        elRequest.send(	"nombre_grupo=" + encodeURIComponent($('Grupo').get('value')) );
    });

    $('Evaluacion').addEvent('blur', function(){
        var elRequest = new Request({
            url		: '../librerias/traer_id_evaluacion.php',
            method  :'post',
            onSuccess: function(html) {
                //Limpiar el Div
                $('ErrorBusqueda').set('html', "");

                if(html=='0'){
                    $('ErrorBusqueda').set('html', "<span style='color:red'><b>Debe ingresar un Nombre de Evaluaci&oacute;n Existente</b></span>");
                }else{
                    $('EvaluacionID').set('value',html);
                }

            },
            //Si Falla
            onFailure: function() {
                $('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
            }
        });
        elRequest.send(	"nombre_eval=" + encodeURIComponent($('Evaluacion').get('value')) );
    });


    function SeleccionarTodo(valor){
        var tabla=$('ListadeUsuarios');
        var largo=tabla.rows.length;
        for (i=1;i<largo;i++){
            tabla.rows[i].cells[0].getElementsByTagName('input')[0].checked=valor;
        }
    }

    function Validar(){
        $('ErrorBusqueda').set('html', "");
        var chequeados=0;
        var tabla=$('ListadeUsuarios');
        if(tabla!=null){
            var largo=tabla.rows.length;
            for (i=1;i<largo;i++){
                if(tabla.rows[i].cells[0].getElementsByTagName('input')[0].checked==true){
                    chequeados=chequeados+1;
                };

            }
            if(chequeados==0){
                $('ErrorBusqueda').set('html', "<span style='color:red'><b>Debe Seleccionar al menos un Usuario</b></span>");
            }else{
                $('finalizar').disabled = true;
                cantidadenvios=chequeados;
                GenerarEnvios();

            }
        }else{
            $('ErrorBusqueda').set('html', "<span style='color:red'><b>Debe Seleccionar Usuarios</b></span>");
        }
    }

    function GenerarEnvios(){

        //aca se recorre la tabla y se hace un request por cada envio, una vez realizado el ultimo, se limpia el div y se va a la siguiente pantalla
        var tabla=$('ListadeUsuarios');
        var largo=tabla.rows.length;
        for (i=1;i<largo;i++){
            if(tabla.rows[i].cells[0].getElementsByTagName('input')[0].checked==true){
                var usuarioEnvio=tabla.rows[i].cells[1].innerText;
                var mailenvio=tabla.rows[i].cells[2].innerText;
                var usuarioIdEnvio=tabla.rows[i].cells[1].getElementsByTagName('input')[0].value;
                var grupoId=$('Grupo').value;
                if(grupoId==''){
                    grupoId=0;
                }else{
                    grupoId=$('GrupoID').value;
                }
                var envioId=$('envioId').value;
                var fechacierre=$('fecha_fin').value;

                divResultado=$('capaT');

                var elRequest1 = new Request({
                    url		: '../librerias/guardaEnvioCapsula.php',
                    method  :'post',
                    link: 'chain',
                    onRequest: function(){
                        divResultado.set('html', "<b>Enviando...</b><img src='../scripts/img/spinner.gif'/>");
                        divResultado.setStyle('display', 'block');
                    },
                    onComplete: function(html) {
                        enviados=enviados+1;
                        divResultado.set('html', '<b>Enviado(s) '+enviados+' de '+cantidadenvios+ ' correo(s)</b><br/><img src="../scripts/img/spinner.gif"/>');
                        $('cantidadEnvios').set('value',enviados);
                        if(enviados==cantidadenvios){
                            divResultado.set('html', '<b>Env&iacute;o Completado</b>');
                            $('siguiente').disabled = false;
                            divResultado.setStyle('display', 'none');
                        }
                    },

                    onSuccess: function(html) {

                    },
                    //Si Falla
                    onFailure: function() {
                        //$('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
                    }
                });
                elRequest1.send(	"usuarioEnvio="  + encodeURIComponent(usuarioEnvio) +
                    "&mailenvio=" + mailenvio +
                    "&usuarioIdEnvio=" + usuarioIdEnvio +
                    "&envioId="	+ envioId +
                    "&grupoId=" + grupoId+
                    "&fechacierre=" + fechacierre +
                    "&capsula=" + <? echo $capsula_id ?>);




            }

        }


    }

    function Siguiente(){
        $('siguienteForm').submit();
    }

    function cancelar(){
        if (confirm('Seguro que desea cancelar la Envio de la C\u00e1psula?')==true){
            $('siguienteForm').action='wizard_capsula_fin1.php';
            $('siguienteForm').submit();
        }
    }

    function planSeleccionarOrganizaciones(){

        var property = 'opacity';
        var to = "1";


        var elRequest = new Request({
            url         : 'FuncionBuscarOrganizaciones.php',
            method      : 'post',
            async       : false,

            onSuccess   : function(datos) {
                if(datos)
                {
                    $('BoxContentOrg').set('html',datos);
                    $('mBoxContainerOrg').tween(property, to);

                }
            },
            //Si Falla
            onFailure: function() {
                alert("Se ha producido un error. Por favor, intentelo mas tarde.");
            }
        });

        elRequest.send();


    }


    function planSeleccionarOrganizacion(){

        tabla = $('tablaListaOrganizaciones');
        filas = tabla.rows.length;

        for(i = 0; i < filas; i++){
            objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];

            if(objInput0.checked == true){

                objInput1 = tabla.rows[i].cells[1];
                $('organizacionNombre').set('value', ((objInput1.innerText != undefined) ? objInput1.innerText : objInput1.textContent ));
                $('organizacionNombre').fireEvent('blur');
                i = filas;
            }
        }

        $('mBoxContainerOrg').tween("opacity", 0);

    }


    function planSeleccionarSectores(){

        var organizacionNombre = ($('organizacionNombre').get('value'));

        var property = 'opacity';
        var to = "1";

        var elRequest = new Request({
            url         : 'FuncionBuscarSectores.php',
            method      : 'post',
            async       : false,

            onSuccess   : function(datos) {
                if(datos)
                {
                    $('BoxContentSec').set('html',datos);
                    $('mBoxContainerSec').tween(property, to);

                }
            },
            //Si Falla
            onFailure: function() {
                alert("Se ha producido un error. Por favor, intentelo mas tarde.");
            }
        });

        elRequest.send("organizacionNombre=" + encodeURIComponent(organizacionNombre));

    }


    function planSeleccionarSector(){

        tabla = $('tablaListaSectores');
        filas = tabla.rows.length;

        for(i = 0; i < filas; i++){
            objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];

            if(objInput0.checked == true){

                objInputSec = tabla.rows[i].cells[1];
                //$('organizacionNombre').set('value', ((objInputOrg.innerText != undefined) ? objInputOrg.innerText : objInputOrg.textContent ));
                $('sectorNombre').set('value', ((objInputSec.innerText != undefined) ? objInputSec.innerText : objInputSec.textContent ));
                $('sectorNombre').fireEvent('blur');
                i = filas;
            }
        }

        $('mBoxContainerSec').tween("opacity", 0);

    }


    function planSeleccionarAreas(){

        var organizacionNombre = ($('organizacionNombre').get('value'));
        var sectorNombre = ($('sectorNombre').get('value'));

        var property = 'opacity';
        var to = "1";


        var elRequest = new Request({
            url         : 'FuncionBuscarAreas.php',
            method      : 'post',
            async       : false,

            onSuccess   : function(datos) {
                if(datos)
                {
                    $('BoxContentAre').set('html',datos);
                    $('mBoxContainerAre').tween(property, to);

                }
            },
            //Si Falla
            onFailure: function() {
                alert("Se ha producido un error. Por favor, intentelo mas tarde.");
            }
        });

        elRequest.send("organizacionNombre=" + encodeURIComponent(organizacionNombre) +
            "&sectorNombre=" + encodeURIComponent(sectorNombre)
    );
    }


    function planSeleccionarArea(){

        tabla = $('tablaListaAreas');
        filas = tabla.rows.length;

        for(i = 0; i < filas; i++){
            objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];

            if(objInput0.checked == true){

                // objInputOrg = tabla.rows[i].cells[1];
                // objInputSec = tabla.rows[i].cells[2];
                objInputAre = tabla.rows[i].cells[1];
                $('areaNombre').set('value', ((objInputAre.innerText != undefined) ? objInputAre.innerText : objInputAre.textContent ));
                $('areaNombre').fireEvent('blur');
                i = filas;
            }
        }

        $('mBoxContainerAre').tween("opacity", 0);

    }


    function planSeleccionarGrupos(){

        var property = 'opacity';
        var to = "1";


        var elRequest = new Request({
            url         : 'FuncionBuscarGrupos.php',
            method      : 'post',
            async       : false,

            onSuccess   : function(datos) {
                if(datos)
                {
                    $('BoxContentGru').set('html',datos);
                    $('mBoxContainerGru').tween(property, to);

                }
            },
            //Si Falla
            onFailure: function() {
                alert("Se ha producido un error. Por favor, intentelo mas tarde.");
            }
        });

        elRequest.send();
    }


    function planSeleccionarGrupo(){

        tabla = $('tablaListaGrupos');
        filas = tabla.rows.length;

        for(i = 0; i < filas; i++){
            objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];

            if(objInput0.checked == true){

                objInputGru= tabla.rows[i].cells[1];
                $('Grupo').set('value', ((objInputGru.innerText != undefined) ? objInputGru.innerText : objInputGru.textContent ));
                $('Grupo').fireEvent('blur');
                i = filas;
            }
        }

        $('mBoxContainerGru').tween("opacity", 0);

    }


    function planSeleccionarEvaluaciones(){

        var property = 'opacity';
        var to = "1";


        var elRequest = new Request({
            url         : 'FuncionBuscarEvaluaciones.php',
            method      : 'post',
            async       : false,

            onSuccess   : function(datos) {
                if(datos)
                {
                    $('BoxContentEva').set('html',datos);
                    $('mBoxContainerEva').tween(property, to);

                }
            },
            //Si Falla
            onFailure: function() {
                alert("Se ha producido un error. Por favor, intentelo mas tarde.");
            }
        });

        elRequest.send();
    }


    function planSeleccionarEvaluacion(){

        tabla = $('tablaListaEvaluaciones');
        filas = tabla.rows.length;

        for(i = 0; i < filas; i++){
            objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];

            if(objInput0.checked == true){

                objInputEva = tabla.rows[i].cells[1];
                $('Evaluacion').set('value', ((objInputEva.innerText != undefined) ? objInputEva.innerText : objInputEva.textContent ));
                $('Evaluacion').fireEvent('blur');
                i = filas;
            }
        }

        $('mBoxContainerEva').tween("opacity", 0);

    }


    function manMostrarSectores(inputOrg, inputSec){

        var nombreOrg = ($(inputOrg).get('value'));

        do{
            nombreOrg = nombreOrg.replace("'","");
        }while(nombreOrg.indexOf("'") >= 0);


        do{
            nombreOrg = nombreOrg.replace('"','');
        }while(nombreOrg.indexOf('"') >= 0);


        new Autocompleter.Request.HTML($(inputSec), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading',
            'minLength': 1,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id' : 'sectorId',
                'nombre_campo' : 'sectorNombre',
                'nombre_tablas' :       'From Sectores s (nolock) inner join Organizaciones o (nolock) on s.organizacionId = o.organizacionId',
                'nombre_where'      :       "and s.clienteId = " + $('clienteId').get('value') + " and o.organizacionNombre like '" + nombreOrg + "%' and o.organizacionEstado = 1 and s.sectorEstado = 1"
            }
        });
    }


    function manMostrarAreas(inputOrg, inputSec, inputAr){

        var nombreOrg = ($(inputOrg).get('value'));
        var nombreSec = ($(inputSec).get('value'));

        do{
            nombreOrg = nombreOrg.replace("'","");
        }while(nombreOrg.indexOf("'") >= 0);


        do{
            nombreOrg = nombreOrg.replace('"','');
        }while(nombreOrg.indexOf('"') >= 0);

        do{
            nombreSec = nombreSec.replace("'","");
        }while(nombreSec.indexOf("'") >= 0);


        do{
            nombreSec = nombreSec.replace('"','');
        }while(nombreSec.indexOf('"') >= 0);

        new Autocompleter.Request.HTML($(inputAr), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading',
            'minLength': 1,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id' : 'areaId',
                'nombre_campo' : 'areaNombre',
                'nombre_tablas' :       'From Areas a (nolock) inner join Sectores s (nolock) on a.sectorId = s.sectorId inner join Organizaciones o (nolock) on a.organizacionId = o.organizacionId',
                'nombre_where'      :       "and s.clienteId = " + $('clienteId').get('value') + " and o.organizacionNombre like '" + nombreOrg + "%' and s.sectorNombre like '" + nombreSec + "%' and o.organizacionEstado = 1 and s.sectorEstado = 1 and a.areaEstado = 1"
            }
        });
    }
</script>