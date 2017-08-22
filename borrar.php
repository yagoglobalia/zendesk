<?php


$con = mysqli_connect("localhost", "root","238500","zendesk") or die('Could not connect: ' . mysqli_error($con));


$sql = "DELETE FROM reservas";
	if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}

$sql = "UPDATE datos_consolidados set valorint = 1 where clave = 'ultimareserva2'";
if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}


mysqli_close($con);	
?>                                                                                                               

