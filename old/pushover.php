<?php

//GET /api/v2/channels/voice/greetings.json

$url = "https://api.pushover.net/1/messages.json";
//$username = "ulyseotravel@gmail.com";
//$password = "pushoverulyseo";


 

curl_setopt_array($ch = curl_init(), array(
  CURLOPT_URL => "https://api.pushover.net/1/messages.json",
  CURLOPT_POSTFIELDS => array(
    "token" => "a4vjh4zugoh8e956kmcjk97g7c2msa",
    "user" => "ubouwrdoxkytpfp8oi5kqkswvbpzgg",
    "message" => "hello world",
  ),
  CURLOPT_SAFE_UPLOAD => true,
));
curl_exec($ch);
curl_close($ch);
?>                                                                                                               
                                                                                                                     
$result = curl_exec($ch);





?>
