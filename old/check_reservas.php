<?php


//recupero número de reservas

$username = 'tbox_bi'; 
$password = 'X32ctN'; 
$host = 'tbox-bo-db.ulyseo.com'; 
$db = 'tbox_backoffice'; 


$db_handle = pg_connect("dbname=$db user=$username password=$password host=$host");



$query = "SELECT id FROM tbox_schema.customer";

//$query = "SELECT table_name FROM information_schema.tables WHERE table_schema='public'";


$result = pg_exec($db_handle, $query); 


$row = pg_numrows($result);        


pg_close($db_handle);

echo $row;

echo "\n";

?>                                                                                                               

