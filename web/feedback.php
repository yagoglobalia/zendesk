<?php



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$locata = $_GET["locator"];





$con = mysqli_connect("localhost", "root","238500","zendesk") or die('Could not connect: ' . mysqli_error($con));

$quer = "select locata, estado, json, jsonassignment from reservas where locata = '".$locata."'";
$resulth=mysqli_query($con,$quer);

$rowa=mysqli_fetch_assoc($resulth);



$json = json_decode($rowa['json']);

$json2 = json_decode($rowa['jsonassignment']);


$sss = "

Hola ".ucfirst(strtolower($json->name))." ".
"
pitiflus pitiflas <br>

".
"tu viaje a ".$json2->destino."<br> ".

"
este enlace tan bonito
".
"<a href=\"https://es.trustpilot.com/evaluate/www.ulyseo.com\">TrustPilot</a>"
;

$sss = "


¡Hola ".ucfirst(strtolower($json->name))."!\n". 

"Esperamos que hayas disfrutado de tu viaje a ".$json2->destino.". Desde Ulyseo queremos agradecerte que hayas confiado en nosotros y, si no es mucho pedir, nos gustaría que nos dieras tu opinión para seguir creciendo y mejorando. Puedes hacerlo en el siguiente enlace.

https://es.trustpilot.com/evaluate/www.ulyseo.com

¡Hasta pronto!

";

//https://es.trustpilot.com/evaluate/www.ulyseo.com

$jj = "$(\"button\").click(function()\{$(\"textarea\").select();document.execCommand(\'copy\');\});";


echo "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js\"></script>";


echo "<script>";

echo "$(\"button\").click(function()";
echo "{";
echo "$(\"textarea\").select();document.execCommand('copy');";
echo "});";

echo "</script>";




echo 
//"<input type=\"text\" id=\"copyTarget1\" value=\"".$sss."\"> 
"<button id=\"copyButton\">Copy</button><br><br>".
//<input type=\"text\" placeholder=\"Click here and press Ctrl-V to see clipboard contents\">
"​<textarea id=\"copyTarget\" rows=\"20\" cols=\"70\">".$sss."</textarea>


";














?>