<?php

//include("master.php");

//notificar cada vez que hay una reserva

//crear usuario cada vez que hay reserva

$grupo = 2;
//recupero número de reservas

$username = 'tbox_bi'; 
$password = 'X32ctN'; 
$host = 'tbox-be-db.ulyseo.com'; 
$db = 'tbox_backend'; 


$db_handle = pg_connect("dbname=$db user=$username password=$password host=$host");



$query = "SELECT reservation_id FROM tbox_schema.reservation";

//$query = "SELECT table_name FROM information_schema.tables WHERE table_schema='public'";


$result = pg_exec($db_handle, $query); 


$row = pg_numrows($result);        


pg_close($db_handle);

//recupero numero almacenado

$con = mysqli_connect("localhost", "root","238500","zendesk") or die('Could not connect: ' . mysqli_error($con));

$get = mysqli_query($con,"SELECT valorint FROM datos_consolidados where clave = 'numreservas'");
$got = mysqli_fetch_array($get);
$codigo = $got['valorint'];



$aa = $row - $codigo;

if($grupo == 0){
$device ="sm-g925f,raquel,antxon,yago,yagogbl";
}elseif($grupo == 1){
$device ="raquel,yago,yagogbl";
}else{
$device ="yago";
}
//actualizo

$sql = "UPDATE datos_consolidados set valorint = ".$row." where clave = 'numreservas'";
if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}


if($aa != 0){
if($aa == 1){$bb = "¡Reserva nueva!";}else{$bb = "¡Reservas nuevas!";}

$curl = curl_init();

$url = "https://hooks.zapier.com/hooks/catch/1877049/tcgw10/";

$data = array('guid' => "hola", 'test' => "adios");
$jsonEncodedData = json_encode($data);
$opts = array(
    CURLOPT_URL             => $url,
    CURLOPT_RETURNTRANSFER  => true,
    CURLOPT_CUSTOMREQUEST   => 'POST',
    CURLOPT_POST            => 1,
    CURLOPT_POSTFIELDS      => $jsonEncodedData,
    CURLOPT_HTTPHEADER  => array('Content-Type: application/json','Content-Length: ' . strlen($jsonEncodedData))                                                                       
);


curl_setopt_array($curl, $opts);

    // Get the results
    $result = curl_exec($curl);

    // Close resource
    curl_close($curl);

}
?>                                                                                                               

