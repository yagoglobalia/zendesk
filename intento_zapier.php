<?php


//Recupero última fecha de fallo guardada en mysql

$con = mysqli_connect("localhost", "root","238500","zendesk") or die('Could not connect: ' . mysqli_error($con));

$get = mysqli_query($con,"SELECT valortimestamp FROM datos_consolidados where clave = 'ultimointento'");
$got = mysqli_fetch_array($get);
$codigo = $got['valortimestamp'];

//me conecto a postgres

$username = 'tbox_bi'; 
$password = 'X32ctN'; 
$host = 'tbox-be-db.ulyseo.com'; 
$db = 'tbox_backend'; 

$db_handle = pg_connect("dbname=$db user=$username password=$password host=$host");

//recupero los ids de pago fallidos con creación mayor de la última guardada.

$query = "SELECT payment_id FROM tbox_schema.payment where payment_state = 'FAIL' and creation_time > '".$codigo."'";

$result = pg_exec($db_handle, $query); 

//los guardo en row

$row = pg_numrows($result);        


//echo $row;



//si row es mayor de cero hago varias cosas

//$row = 0;

//echo $row;

if($row > 0){
	
//recupero el timestamp del último pago fallido.

$query = "SELECT max(creation_time) as pm FROM tbox_schema.payment where payment_state = 'FAIL'";

$result = pg_query($query);
$row1 = pg_fetch_assoc($result);
$lasttimestamp = $row1['pm'];



$lasttimestamp = date("Y-m-d H:i:s",strtotime($lasttimestamp));
//le añado un segundo.
$lasttimestamp++;
	
// actualizo la tabla de mysql con el último timestamp + un segundo.	
	


//si sólo hay un pago (lo más probable de momento en un minuto), recupero correo y teléfono y nombre del cliente.

if($row == 1){
	
	
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


//echo $email;

$curl = curl_init();

//https://hooks.zapier.com/hooks/catch/1877049/t0kqb3/

$url = "https://hooks.zapier.com/hooks/catch/1877049/t0kqb3/";

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
    
//https://hooks.zapier.com/hooks/catch/1877049/t0z01d/    
    
$url = "https://hooks.zapier.com/hooks/catch/1877049/t0z01d/";    
  
  
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
    $result = curl_exec($curl);

    // Close resource
    curl_close($curl);  


$sql = "UPDATE datos_consolidados set valortimestamp = '".$lasttimestamp."' where clave = 'ultimointento'";
if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}
}



pg_close($db_handle);
?>                                                                                                               

