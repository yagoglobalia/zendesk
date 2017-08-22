<?php

$grupo = 1;
//recupero número de intentos

$con = mysqli_connect("localhost", "root","238500","zendesk") or die('Could not connect: ' . mysqli_error($con));

$get = mysqli_query($con,"SELECT valortimestamp FROM datos_consolidados where clave = 'ultimointento'");
$got = mysqli_fetch_array($get);
$codigo = $got['valortimestamp'];

echo "fecha guardada: ".$codigo;

$username = 'tbox_bi'; 
$password = 'X32ctN'; 
$host = 'tbox-be-db.ulyseo.com'; 
$db = 'tbox_backend'; 

$db_handle = pg_connect("dbname=$db user=$username password=$password host=$host");

$query = "SELECT payment_id FROM tbox_schema.payment where payment_state = 'FAIL' and creation_time > '".$codigo."'";

//$query = "SELECT table_name FROM information_schema.tables WHERE table_schema='public'";


$result = pg_exec($db_handle, $query); 


$row = pg_numrows($result);        

$query = "SELECT max(creation_time) as pm FROM tbox_schema.payment where payment_state = 'FAIL'";

$result = pg_query($query);
$row1 = pg_fetch_assoc($result);
$lasttimestamp = $row1['pm'];

pg_close($db_handle);

echo "\n";
echo $row;
echo "\n";
echo "ultimafecha: ".$lasttimestamp;
echo "\n";
$lasttimestamp = date("Y-m-d H:i:s",strtotime($lasttimestamp));
$lasttimestamp++;
echo "hola ".$lasttimestamp;

if($grupo == 0){
$device = "sm-g925f,raquel,antxon,yago,yagogbl";
}elseif($grupo == 1){
$device ="raquel,yago,yagogbl";
}else{
$device ="yago";
}
//actualizo

if($row > 0){
$sql = "UPDATE datos_consolidados set valortimestamp = '".$lasttimestamp."' where clave = 'ultimointento'";
if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}


$bb = "¡Intento fallido!";

$url = "https://api.pushover.net/1/messages.json";
//$username = "ulyseotravel@gmail.com";
//$password = "pushoverulyseo";

curl_setopt_array($ch = curl_init(), array(
  CURLOPT_URL => "https://api.pushover.net/1/messages.json",
  CURLOPT_POSTFIELDS => array(
    "token" => "a4vjh4zugoh8e956kmcjk97g7c2msa",
    "user" => "ubouwrdoxkytpfp8oi5kqkswvbpzgg",
    "message" => $bb
    ,"device" => $device
  ),
  CURLOPT_SAFE_UPLOAD => true,
));
curl_exec($ch);
curl_close($ch);

}
?>                                                                                                               

