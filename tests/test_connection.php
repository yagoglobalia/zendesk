<?php
//backoffice


$username = 'tbox_bi'; 
$password = 'X32ctN'; 
$host = 'tbox-bo-db.ulyseo.com'; 
$db = 'tbox_backoffice'; 



try{
    $dbh = new pdo( 'pgsql:host=$host;dbname=$db','tbox_bi','X32ctN' );
    die(json_encode(array('outcome' => true)));
}
catch(PDOException $ex){
    die(json_encode(array('outcome' => false, 'message' => 'Unable to connect')));
}


?>
