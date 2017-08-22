<?php

$username = 'tbox_bi'; 
$password = 'X32ctN'; 
$host = 'tbox-bo-db.ulyseo.com'; 
$db = 'tbox_backoffice'; 


$db_handle = pg_connect("dbname=$db user=$username password=$password host=$host");


if ($db_handle) {   

    echo 'Connection attempt succeeded.';   

} else {   

    echo 'Connection attempt failed.';   

}   

 



$query = "SELECT email, name, surnames FROM tbox_schema.customer";

//$query = "SELECT table_name FROM information_schema.tables WHERE table_schema='public'";


$result = pg_exec($db_handle, $query); 


 for ($row = 0; $row < pg_numrows($result); $row++) {        

        $fullname = pg_result($result, $row, 'email') . " ";        

        $fullname .= pg_result($result, $row, 'name') . " ";        

        $fullname .= pg_result($result, $row, 'surnames');        

        echo "Customer: $fullname";echo "\n";        

   }   
   
   echo pg_numrows($result);

pg_close($db_handle);

?>
