<?php

echo "Update mysql\n";

$username = 'tbox_bi'; 
$password = 'X32ctN'; 
$host = 'tbox-bo-db.ulyseo.com'; 

$db = 'tbox_backoffice'; 

$db_handle = pg_connect("dbname=$db user=$username password=$password host=$host");

//actualización de estados

$con = mysqli_connect("localhost", "root","238500","zendesk") or die('Could not connect: ' . mysqli_error($con));

$quer = "select locata, estado from reservas";
$resulth=mysqli_query($con,$quer);

while($rowa=mysqli_fetch_assoc($resulth))
{


$estado = $rowa['estado'];

	
$query1 = "select r.reference, state from tbox_schema.reservation r where r.reference ='".$rowa['locata']."'";
$result1 = pg_query($db_handle, $query1) or die("Cannot execute query: $query1\n");	
$row2 = pg_fetch_assoc($result1);
$cat2 = $row2['state'];

if($cat2 != $estado || $estado == '' ){

$sql = "UPDATE reservas SET estado = '".$cat2."' where locata ='".$rowa['locata']."'";
if(!mysqli_query($con,$sql)){die('Error3 : ' . mysqli_error($con));}

}

}

$a = 0;
//

$qqq = "SELECT locata FROM reservas where update_assign != 1 and estado in 
(
'SERVICES_ASSIGNED',
'SERVICES_NOTIFIED',
'SERVICES_CHANGES_NOTIFIED',
'TRIP_IN_PROGRESS',
'FEEDBACK_REQUEST_PENDING',
'FEEDBACK_REQUESTED',
'FEEDBACK_RECEIVED',
'CLOSED_WITHOUT_FEEDBACK',
'CLOSED'
)";

$resultj=mysqli_query($con,$qqq);

if (!$resultj) { // add this check.
    die('Invalid query: ' . mysqli_error());
}

$a = 0;

