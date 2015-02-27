<?php
session_start();

include("../default.php");

ini_set("memory_limit", "600M");

setlocale(LC_TIME, "spanish");
$fechaActual = htmlentities(strftime("%A %d de %B del %Y"));
$fechaActual = ucfirst($fechaActual);

$nusuario = $_SESSION['usuario'];
$plantilla->setPath('../skins/saam/plantillas/');
$plantilla->setTemplate("header_2");
$plantilla->setVars(array("USUARIO" => " $nusuario ",
    "FECHA" => "$fechaActual"));
echo $plantilla->show();


$nusuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] : 0;
?>
<script type="text/javascript" src="../scripts/overlay.js"></script>
<script type="text/javascript" src="../scripts/multiBox.js"></script>
<script type="text/javascript" src="../scripts/tablesort.js"></script>
<link type="text/css" rel="stylesheet" href="../skins/saam/plantillas/multiBox.css" />
<div>
<?
$usuario_id = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';

if ($usuario_id == '') {
    echo "<script>window.location='../index.php';</script>";
    ?>
        <?
    } else {


        if ($_SESSION['perfilId'] == 1) {
            $menu1 = "display:block;";
        } else {
            $menu1 = "display:none;";
        }

        require('../planificaciones/clases/planificaciones.class.php');
        $objPlanificacion = new planificaciones();

        $consulta = $objPlanificacion->planBuscarCliente($cliente_id);
        $cliente = mssql_fetch_array($consulta);
        $consulta = $objPlanificacion->planBuscarAdministrador($administradorId);
        $administrador = mssql_fetch_array($consulta);

        $cnombre = htmlentities($cliente['clienteNombreCompleto']);
        $anombre = htmlentities($administrador['administradorNombreCompleto']);

        $mes = date("F");
        $mes1 = date("n");
        switch ($mes) {
            case "January":
                $mes = "Enero";
                break;
            case "February":
                $mes = "Febrero";
                break;
            case "March":
                $mes = "Marzo";
                break;
            case "April":
                $mes = "Abril";
                break;
            case "May":
                $mes = "Mayo";
                break;
            case "June":
                $mes = "Junio";
                break;
            case "July":
                $mes = "Julio";
                break;
            case "August":
                $mes = "Agosto";
                break;
            case "September":
                $mes = "Setiembre";
                break;
            case "October":
                $mes = "Octubre";
                break;
            case "November":
                $mes = "Noviembre";
                break;
            case "December":
                $mes = "Diciembre";
                break;
        }

        $mesactual = $mes;

        $plantilla->setTemplate("PlanificarInformes");
        $fecha = date("d-m-Y");
        $plantilla->setVars(array("USUARIO" => "$usuario_id",
            "FECHA" => "$fecha",
            "MANT" => "$menu1",
            "CLIENTENOMBRE" => $cnombre,
            "ADMIN" => $anombre,
            "CLIENTE" => $cliente_id,
            "MESACTUAL" => $mesactual,
            "MESACTUALNUMERO" => $mes1
        ));

        echo $plantilla->show();
        include("FuncionListarFechasInforme.php")
        ?>
    </tbody>
    </table> 

    </div>
    <br>

    <a id="aDescartarEnvios" onclick="descartarEnvios()" style="cursor:pointer; font-size: 11px;">Descartar Envios</a>
    <input id="FilasEliminadas" type="hidden"/>
    </div>
    </div>
    </td>
    </tr>
    </table>
    <br>                                                                
    <br>
    </div>
    <input type="button" value="Guardar" class="btn azul" name="button" onclick="ValidaGuardar()">
    <input id="btnCancelar" type="button" name="btnCancelar" class="btn" value="Cancelar" onclick="location.href = '../reportes/adm_reportes.php'"/>

    </div>

<? } ?>
<script type="text/javascript">
                                                                                                    
    window.addEvent('domready', function() {    
        $('mBoxContainerFechaInfo').setStyle('opacity', '0'); 
        //ListarContactos();
    });
  
    function IngresarFechaEnvio(){
        $('calFecha').value='';
        var property = 'opacity';
        var to = "1";
        $('mBoxContainerFechaInfo').tween(property, to);
    }    
    function SeleccionarFechaEnvio(){
        validarFecha($('calFecha'));
    
        if($('calFecha').value!=""){
            AgregarFecha($('mesactual1').value,$('calFecha').value,$('mesactual2').value);
            $('mBoxContainerFechaInfo').tween("opacity", 0);   
        }
    }    

    function AgregarFecha(mesActual,fecha,mesNumero){
        var tabla=$('listaMeses');
        var filas = tabla.rows.length;

        total = 0;
        uf = 1; //ultima fila
        filas=filas+1;
        objInput1 = mesActual;
                 
        x = tabla.insertRow(-1);
                 
        var y = x.insertCell(0);
        y.innerHTML = "<div style='width:46px;text-align:center'><input id='" + filas + "' type='checkbox'/><input type='hidden' id='idFila' value='0'/><input type='hidden' id='mesFila' value='"+mesNumero+"'/></div>";

        var y1 = x.insertCell(1);
        y1.innerHTML = "<div style='width:138px'>"+objInput1+"</div>";
    
        var y2 = x.insertCell(2);
        y2.innerHTML = "<div style='width:182px;text-align:center'><input id='cal-" + filas + "' type='text' style='width:70px; text-align:right' readonly='readonly' value='"+fecha+"'/><button id='btn-" + filas + "' class='btn invisible'><img src='../skins/saam/img/calendar.png'/></button></div>";
        

            
                       
        var myCal1 = Calendar.setup({
            inputField: "cal-" + filas + "",
            trigger: "btn-" + filas + "",
            onSelect: function () {
                this.hide();
            },
            showTime: 12,
            dateFormat: "%d-%m-%Y"
        });
        myCal1.setLanguage('es');                                                          
        
        
        filas = filas + 1;

        tabla = $('listaMeses');
        filas = tabla.rows.length;

        if(filas > 1)
        {             
            $('aDescartarEnvios').setStyle('display', '');
            $('listaMeses').setStyle('display', '');
        } 
    
    }

    
    function validarFecha(campofecha)
    {
        $('MalaFecha').setStyle('visibility','hidden');
        $('NoFecha').setStyle('visibility','hidden');
        var getdate = new Date();
        var dia = "00" + getdate.getDate();
        var mes = "00" + (getdate.getMonth() +1).toString();
        var yea = getdate.getFullYear();            

        var fechaActual = dia.substring(dia.length -2, dia.length) + "-" + mes.substring(mes.length -2, mes.length) + "-" + yea;
    
        if(campofecha.value != "")
        {                                                                                                          
            if(CompararFechas(fechaActual, campofecha.value)){
                $('MalaFecha').setStyle('visibility','visible');
                campofecha.value = "";                                       
            }
        }else{
            $('NoFecha').setStyle('visibility','visible');
        }   
            
    }

    function CompararFechas(fecha, fecha2){   
        var xMonth=fecha.substring(3, 5);  
        var xDay=fecha.substring(0, 2);  
        var xYear=fecha.substring(6,10);  
        var yMonth=fecha2.substring(3, 5);  
        var yDay=fecha2.substring(0, 2);  
        var yYear=fecha2.substring(6,10);  
        if (xYear> yYear)  
        {  
            return(true)  
        }  
        else  
        {  
            if (xYear == yYear)  
            {   
                if (xMonth> yMonth)  
                {  
                    return(true)  
                }  
                else  
                {   
                    if (xMonth == yMonth)  
                    {  
                        if (xDay>= yDay)  
                            return(true);  
                        else  
                            return(false);  
                    }  
                    else  
                        return(false);  
                }  
            }  
            else  
                return(false);  
        }  
    }        
    function SeleccionarTodo(valor, tabla){
        tabla=$(tabla);
        largo=tabla.rows.length;    
        for (i=0;i<largo;i++){
        
            if(tabla.rows[i].cells[0] != null)
            {
                tabla.rows[i].cells[0].getElementsByTagName('input')[0].checked = valor;
            }
        }
    }

    function descartarEnvios(){
        var tabla=$('listaMeses');
        var rowCount=tabla.rows.length;   
        for(var i=0; i<rowCount; i++) {
            var row = tabla.rows[i];
            var chkbox = row.cells[0].getElementsByTagName('input')[0];
            if(null != chkbox && true == chkbox.checked) {
                var idFila=row.cells[0].getElementsByTagName('input')[1].value;
                //alert(idFila);
                tabla.deleteRow(i);
                rowCount--;
                i--;
                if(idFila!='0'){
                    $('FilasEliminadas').value=$('FilasEliminadas').value+idFila+',';
                }
            }

        }
        tabla = $('listaMeses');
        filas = tabla.rows.length;

        if(filas > 1)
        {             
            $('aDescartarEnvios').setStyle('display', '');
            $('listaMeses').setStyle('display', '');
        } 
    }


    function ValidaGuardar(){
        $('oListaFechas').setStyle('display', 'none');
        var tabla=$('listaMeses');
        var filas=tabla.rows.length;
        if(filas==0){
            $('oListaFechas').setStyle('display', '');
        }else{
            for (i=0;i<filas;i++){
        
                if(tabla.rows[i].cells[0] != null)
                {
                    var row = tabla.rows[i];
                    var idFila=row.cells[0].getElementsByTagName('input')[1].value;
                    var mesFila=row.cells[0].getElementsByTagName('input')[2].value;
                    var fechaFila=row.cells[2].getElementsByTagName('input')[0].value;
                    //alert('idFila='+idFila+'&mesFila='+mesFila+'&fechaFila='+fechaFila);
                    guardarContactos(idFila,mesFila,fechaFila);
                }
            }
        
            if($('FilasEliminadas').value!=''){
                EliminarFilasBDD($('FilasEliminadas').value);
            }
        
            alert('La Información se ha guardado exitosamente');
            ListarContactos();
        }
    }

    function guardarContactos(idFila,mesFila,fechaFila){
        var elRequest = new Request({
            url         : 'FuncionMantFechasInforme.php',
            method      : 'post',
            async       : false,
        
            onSuccess   : function() {
                //
            },
            onFailure: function() {
                alert("Se ha producido un error. Por favor, inténtelo más tarde.");
            }
        });
    
        elRequest.send('idFila='+idFila+'&mesFila='+mesFila+'&fechaFila='+fechaFila);
    }

    function EliminarFilasBDD(lista){
        var elRequest = new Request({
            url         : 'FuncionLimpiarFechasInforme.php',
            method      : 'post',
            async       : false,
        
            onSuccess   : function(html) {
                $('MesesInforme').set('html',html);
            },
            onFailure: function() {
                alert("Se ha producido un error. Por favor, inténtelo más tarde.");
            }
        });
    
        elRequest.send("lista="+lista);

    }

    function ListarContactos(){
        location.href = '../reportes/adm_reportes.php'
        //location.href = 'PlanificarInformes.php'
    }

</script>

<?
$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>
