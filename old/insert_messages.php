<?php


$url = "https://ulyseo.zendesk.com/api/v2/channels/voice/phone_numbers/20317849.json";
$username = "ulyseotravel@gmail.com";
$password = "Bimyou20!6";

$temp_messages = array();
$temp_messages1 = array();
$data = array();
$data1 = array();
$data2 = array();

//me conecto a la bbdd

$con = mysqli_connect("localhost", "root","238500","zendesk") or die('Could not connect: ' . mysqli_error($con));

//FECHAS ESPECIALES

$get = mysqli_query($con,"SELECT count(codigo) as p FROM fechas_especiales WHERE fecha = CURDATE()");
$got = mysqli_fetch_array($get);
$codigo = $got['p'];

if($codigo !=0){

for($i=1;$i<5;$i++){

$get = mysqli_query($con,"SELECT codigo FROM fechas_especiales WHERE tipo = ".$i." AND fecha = CURDATE()");
$got = mysqli_fetch_array($get);
$codigo = $got['codigo'];

$temp_messages[$i] = $codigo;
$temp_messages1[] = $codigo;


}



}else{




//itero por los cuatro tipos
for($i=1;$i<5;$i++){

//recupero el máximo orden
$get = mysqli_query($con,"SELECT MAX(order1) FROM active_messages");
$got = mysqli_fetch_array($get);
$ordermax = $got['MAX(order1)'];

//echo $ordermax."\n";
//selecciono el mensaje con order mas bajo
$get=mysqli_query($con,"select am.id, am.order1 from active_messages am left join fechas_especiales fe on am.id = fe.codigo where am.type = ".$i." AND fe.codigo is NULL order by am.order1 limit 1");
$got = mysqli_fetch_array($get);
$id = $got['id'];
$orden = $got['order1'];

//echo $orden."\n";
//actualizo el orden al máximo + 1


$sql = "UPDATE active_messages SET order1 = 1 + ".$ordermax." WHERE id=".$id;
if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}
	
$temp_messages[$i] = $id;
$temp_messages1[] = $id;


$ordermax++;
}

//Para que el orden no se descontrole.

if($ordermax > 400){

$sql = "UPDATE active_messages SET order1 = order1-300";
if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}

}

}
//los inserto en zendesk

$data1["greeting_ids"] = $temp_messages1;
$data1["categorised_greetings"] = $temp_messages;

$data["phone_number"] = $data1;

$data_json = json_encode($data);

//var_dump($data_json);




$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);



$result = curl_exec ($ch);
curl_close($ch);




$con->close();

  


?>
