<?php

require  'calendar/add_booking.php';

//Recupero último id de reserva guardada en mysql

$con = mysqli_connect("localhost", "root","238500","zendesk") or die('Could not connect: ' . mysqli_error($con));

$get = mysqli_query($con,"SELECT count(idreserva) as id FROM reservas where new_calendar != 1 and estado != 'TRAVELLER_INFO_LIMIT_PASSED'");
$got = mysqli_fetch_array($get);
$numero = $got['id'];



for($h = 0; $h<$numero; $h++)

{
	
$get = mysqli_query($con,"SELECT idreserva as id, json FROM reservas where new_calendar != 1 and estado != 'TRAVELLER_INFO_LIMIT_PASSED' limit 1");
$got = mysqli_fetch_array($get);
$id = $got['id'];	

$json = html_entity_decode($got['json']);
	

new_booking(1);


$sql = "UPDATE reservas SET new_calendar = 1 WHERE idreserva = ".$id;
if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}

sleep(2);

}
?>                                                                                                               

