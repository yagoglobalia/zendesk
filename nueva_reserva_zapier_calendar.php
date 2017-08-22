<?php


//Recupero última fecha de reserva guardada en mysql

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
	
//echo $json." \n";	
	
//$row1 = json_decode($json);
//si sólo hay un pago (lo más probable de momento en un minuto), recupero correo y teléfono y nombre del cliente.


//$email = $row1['email'];
//$phone = $row1['phone'];
//$name = $row1['name'];
//$surnames = $row1['surnames'];
//$amount = $row1['amount'];
//$origin = $row1['origin'];
//$departure = $row1['departure'];
//$rumbo = $row1['rumbo'];
//$locator = $row1['locator'];
//if($rumbo == 'islands'){
//}else{
//$return = date('Y-m-d', strtotime($departure. ' + 3 days'));
//$return = date('Y-m-d', strtotime($departure. ' + 2 days'));//
//}
//$brand = 'travelbox';
//$type = 'reserva';
//echo $email;

$url = 'https://hooks.zapier.com/hooks/catch/1877049/tpkqoh/';

//$url = 'https://hooks.zapier.com/hooks/catch/1877049/tv5ggi/';

$curl = curl_init();


$opts = array(
    CURLOPT_URL             => $url,
    CURLOPT_RETURNTRANSFER  => true,
    CURLOPT_CUSTOMREQUEST   => 'POST',
    CURLOPT_POST            => 1,
    CURLOPT_POSTFIELDS      => $json,
    CURLOPT_HTTPHEADER  => array('Content-Type: application/json','Content-Length: ' . strlen($json))                                                                       
);


curl_setopt_array($curl, $opts);

    // Get the results
$result = curl_exec($curl);

    // Close resource
    curl_close($curl);


$sql = "UPDATE reservas SET new_calendar = 1 WHERE idreserva = ".$id;
if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}

sleep(2);

}
?>                                                                                                               

