<?php

echo "Nueva reserva mysql\n";

//echo "hola";
//NUEVAS RESERVAS

$con = mysqli_connect("localhost", "root","238500","zendesk") or die('Could not connect: ' . mysqli_error($con));


//echo " -- ".$ultimostamp."\n";

//me conecto a postgres

$username = 'tbox_bi'; 
$password = 'X32ctN'; 
$host = 'tbox-be-db.ulyseo.com'; 
$db = 'tbox_backend'; 

$db_handle = pg_connect("dbname=$db user=$username password=$password host=$host");


$sql = "CREATE TABLE locatastemp (locata VARCHAR(100) NOT NULL)";
	if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}

//recupero el número de ids de reservas con creación mayor de la última guardada.

$query = "SELECT locator FROM tbox_schema.reservation";

$rs = pg_exec($db_handle, $query); 

while ($rowQ = pg_fetch_assoc($rs)) {
  $locator = $rowQ['locator'];
  //echo $locator."\n";
  $sql = "INSERT INTO locatastemp (locata) VALUES ('".$locator."') ";
	if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}
}


$queryf = "SELECT locata FROM locatastemp";

$resultj=mysqli_query($con,$queryf);

if (!$resultj) { // add this check.
    die('Invalid query: ' . mysqli_error());
}

while($rowa=mysqli_fetch_assoc($resultj)){

$ll = $rowa['locata'];

$query = "
select r.reservation_id, r.state, r.amount as amount, r.locator as locator, r.customer_id, c.email as email, c.phone as phone, c.name as name
, c.surnames as surnames, r.origin_code as origin, r.departure as departure, gc.name as rumbo, r.creation_time as creation
from tbox_schema.reservation r 
inner join tbox_schema.customer c on r.customer_id = c.customer_id 
inner join tbox_schema.getaway_category gc on gc.category_id = r.getaway_category_id
where r.locator = '".$ll."' and r.state not in ('TRAVELLER_INFO_LIMIT_PASSED')";

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



}

$sql = "DROP TABLE locatastemp";
	if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}



mysqli_close($con);	
pg_close($db_handle);
?>                                                                                                               

