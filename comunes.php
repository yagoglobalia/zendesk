<?php

//funcion envío a pushover

function send_pushover($group, $message, $title)

{

if($group == 0){
$device ="";
}elseif($group == 1){
$device ="raquel,yago,yagogbl";
}else{
$device ="yago";
}

 

curl_setopt_array($ch = curl_init(), array(
  CURLOPT_URL => "https://api.pushover.net/1/messages.json",
  CURLOPT_POSTFIELDS => array(
    "token" => "a4vjh4zugoh8e956kmcjk97g7c2msa",
    "user" => "ubouwrdoxkytpfp8oi5kqkswvbpzgg",
    "title" => $title,
    "message" => $message
    ,"device" => $device
  ),
  CURLOPT_SAFE_UPLOAD => true,
));
curl_exec($ch);
curl_close($ch);




}



?>