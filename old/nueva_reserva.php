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
if($aa == 1){$bb = "¡Reserva nueva!";


$query = "
select p.reference, p.payment_state, p.amount as amount, pr.locator, pr.customer_id, c.email as email, c.phone as phone, c.name as name
, c.surnames as surnames, pr.origin_code as origin, pr.departure as departure, gc.name as rumbo
from tbox_schema.payment p inner join tbox_schema.potential_reservation pr on p.reference = pr.reference 
inner join tbox_schema.customer c on pr.customer_id = c.customer_id 
inner join tbox_schema.getaway_category gc on gc.category_id = pr.getaway_category_id
where p.payment_state = 'FAIL' 
and p.creation_time > '".$codigo."'";


$result = pg_query($query);
$row1 = pg_fetch_assoc($result);
$email = $row1['email'];
$phone = $row1['phone'];
$name = $row1['name'];
$surnames = $row1['surnames'];
$amount = $row1['amount'];
$origin = $row1['origin'];
$departure = $row1['departure'];
$rumbo = $row1['rumbo'];


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


}else{$bb = "¡Reservas nuevas!";}


}

pg_close($db_handle);
?>                                                                                                               

