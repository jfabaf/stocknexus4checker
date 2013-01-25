<?php

/***** VARIABLES ****/
$file8 = "https://play.google.com/store/devices/details?id=nexus_4_8gb";
$file16 = "https://play.google.com/store/devices/details?id=nexus_4_16gb";
$msg8 = "HAY NEXUS 4 DE 8 GB";
$msg16 = "HAY NEXUS 4 DE 16 GB";
$msgno = "No hay Nexus 4 ni de 8 ni de 16 GB :-(";
$buscar = "Agotado";
$emails8 = array("email1@domain1.com", "email2@domain2.com");
$telefonos8 = array("600123456", "601123456");
$emails16 = array("email3@domain3.com", "email4@domain4.com");
$telefonos16 = array("600123456", "601123456");
$emailfrom = "admin@domain.com";
$userLleida = "userIdLleida";
$passLleida = "passIdLleida";
$filehay8 = "/path/to/hay8";
$filehay16 = "/path/to/hay16";
/****** FIN VARIABLES *****/

/****** main() ******/


$nohay8 = comprobarStock($file8,$filehay8);
$nohay16 = comprobarStock($file16,$filehay16);

if ($nohay8 === false) {
	echo $msg8 . "\n";
	enviar_email($emails8,$msg8);
	enviar_sms($telefonos8,$msg8);
	crearFicheroHay($filehay8);
}

if ($nohay16 === false) {
	echo $msg16 . "\n";
	enviar_email($emails16,$msg16);
	enviar_sms($telefonos16,$msg16);
	crearFicheroHay($filehay16);
}

if ($nohay8 !== false && $nohay16 !== false) {
	echo $msgno . "\n";
}


/********** fin main ********/


/******** funciones *******/

function enviar_email($emails, $txt)
{
 	global $emailfrom; 
	foreach ($emails as $email) {
    	$para      = $email;
		$cabeceras = 'From: ' . $emailfrom . "\r\n" .
    	'Reply-To: ' . $emailfrom . "\r\n" .
    	'Return-Path: ' . $emailfrom . "\r\n" .
    	'X-Mailer: PHP/' . phpversion();
		mail($para, $txt, $txt, $cabeceras);
	}

 
	
}

function enviar_sms($telefonos,$txt)
{
    global $userLleida, $passLleida;
	$nums = "";
	foreach ($telefonos as $sms) {
    	$nums .=  "<num>+34" . $sms . "</num>\n";
    }

    	$xml = 	"<sms>\n".
				"<user>" . $userLleida . "</user>\n".
				"<password>" . $passLleida . "</password>\n".
				"<dst>".
				$nums .
				"</dst>".
				"<txt>" . $txt . "</txt>".
				"</sms>";

    	$para      = "xmlsms@sms.lleida.net";	
		mail($para, "", $xml);
}



function comprobarHay($filehay)
{
	return file_exists($filehay);
}

function comprobarStock($file, $filehay) 
{
	global $buscar;
	$nohay = true;
	if (comprobarHay($filehay)) 
	{
		echo "Ya se ha avisado del stock.\r\nEl fichero " . $filehay . " ya existe.\r\n";
	}
	else
	{
		$gestor = fopen($file, "r") or die("Imposible conectarse a internet\r\n");
		$contenido = stream_get_contents($gestor);
		fclose($gestor);
		$nohay = stripos($contenido, $buscar);

	}
	return $nohay;
}

function crearFicheroHay($filehay)
{
	$gestor = fopen($filehay, "w");
	if (fwrite($gestor, "STOCK") === FALSE) {
		echo "No se puede crear el archivo " . $filehay;
	}
	fclose($gestor);
}

/********** fin funciones ********/

?>