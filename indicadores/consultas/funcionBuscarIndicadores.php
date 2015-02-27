<?php

include ("../../librerias/conexion.php");
require('../clases/registro.class.php');

session_start();

$yyyy = $_POST['yyyy'];
$mm = $_POST['mm'];

$objReg = new Registro();

$resultLimites = $objReg->seleccionarLimites();

while ($rowdatos = mssql_fetch_array($resultLimites)) {
    $resultadoLimtes[] = $rowdatos;
}

$limite1 = $resultadoLimtes[1][2];
$limite2 = $resultadoLimtes[0][2];

$yyyyInicial = 2013;
$personalId = 0;

$indicadores = $objReg->seleccionarIndicadoresAnual($yyyy, $mm, $personalId, $usuarioNombre);

$html = "";

$yyyyActual = date("Y");
$mmActual = date("m");

$yyyyAnterior = 0;
$yyyySiguiente = 0;

$yyyyAnteriorb = "";
$yyyySiguienteb = "";

$mmAnterior = "";
$mmSiguiente = "";

$yyyyAnterior = $yyyy - 1;      // Año anterior
$yyyySiguiente = $yyyy + 1;     // Año posterior


if ($mm == "1") {
    $mmAnterior = "12";                 // Mes anterior
    $yyyyAnteriorb = $yyyyAnterior;     // Año correspondiente al mes anterior
} else {
    $mmAnterior = $mm - 1;              // Mes anterior
    $yyyyAnteriorb = $yyyy;             // Año correspondiente al mes anterior
}


if ($mm == "12") {
    $mmSiguiente = "1";                 // Mes posterior
    $yyyySiguienteb = $yyyySiguiente;   // Año correspondiente al mes posterior
} else {
    $mmSiguiente = $mm + 1;             // Mes posterior
    $yyyySiguienteb = $yyyy;            // Año correspondiente al mes posterior
}


$html = "<center><div id=\"navigation\" style=\"padding-bottom: 15px\"><table><tr>";
$casoActual = date("d-m-Y", strtotime($yyyy . '-' . $mm . '-01'));


$mesTope = date("d-m-Y", strtotime($yyyyInicial . '-01-01'));

if (strtotime($mesTope) < strtotime($casoActual)) {
    $html = $html . "<td width='22px'><img src=\"flechas/first.gif\" width=\"16\" height=\"16\" alt=\"Año Anterior\" onclick=\"buscarIndicadoresCAP(" . $yyyyAnterior . "," . $mm . ")\" /></td>";
} else {
    $html = $html . "<td width='22px'></td>";
}

$mesTope = date("d-m-Y", strtotime(($yyyyInicial - 1) . '-02-01'));


if (strtotime($mesTope) < strtotime($casoActual)) {
    $html = $html . "<td width='22px'><img src=\"flechas/previous.gif\" width=\"16\" height=\"16\" alt=\"Mes anterior\" onclick=\"buscarIndicadoresCAP(" . $yyyyAnteriorb . "," . $mmAnterior . ")\" /></td>";
} else {
    $html = $html . "<td width='22px'></td>";
}

$mesTope = date("d-m-Y", strtotime($yyyyActual . '-' . $mmActual . '-01'));

if (strtotime($casoActual) < strtotime($mesTope)) {
    $html = $html . "<td width='22px'><img src=\"flechas/next.gif\" width=\"16\" height=\"16\" alt=\"Mes siguiente\" onclick=\"buscarIndicadoresCAP(" . $yyyySiguienteb . "," . $mmSiguiente . ")\" /></td>";
} else {
    $html = $html . "<td width='22px'></td>";
}

$mesTope = date("d-m-Y", strtotime(($yyyyActual - 1) . '-' . $mmActual . '-01'));

if (strtotime($casoActual) <= strtotime($mesTope)) {
    $html = $html . "<td width='22px'><img src=\"flechas/last.gif\" width=\"16\" height=\"16\" alt=\"Año siguiente\" onclick=\"buscarIndicadoresCAP(" . $yyyySiguiente . "," . $mm . ")\" /></td>";
} else {
    $html = $html . "<td width='22px'></td>";
}

$html = $html . "</tr></table></div></center>";



$html = $html . "<table border='0' class='tabla'><tr><th class='indicadorCabecera' style=\"font-size:12px; width:310px\">Gerencia / Agencia Responsable</th>";


