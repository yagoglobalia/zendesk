<?php

require('/home/ubuntu/zendesk/comunes.php');

//notificar cada vez que hay una reserva

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

//echo $row;

$aa = $row - $codigo;


if($aa > 0){

send_pushover(0, '¡Nueva Reserva!', 'TravelBox');

$sql = "UPDATE datos_consolidados set valorint = ".$row." where clave = 'numreservas'";
if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}


}

?>                                                                                                               

