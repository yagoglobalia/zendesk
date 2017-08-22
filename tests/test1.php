<?php

$username = "ulyseotravel@gmail.com";
$password = "Bimyou20!6";
$url = "https://ulyseo.zendesk.com//api/v2/channels/voice/greetings.json";


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);


curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

$response = curl_exec ($ch);
curl_close($ch);
echo $response;






?>
