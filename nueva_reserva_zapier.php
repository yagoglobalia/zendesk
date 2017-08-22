<?php


//Recupero última fecha de reserva guardada en mysql

$con = mysqli_connect("localhost", "root","238500","zendesk") or die('Could not connect: ' . mysqli_error($con));

$get = mysqli_query($con,"SELECT valortimestamp FROM datos_consolidados where clave = 'ultimareserva'");
$got = mysqli_fetch_array($get);
$codigo = $got['valortimestamp'];

//me conecto a postgres

$username = 'tbox_bi'; 
$password = 'X32ctN'; 
$host = 'tbox-be-db.ulyseo.com'; 
$db = 'tbox_backend'; 

$db_handle = pg_connect("dbname=$db user=$username password=$password host=$host");

//recupero los ids de reservas con creación mayor de la última guardada.

$query = "SELECT count(reservation_id) as idcount FROM tbox_schema.reservation where creation_time > '".$codigo."'";

//echo $query;

$result = pg_exec($db_handle, $query); 

//los guardo en row

$row1 = pg_fetch_assoc($result);
$row = $row1['idcount'];








//si row es mayor de cero hago varias cosas

//$row = 0;



if($row > 0){
	
//recupero el timestamp del último pago fallido.

$query = "SELECT max(creation_time) as pm FROM tbox_schema.reservation";

$result = pg_query($query);
$row1 = pg_fetch_assoc($result);
$lasttimestamp = $row1['pm'];

$lasttimestamp = strtotime($lasttimestamp);

$lasttimestamp++;

$lasttimestamp = date("Y-m-d H:i:s",$lasttimestamp);
//le añado un segundo.

	
	
	

//si sólo hay un pago (lo más probable de momento en un minuto), recupero correo y teléfono y nombre del cliente.

if($row == 1){
	
	
$query = "
select r.amount as amount, r.locator as locator, r.customer_id, c.email as email, c.phone as phone, c.name as name
, c.surnames as surnames, r.origin_code as origin, r.departure as departure, gc.name as rumbo
from tbox_schema.reservation r 
inner join tbox_schema.customer c on r.customer_id = c.customer_id 
inner join tbox_schema.getaway_category gc on gc.category_id = r.getaway_category_id
where r.creation_time > '".$codigo."'";


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
$locator = $row1['locator'];

//echo $email;

$curl = curl_init();

//https://hooks.zapier.com/hooks/catch/1877049/t0nnog/

$url = "https://hooks.zapier.com/hooks/catch/1877049/t0nnog/";


$data = array(
'email' => $email
, 'phone' => $phone
, 'fullname' => $name." ".$surnames
, 'name' => $name
, 'surnames' => $surnames
, 'amount' => $amount
, 'origin' => $origin
, 'departure' => $departure
, 'rumbo' => $rumbo
, 'locata' => $locator

);
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
//notifico intento fallido (sea uno o varios) 
    
$curl = curl_init();    

//https://hooks.zapier.com/hooks/catch/1877049/tcgw10/
    
$url = "https://hooks.zapier.com/hooks/catch/1877049/tcgw10/";
  
//$url = "s";  
  
$data = array('pushover' => 'True');
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
    //$result = curl_exec($curl);

    // Close resource
    curl_close($curl);  

//echo " sss- ".$lasttimestamp."\n";

$sql = "UPDATE datos_consolidados set valortimestamp = '".$lasttimestamp."' where clave = 'ultimareserva'";
	if(!mysqli_query($con,$sql)){die('Error hola : ' . mysqli_error($con));}
}



pg_close($db_handle);
?>                                                                                                               

