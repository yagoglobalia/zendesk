<?php

//GET /api/v2/channels/voice/greetings.json

$url = "https://ulyseo.zendesk.com/api/v2/channels/voice/greeting_categories.json";
$username = "ulyseotravel@gmail.com";
$password = "Bimyou20!6";


$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


$result = curl_exec ($ch);

//$result = json_encode($result, JSON_PRETTY_PRINT);

?>
