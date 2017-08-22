<?php

echo "Update Costes\n";

$username = 'tbox_bi'; 
$password = 'X32ctN'; 
$host = 'tbox-bo-db.ulyseo.com'; 

$db = 'tbox_backoffice'; 

$db_handle = pg_connect("dbname=$db user=$username password=$password host=$host");

//actualización de estados

$con = mysqli_connect("localhost", "root","238500","zendesk") or die('Could not connect: ' . mysqli_error($con));


$a = 0;
//

$qqq = "SELECT locata FROM reservas where vueloidar = 0 or vuelovueltar = 0";

$resultj=mysqli_query($con,$qqq);

if (!$resultj) { // add this check.
    die('Invalid query: ' . mysqli_error());
}

while($rowa=mysqli_fetch_assoc($resultj)){


$id3 = $rowa['locata'];	
	

//echo $id3."\n";

$query = "


select r.reference as locata, mv.value as importeida, mv1.value as importevuelta

 from tbox_schema.reservation r
inner join tbox_schema.travel t on r.id = t.id
inner join tbox_schema.assignment a on t.assignment_id = a.id
inner join tbox_schema.destination d on t.destination_id = d.id
inner join tbox_schema.flight f on a.outbound_flight_id = f.id
inner join tbox_schema.flight f1 on a.return_flight_id = f1.id
inner join tbox_schema.monetary_value mv on mv.id = f.amount_id
inner join tbox_schema.monetary_value mv1 on mv1.id = f1.amount_id

where r.reference = '".$id3."'";


//echo $query;

$result = pg_query($db_handle, $query) or die("Cannot execute query: $query\n");	
	

$row1 = pg_fetch_assoc($result);
$locator = $row1['locata'];
$importeida = $row1['importeida'];
$importevuelta = $row1['importevuelta'];

//$pas_name = $row1['psname'];
//$pas_surname = $row1['pssurname'];

if($importeida === NULL){$importeida = 0;}
if($importevuelta === NULL){$importevuelta = 0;}

$sql = "UPDATE reservas SET vueloidar = ".$importeida.", vuelovueltar = ".$importevuelta." WHERE locata = '".$id3."'";
if(!mysqli_query($con,$sql)){die('Error1 : ' .$sql.'  -  '. mysqli_error($con));}


$a++;

}



echo "Costes actualizados:".$a."\n";

?>                                                                                                             