while($rowa=mysqli_fetch_assoc($resultj)){


$id3 = $rowa['locata'];	
	



$query = "


select gw.category_id as cat, gw.travel_date as salida, r.reference as locator, h.name as hotelname, d.name as destino, f.air_line as ciaida, 
f.fly_number as numeroida, f.departure_time as idaida, f.arrival_time as idavuelta
,f1.air_line as ciavuelta, f1.fly_number as numerovuelta, f1.departure_time as vueltaida, f1.arrival_time as vueltavuelta
,air.airport as aeropuertoidasalida, air1.airport as aeropuertoidavuelta, air2.airport as aeropuertovueltasalida, air3.airport as aeropuertovueltavuelta


 from tbox_schema.reservation r
inner join tbox_schema.travel t on r.id = t.id
inner join tbox_schema.assignment a on t.assignment_id = a.id
inner join tbox_schema.destination d on t.destination_id = d.id
inner join tbox_schema.hotel h on a.hotel_id = h.id
inner join tbox_schema.flight f on a.outbound_flight_id = f.id
inner join tbox_schema.flight f1 on a.return_flight_id = f1.id
inner join tbox_schema.airport_location air on air.id = f.origin_airport_location_id
inner join tbox_schema.airport_location air1 on air1.id = f.destination_airport_location_id	
inner join tbox_schema.airport_location air2 on air2.id = f1.origin_airport_location_id
inner join tbox_schema.airport_location air3 on air3.id = f1.destination_airport_location_id	
inner join tbox_schema.getaway gw on gw.id = r.id

where r.reference = '".$id3."'";


//echo $query;

$result = pg_query($db_handle, $query) or die("Cannot execute query: $query\n");	
	

$row1 = pg_fetch_assoc($result);
$cat = $row1['cat'];
$hotelname = $row1['hotelname'];
$destino = $row1['destino'];
$ciaida = $row1['ciaida'];
$numeroida = $row1['numeroida'];
$idaida = $row1['idaida'];
$idavuelta = $row1['idavuelta'];
$ciavuelta = $row1['ciavuelta'];
$numerovuelta = $row1['numerovuelta'];
$vueltaida = $row1['vueltaida'];
$vueltavuelta = $row1['vueltavuelta'];
$locator = $row1['locator'];
$salida = $row1['salida'];
$aeropuertoidasalida = $row1['aeropuertoidasalida'];
$aeropuertoidavuelta = $row1['aeropuertoidavuelta'];
$aeropuertovueltasalida = $row1['aeropuertovueltasalida'];
$aeropuertovueltavuelta = $row1['aeropuertovueltavuelta'];
//$pas_name = $row1['psname'];
//$pas_surname = $row1['pssurname'];

if($cat == 2){
$nd = 3;$cc = "Canarias";
}else {$nd = 2;
$cc = "Resto";
}


//echo $id3." ".$ciaida." ".$ciavuelta."\n";



$salidaida1 = strtotime($salida) + (strtotime($idaida) - strtotime(date('Y-m-d')));



$salidavuelta1 = strtotime($salida) + (strtotime($idavuelta)  - strtotime(date('Y-m-d')));

if($salidavuelta1 < $salidaida1){ $salidavuelta1 = $salidaida1 + 7200;  }



$retorno = strtotime($salida) + $nd * 24 * 60 * 60; 

//echo $cc."  -  ".$salida."  ".date('Y-m-d',$retorno)."\n";

$retornoida1 = $retorno + (strtotime($vueltaida)  - strtotime(date('Y-m-d')));
$retornovuelta1 = $retorno + (strtotime($vueltavuelta)  - strtotime(date('Y-m-d')));

if($retornovuelta1 < $retornoida1){ $retornovuelta1 = $retornoida1 + 7200;  }


//$salidaida = date('Y-m-d H:i:s',strtotime('+'.substr($idaida,0,2).' hour +'.substr($idaida,3,2).' minutes',strtotime($salida)));

$salidaida = date('Y-m-d H:i:s',$salidaida1);
$salidavuelta = date('Y-m-d H:i:s',$salidavuelta1);
$retornoida = date('Y-m-d H:i:s',$retornoida1);
$retornovuelta = date('Y-m-d H:i:s',$retornovuelta1);

//$salidavuelta = date('Y-m-d H:i:s',strtotime('+'.substr($idavuelta,0,2).' hour +'.substr($idavuelta,3,2).' minutes',strtotime($salida)));
//$retornoida = date('Y-m-d H:i:s',strtotime('+ '.$nd.' days +'.substr($idavuelta,0,2).' hour +'.substr($idavuelta,3,2).' minutes',strtotime($salida)));
//$retornovuelta = date('Y-m-d H:i:s',strtotime('+ '.$nd.' days +'.substr($vueltavuelta,0,2).' hour +'.substr($vueltavuelta,3,2).' minutes',strtotime($salida)));


//echo " -- ".$salidaida." - ".$salidavuelta." - ".$retornoida." - ".$retornovuelta;


$hotelname = htmlentities(str_replace('\'','',$hotelname));
$destino = htmlentities(str_replace('\'','',$destino));



$data = array(
'hotelname' => $hotelname
, 'destino' => $destino
, 'ciaida' => $ciaida
, 'numeroida' => $numeroida
, 'idaida' => $idaida
, 'idavuelta' => $idavuelta
, 'ciavuelta' => $ciavuelta
, 'numerovuelta' => $numerovuelta
, 'vueltaida' => $vueltaida
, 'vueltavuelta' => $vueltavuelta
, 'locator' => $locator
, 'salida' => $salida
, 'salidaida' => $salidaida
, 'salidavuelta' => $salidavuelta
, 'retornoida' => $retornoida
, 'retornovuelta' => $retornovuelta

, 'aeropuertoidasalida' => $aeropuertoidasalida
, 'aeropuertoidavuelta' => $aeropuertoidavuelta
, 'aeropuertovueltasalida' => $aeropuertovueltasalida
, 'aeropuertovueltavuelta' => $aeropuertovueltavuelta
//, 'name' => $pas_name." ".$pas_surname


);




$jsonEncodedData = json_encode($data);



$sql = "UPDATE reservas SET jsonassignment = '".$jsonEncodedData."', ciaida = '".$ciaida."', ciavuelta = '".$ciavuelta."' WHERE locata = '".$id3."'";
if(!mysqli_query($con,$sql)){die('Error1 : ' .$sql.'  -  '. mysqli_error($con));}



$sql = "UPDATE reservas SET update_assign = 1 WHERE locata = '".$id3."'";
if(!mysqli_query($con,$sql)){die('Error2 : ' . mysqli_error($con));}

$a++;

}



echo "Reservas actualizadas:".$a."\n";
?>                                                                                                             

