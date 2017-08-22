<?php


//Recupero última fecha de fallo guardada en mysql

$con = mysqli_connect("localhost", "root","238500","zendesk") or die('Could not connect: ' . mysqli_error($con));

$get = mysqli_query($con,"SELECT valortimestamp FROM datos_consolidados where clave = 'ultimomensaje'");
$got = mysqli_fetch_array($get);
$codigo = $got['valortimestamp'];

//me conecto a postgres

$username = 'tbox_bi'; 
$password = 'X32ctN'; 
$host = 'tbox-be-db.ulyseo.com'; 
$db = 'tbox_backend'; 

$db_handle = pg_connect("dbname=$db user=$username password=$password host=$host");

//recupero los ids de mensajes con creación mayor de la última guardada.

$query = "SELECT contact_request_id FROM tbox_schema.contact_request where creation_time > '".$codigo."'";

$result = pg_exec($db_handle, $query); 

//los guardo en row

$row = pg_numrows($result);        


//echo $row;



//si row es mayor de cero hago varias cosas

//$row = 0;

//echo $row;

if($row > 0){
	
//recupero el timestamp del último mensaje.

$query = "SELECT max(creation_time) as pm FROM tbox_schema.contact_request";

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
select cr.name as name, cr.email as email, cr.message as message
from tbox_schema.contact_request cr
where cr.creation_time > '".$codigo."'";


$result = pg_query($query);
$row1 = pg_fetch_assoc($result);
$email = $row1['email'];
$name = $row1['name'];
$message = $row1['message'];


//echo $email;

$curl = curl_init();

//https://hooks.zapier.com/hooks/catch/1877049/txye09/

$url = "https://hooks.zapier.com/hooks/catch/1877049/txye09/";

$data = array(
'email' => $email

, 'name' => $name
, 'message' => $message


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


$sql = "UPDATE datos_consolidados set valortimestamp = '".$lasttimestamp."' where clave = 'ultimomensaje'";
if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}
}



pg_close($db_handle);
?>                                                                                                               

