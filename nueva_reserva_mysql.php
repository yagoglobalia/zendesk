<?php

echo "Nueva reserva mysql\n";

//echo "hola";
//NUEVAS RESERVAS

$con = mysqli_connect("localhost", "root","238500","zendesk") or die('Could not connect: ' . mysqli_error($con));

$get = mysqli_query($con,"SELECT valorint FROM datos_consolidados where clave = 'ultimareserva2'");
$got = mysqli_fetch_array($get);
$ultimostamp = $got['valorint'];

//echo " -- ".$ultimostamp."\n";

//me conecto a postgres

$username = 'tbox_bi'; 
$password = 'X32ctN'; 
$host = 'tbox-be-db.ulyseo.com'; 
$db = 'tbox_backend'; 

$db_handle = pg_connect("dbname=$db user=$username password=$password host=$host");

//recupero el número de ids de reservas con creación mayor de la última guardada.

$query = "SELECT reservation_id FROM tbox_schema.reservation where reservation_id > ".$ultimostamp;

$result = pg_exec($db_handle, $query); 

$row2 = pg_numrows($result);        

//$row1--;

echo "total reservas ".$row2."\n";
//itero el número de reservas

//recupero el último id en postgres

$queryx = "SELECT max(reservation_id) as idmax FROM tbox_schema.reservation";
$resultx = pg_query($db_handle, $queryx) or die("Cannot execute query: $query\n");
$row1x = pg_fetch_assoc($resultx);
$idmax = $row1x['idmax'];

//echo " --- ".$idmax;


for($a = 0;$a < $row2;$a++){



// vuelvo a recuperar el ultimo timestamp guardado	
$get = mysqli_query($con,"SELECT valorint FROM datos_consolidados where clave = 'ultimareserva2'");
$got = mysqli_fetch_array($get);
$ultimostamp1 = $got['valorint'];

//recupero la primera reserva por orden de creación



$query = "
select r.reservation_id, r.state, r.amount as amount, r.locator as locator, r.customer_id, c.email as email, c.phone as phone, c.name as name
, c.surnames as surnames, r.origin_code as origin, r.departure as departure, gc.name as rumbo, r.creation_time as creation
from tbox_schema.reservation r 
inner join tbox_schema.customer c on r.customer_id = c.customer_id 
inner join tbox_schema.getaway_category gc on gc.category_id = r.getaway_category_id
where r.reservation_id > ".$ultimostamp1." and r.state not in ('TRAVELLER_INFO_LIMIT_PASSED') ORDER BY reservation_id LIMIT 1";

$result = pg_query($db_handle, $query) or die("Cannot execute query: $query\n");


$row1 = pg_fetch_assoc($result);
$email = $row1['email'];
$phone = $row1['phone'];
$name = htmlentities($row1['name']);
$surnames = htmlentities($row1['surnames']);
$amount = $row1['amount'];
$origin = $row1['origin'];
$departure = $row1['departure'];
$rumbo = $row1['rumbo'];
$locator = $row1['locator'];
$id = $row1['reservation_id'];
$state = $row1['state'];
$creation = $row1['creation'];



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
, 'creation' => $creation

);
$jsonEncodedData = json_encode($data);

//echo $row2." - ".$a." - ".$id." - ".$jsonEncodedData."\n";;

//echo $locator." ".$id." ".$a."\n";

$sql = "INSERT IGNORE INTO reservas (locata, estado, json) VALUES ('$locator','$state', '$jsonEncodedData')";
	if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}



$b = $ultimostamp + $a;





}

//echo " --".$idmax."  \n";

$sql = "UPDATE datos_consolidados set valorint = ".$idmax." where clave = 'ultimareserva2'";
if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}


mysqli_close($con);	
pg_close($db_handle);
?>                                                                                                               

