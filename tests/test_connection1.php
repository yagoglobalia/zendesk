<?php
//backoffice

try{
$username = 'tbox_bi'; 
$password = 'X32ctN'; 
$host = 'tbox-bo-db.ulyseo.com'; 
$db = 'tbox_backoffice'; 

$dsn = "pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password";


try{
	// create a PostgreSQL database connection
	$conn = new PDO($dsn);

	// display a message if connected to the PostgreSQL successfully
	if($conn){
		echo "Connected to the <strong>$db</strong> database successfully!";
	}
}catch (PDOException $e){
	// report error message
	echo $e->getMessage();
}




?>