if($mm == 12){
    $mm = 1;    
}
else{
    $mm = $mm + 1;
    $yyyy = $yyyy - 1;
}

for ($i = 12; $i > 0; $i--) {
    $html = $html . "<th class='indicadorCabecera' width='58px'>" . retornarNombreMes($mm) . " <br> " . $yyyy . "</th>";

    if ($mm == "12") {
        $mm = "1";
        $yyyy = $yyyy + 1;
    } else {
        $mm = $mm + 1;
    }
}

$html = $html . "</tr>";




$atrasados = 0;
$total = 0;
$porcentaje = 0;
$result = "";
$clase = "";

if($indicadores){
    
    if(mssql_num_rows($indicadores) > 0){
        
        $resultado = array();
    
        while ($rowdatos = mssql_fetch_array($indicadores)) {
            $resultado[] = $rowdatos;
        }
        
        for($i = 0; $i < count($resultado); $i++){
                        
            if(trim($resultado[$i][1]) != "" && trim($resultado[$i][2]) == ""){
                $clase = "indicadorSGH";
                $html = $html . "<tr title=\"Ver Detalle " . htmlentities($resultado[$i][1]) . "\"><td class=\"indicadorSGH\" align=\"left\" onclick=\"abrirTr('CAP_" . $resultado[$i][0] . "')\" style='cursor:pointer'>" . htmlentities($resultado[$i][1]) . "</td>";                                                
            }
            else{
                $clase = "indicadorSGH_N2";
                $html = $html . "<tr class=\"valor_N2_CAP_" . $resultado[$i][0] . "\" style=\"display:none; background-color:#FFFFFF\"><td class=\"indicadorSGH_N2\" align=\"left\">" . htmlentities($resultado[$i][2]) . "</td>";
            }
            
            for ($j = 0; $j < 23; $j++)
            {
                $atrasados = $resultado[$i][$j + 4];
                $total = $resultado[$i][$j + 3];

                if ($total != 0)
                {
                    $porcentaje =  number_format((($atrasados / $total)) * 100, 2);
                    $result = $porcentaje . "%";
                }
                else
                {
                    $porcentaje = -1;
                    $result = " ";
                }

                if ($result != " ")
                {
                    if ($porcentaje < $limite1 && $porcentaje != -1)
                    {
                        if ($tipo == "A")
                        {
                            $html = $html . "<td class=\"" . $clase . "\" align=\"right\" style=\"color: RED;\">" . $result . "</td>";
                        }
                        else
                        {
                            $html = $html . "<td class=\"" . $clase . "\" align=\"right\" style=\"color: RED\">" . $result . "</td>";
                        }
                    }
                    else if ($limite1 <= $porcentaje && $porcentaje < $limite2)
                    {
                        if ($tipo == "A")
                        {
                            $html = $html . "<td class=\"" . $clase . "\" align=\"right\" style=\"color:#BABE28;\">" . $result . "</td>";
                        }
                        else
                        {
                            $html = $html . "<td class=\"" . $clase . "\" align=\"right\" style=\"color:#BABE28;\">" . $result . "</td>";
                        }
                    }
                    else if ($porcentaje >= $limite2)
                    {
                        if ($tipo == "A")
                        {
                            $html = $html . "<td class=\"" . $clase . "\" align=\"right\" style=\"color:GREEN\">" . $result . "</td>";
                        }
                        else
                        {
                            $html = $html . "<td class=\"" . $clase . "\" align=\"right\" style=\"color:GREEN\">" . $result . "</td>";
                        }
                    }

                }
                else
                {
                    $html = $html . "<td class=\"" . $clase . "\" align=\"right\">" . $result . "</td>";
                }
                $j++;
            }
            
            
            
            $html = $html . "</tr>";
                                      
        }
                
    }
    else{
        $html = $html . "<tr><td colspan='14' style='color: RED; text-align: left'>No existen indicadores para el año seleccionado.</td></tr>";
        //$html = $html . "<tr><td colspan='14' style='color: RED; text-align: left'><br> </td></tr>";
    }

    $html = $html . "</table>";
    
}

