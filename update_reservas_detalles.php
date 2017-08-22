<?php

echo "Update Detalles\n";


//actualización de detalles

$con = mysqli_connect("localhost", "root","238500","zendesk") or die('Could not connect: ' . mysqli_error($con));


$a = 0;
//

$qqq = "SELECT locata, json FROM reservas where email = '' or telefono = '' or fechaviaje = '' or creacion = '1980-01-01 00:00:00'";

$resultj=mysqli_query($con,$qqq);

if (!$resultj) { // add this check.
    die('Invalid query: ' . mysqli_error());
}

while($rowa=mysqli_fetch_assoc($resultj)){


$id3 = $rowa['locata'];	
	
$json = json_decode($rowa['json']);
$email = $json -> email;
$telefono = $json -> phone;
$fechaviaje = $json -> departure;
$pasta = $json -> amount;
$creacion = $json -> creation;

echo " --" .$id3."\n";

$sql = "UPDATE reservas SET email = '".$email."', telefono = '".$telefono."', fechaviaje = '".$fechaviaje."', creacion = '".$creacion."' WHERE locata = '".$id3."'";
if(!mysqli_query($con,$sql)){die('Error1 : ' .$sql.'  -  '. mysqli_error($con));}


$a++;

}



echo "Detalles actualizados:".$a."\n";

?>                                                                                                             

