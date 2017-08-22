<?php

//recupero activos de la tabla.

$url = "https://ulyseo.zendesk.com//api/v2/channels/voice/greetings.json";
$username = "ulyseotravel@gmail.com";
$password = "Bimyou20!6";


$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


$result = curl_exec ($ch);

//$resultjson = json_encode($result);



$resultarray = json_decode($result, true);

$resultarray = $resultarray['greetings'];

var_dump($resultarray);





  


?>
