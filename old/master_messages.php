<?php

//GET /api/v2/channels/voice/greetings.json

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



$con = mysqli_connect("localhost", "yago","238500","zendesk") or die('Could not connect: ' . mysqli_error($con));

foreach($resultarray as $v){
if(gettype($v['id']) == 'integer'){



$id = $v['id'];
$name = $v['name'];
$category_id = $v['category_id'];
$default1 = (int)$v['default'];
$active = (int)$v['active'];
$pending = (int)$v['pending'];



$sql = "INSERT IGNORE INTO messages(id, name, category_id, default1, active, pending, zzz)
VALUES('$id', '$name', '$category_id', '$default1', '$active', '$pending', 1)";
if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}


}
}



$sql = "DELETE FROM messages WHERE zzz = 0";
if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}


  
$sql = "UPDATE messages SET zzz = 0";
if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}



$sql = "DELETE FROM active_messages";
if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}



$sql = "INSERT INTO active_messages(id, type, order1)
SELECT id, 
       category_id,
       @n := @n + 1 n
  FROM messages, (SELECT @n := 0) m
";
if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}








?>