//            if (dt_IndicadorSGH.Rows.Count > 0)
//            {
//
//                for (int i = 0; i < dt_IndicadorSGH.Rows.Count; i++)
//                {
//                    if (dt_IndicadorSGH.Rows[i]["empresa_nombre"].ToString().Trim() == "")
//                    {
//                        clase = "indicadorSGH";
//                        html = html + "<tr title=\"Ver Detalle " + dt_IndicadorSGH.Rows[i]["sector_nombre"].ToString().Trim() + "\"><td class=\"indicadorSGH\" align=\"left\" onclick=\"abrirTr('CAP_" + dt_IndicadorSGH.Rows[i]["sector_id"].ToString().Trim() + "')\">" + dt_IndicadorSGH.Rows[i]["sector_nombre"].ToString().Trim() + "</td>";
//                    }
//                    else
//                    {
//                        clase = "indicadorSGH_N2";
//                        html = html + "<tr class=\"valor_N2_CAP_" + dt_IndicadorSGH.Rows[i]["sector_id"].ToString().Trim() + "\" style=\"display: none\"><td class=\"indicadorSGH_N2\" align=\"left\">" + dt_IndicadorSGH.Rows[i]["empresa_nombre"].ToString().Trim() + "</td>";
//                    }
//
//                    for (int j = 0; j < 23; j++)
//                    {
//                        atrasados = Convert.ToDecimal(dt_IndicadorSGH.Rows[i][j + 4]);
//                        total = Convert.ToDecimal(dt_IndicadorSGH.Rows[i][j + 3]);
//
//                        if (total != 0)
//                        {
//                            porcentaje = ((atrasados / total)) * 100;
//                            resultado = String.Format("{0:0}", porcentaje) + "%";
//                        }
//                        else
//                        {
//                            resultado = " ";
//                        }
//
//                        if (resultado != " ")
//                        {
//                            if (Convert.ToInt32(String.Format("{0:0}", porcentaje)) < limite1)
//                            {
//                                if (tipo == "A")
//                                {
//                                    html = html + "<td class=\"" + clase + "\" align=\"right\" style=\"Color: RED;\">" + resultado.ToString().Trim() + "</td>";
//                                }
//                                else
//                                {
//                                    html = html + "<td class=\"" + clase + "\" align=\"right\" style=\"background-color: RED; color:#FFFFFF\">" + resultado.ToString().Trim() + "</td>";
//                                }
//                            }
//                            else if (limite1 <= Convert.ToInt32(String.Format("{0:0}", porcentaje)) && Convert.ToInt32(String.Format("{0:0}", porcentaje)) < limite2)
//                            {
//                                if (tipo == "A")
//                                {
//                                    html = html + "<td class=\"" + clase + "\" align=\"right\" style=\"color:Yellow\">" + resultado.ToString().Trim() + "</td>";
//                                }
//                                else
//                                {
//                                    html = html + "<td class=\"" + clase + "\" align=\"right\" style=\"background-color: Yellow; color:#000000\">" + resultado.ToString().Trim() + "</td>";
//                                }
//                            }
//                            else if (Convert.ToInt32(String.Format("{0:0}", porcentaje)) >= limite2)
//                            {
//                                if (tipo == "A")
//                                {
//                                    html = html + "<td class=\"" + clase + "\" align=\"right\" style=\"color: GREEN\">" + resultado.ToString().Trim() + "</td>";
//                                }
//                                else
//                                {
//                                    html = html + "<td class=\"" + clase + "\" align=\"right\" style=\"background-color: GREEN; color:#FFFFFF\">" + resultado.ToString().Trim() + "</td>";
//                                }
//                            }
//
//                        }
//                        else
//                        {
//                            html = html + "<td class=\"" + clase + "\" align=\"right\">" + resultado.ToString().Trim() + "</td>";
//                        }
//                        j++;
//                    }
//
//                    html = html + "</tr>";
//
//                }
//            }

//        }


echo $html;

function retornarNombreMes($mm) {

    $nombreMes = "";

    switch ($mm) {

        case '1':
            $nombreMes = "ENE";
            break;

        case '2':
            $nombreMes = "FEB";
            break;

        case '3':
            $nombreMes = "MAR";
            break;

        case '4':
            $nombreMes = "ABR";
            break;

        case '5':
            $nombreMes = "MAY";
            break;

        case '6':
            $nombreMes = "JUN";
            break;

        case '7':
            $nombreMes = "JUL";
            break;

        case '8':
            $nombreMes = "AGO";
            break;

        case '9':
            $nombreMes = "SEP";
            break;

        case '10':
            $nombreMes = "OCT";
            break;

        case '11':
            $nombreMes = "NOV";
            break;

        case '12':
            $nombreMes = "DIC";
            break;
    }

    return $nombreMes;
}
