<?php


//Recupero última fecha de reserva guardada en mysql

$con = mysqli_connect("localhost", "root","238500","zendesk") or die('Could not connect: ' . mysqli_error($con));


$result1 = mysqli_query($con,"SELECT idreserva as id, json FROM reservas");



while ($row = mysqli_fetch_array($result1)) {
   
echo $row["id"]."\n";
    
$j = json_decode($row['json']);

$locata = $j->locata;
   
echo $locata."\n";   
    
$data = array(
'locata' => $locata


);
$jsonEncodedData = json_encode($data);




for($a = 0;$a < 4;$a++) {

$url = 'https://hooks.zapier.com/hooks/catch/1877049/tv5ggi/';

$curl = curl_init();


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

sleep(2);
}



}

?>                                                                                                               

