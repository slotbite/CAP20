<?

require_once 'phpmailer/class.phpmailer.php';

function enviarCorreo($correoDe,$correoDeNombre,$correoA,$correoSubject,$mensaje){
$resultadoEnvio=0;
$ServidorSMTP="smtp.googlemail.com";
$puertoCorreo=465;
$seguridad="SSL";
$usuarioCorreo="pruebasti@brains.cl";
$passwordCorreo="olazo201406"; 

$mail = new PHPMailer();      
    $mail->IsHTML();
    $body = $mensaje;

    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = $ServidorSMTP; // SMTP server
    $mail->SMTPDebug = 1;                     // enables SMTP debug information (for testing)
    // 1 = errors and messages
    // 2 = messages only
    if ($seguridad == "SSL") {        
        $mail->SMTPAuth = true;                  // enable SMTP authentication
        $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
        $mail->Host = $ServidorSMTP;      // sets GMAIL as the SMTP server
        $mail->Port = $puertoCorreo;                 // set the SMTP port for the GMAIL server
        $mail->Username = $usuarioCorreo;  // GMAIL username
        $mail->Password = $passwordCorreo;            // GMAIL password
    }

$mail->SetFrom($correoDe, $correoDeNombre);
$mail->Subject    = $correoSubject;
//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
$mail->MsgHTML($body);
$address = $correoA;
$mail->AddAddress($address, "");

if(!$mail->Send()) {
  $resultadoEnvio = $mail->ErrorInfo;
  //$resultadoEnvio="";
} else {
  //$resultadoEnvio= "Message sent!";
  $resultadoEnvio = "";
}
return($resultadoEnvio);

}




function enviarCorreoInformes($correoDe,$correoDeNombre,$correoA,$correoSubject,$mensaje,$rutaAttach){
$resultadoEnvio=0;
$ServidorSMTP = "smtp.googlemail.com";
$puertoCorreo = 465;
$seguridad = "SSL";
$usuarioCorreo = "pruebasti@brains.cl";
$passwordCorreo = "olazo201406";
	
    $mail = new PHPMailer();      
    $mail->IsHTML();
    $body = $mensaje;

    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = $ServidorSMTP; // SMTP server
    $mail->SMTPDebug = 1;                     // enables SMTP debug information (for testing)
    // 1 = errors and messages
    // 2 = messages only
    if ($seguridad == "SSL") {        
        $mail->SMTPAuth = true;                  // enable SMTP authentication
        $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
        $mail->Host = $ServidorSMTP;      // sets GMAIL as the SMTP server
        $mail->Port = $puertoCorreo;                 // set the SMTP port for the GMAIL server
        $mail->Username = $usuarioCorreo;  // GMAIL username
        $mail->Password = $passwordCorreo;            // GMAIL password
    }

$mail->SetFrom($correoDe, $correoDeNombre);
$mail->Subject    = $correoSubject;
//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
$mail->MsgHTML($body);

$address = $correoA;

//$correoA = '';

$lista=explode(',',$correoA);
$largo = sizeof($lista)-1;

for($i=0;$i<=$largo;$i++){
    $mail->AddAddress($lista[$i], "");
}

//Se agrega como copia oculta al Equipo GestiÃ³n:
//$mail->AddBCC('xhunter.2501@gmail.com');
//$mail->AddBCC($correoDe);

$mail->AddAttachment($rutaAttach);
if(!$mail->Send()) {
  $resultadoEnvio="Mailer Error: " . $mail->ErrorInfo;
  //$resultadoEnvio=0;
} else {
  $resultadoEnvio= "Message sent!";
  //$resultadoEnvio=1;
}
return($resultadoEnvio);

}



?>