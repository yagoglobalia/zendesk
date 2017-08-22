
<?php

echo date("Y-m-d H:i:s")." -- Update Booking\n";

//Recupero último id de reserva guardada en mysql

$con = mysqli_connect("localhost", "root","238500","zendesk") or die('Could not connect: ' . mysqli_error($con));

//$get = mysqli_query($con,"SELECT count(locata) as id FROM reservas where new_calendar != 1 and estado != 'TRAVELLER_INFO_LIMIT_PASSED'");
//$got = mysqli_fetch_array($get);
//$numero = $got['id'];f



	
//rollo autenticación google calendar
	
require_once __DIR__ . '/vendor/autoload.php';

define('APPLICATION_NAME', 'Google Calendar API PHP Quickstart');
define('CREDENTIALS_PATH', '/home/ubuntu/.credentials/calendar-php-quickstart.json');
define('CLIENT_SECRET_PATH', __DIR__ . '/client_secret.json');
// If modifying these scopes, delete your previously saved credentials
// at ~/.credentials/calendar-php-quickstart.json
define('SCOPES', implode(' ', array(
  //Google_Service_Calendar::CALENDAR_READONLY)
  Google_Service_Calendar::CALENDAR)
));

if (php_sapi_name() != 'cli') {
  throw new Exception('This application must be run on the command line.');
}

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient() {
  $client = new Google_Client();
  $client->setApplicationName(APPLICATION_NAME);
  $client->setScopes(SCOPES);
  $client->setAuthConfig(CLIENT_SECRET_PATH);
  $client->setAccessType('offline');

  // Load previously authorized credentials from a file.
  $credentialsPath = expandHomeDirectory(CREDENTIALS_PATH);
  if (file_exists($credentialsPath)) {
    $accessToken = json_decode(file_get_contents($credentialsPath), true);
  } else {
    // Request authorization from the user.
    $authUrl = $client->createAuthUrl();
    printf("Open the following link in your browser:\n%s\n", $authUrl);
    print 'Enter verification code: ';
    $authCode = trim(fgets(STDIN));

    // Exchange authorization code for an access token.
    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

    // Store the credentials to disk.
    if(!file_exists(dirname($credentialsPath))) {
      mkdir(dirname($credentialsPath), 0700, true);
    }
    file_put_contents($credentialsPath, json_encode($accessToken));
    printf("Credentials saved to %s\n", $credentialsPath);
  }
  $client->setAccessToken($accessToken);

  // Refresh the token if it's expired.
  if ($client->isAccessTokenExpired()) {
    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
  }
  return $client;
}

/**
 * Expands the home directory alias '~' to the full path.
 * @param string $path the path to expand.
 * @return string the expanded path.
 */
function expandHomeDirectory($path) {
  $homeDirectory = getenv('HOME');
  if (empty($homeDirectory)) {
    $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
  }
  return str_replace('~', realpath($homeDirectory), $path);
}

// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Calendar($client);

//itg6b7mh2bm9fa4742kt2b3tt4@group.calendar.google.com






//$calendar = $service->calendars->get($calendarId);

//itero

$qqq = "SELECT locata as id, jsonassignment, json FROM reservas where update_calendar != 1 and update_assign = 1";

$a=0;

$resulta=mysqli_query($con,$qqq);

while($rowa=mysqli_fetch_assoc($resulta))
{



$json = $rowa['jsonassignment'];
$json2 = $rowa['json'];
$id2 = $rowa['id'];
	


find_event_viaje($json,$service);
//delete_event_viaje($json,$service);

create_event_vueloida($json,$json2,$service);
create_event_vuelovuelta($json,$json2,$service);
create_event_feedback($json,$json2,$service);

//echo $id2."\n";

$sql = "UPDATE reservas SET update_calendar = 1 WHERE locata = '".$id2."'";
if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}

sleep(1);

$a++;

}

echo "Eventos actualizados: ".$a."\n";

function find_event_viaje($hola,$s){
	
	$calendarId = 'itg6b7mh2bm9fa4742kt2b3tt4@group.calendar.google.com';

$hola = json_decode($hola);

$locata = $hola->locator;

$subject = 'Viaje - '.$locata;

$optParams = array('q' => $subject);

$events = $s->events->listEvents($calendarId, $optParams);

//var_dump($events);

foreach ($events->getItems() as $event) {
    //echo $event->getId();
    //echo "\n";
    delete_event_viaje($event->getId(),$s);
 	   
    
  }

}


function delete_event_viaje($id,$s)
{
	$calendarId = 'itg6b7mh2bm9fa4742kt2b3tt4@group.calendar.google.com';

$s->events->delete($calendarId, $id);


}

function create_event_vueloida($hola,$hola2,$s){
	
	$calendarId = 'itg6b7mh2bm9fa4742kt2b3tt4@group.calendar.google.com';

//echo $hola;
	
$hola = json_decode($hola);
$hola2 = json_decode($hola2);



$salidaida = strtotime($hola->salidaida);
	
$salidaida = date('Y-m-d\TH:i:s',$salidaida);

$salidavuelta = strtotime($hola->salidavuelta);
	
$salidavuelta = date('Y-m-d\TH:i:s',$salidavuelta);

if($hola2->rumbo == 'Espau00f1a') {
	
$hola2->rumbo = 'España';	
	
	}
	
$event = new Google_Service_Calendar_Event(array(
  'summary' => 'Vuelo Ida - '.$hola2->locata,
  'location' => html_entity_decode($hola2->origin).' - '.html_entity_decode($hola->destino),
  
  'description' => 
  		'Email: '.$hola2->email."\n".
  		'Teléfono: '.$hola2->phone."\n".
  		'Nombre: '.html_entity_decode($hola2->fullname)."\n\n".
  		
  'Hotel: '.html_entity_decode($hola->hotelname)."\n\n".
  
  'Vuelo ida - Cía: '.$hola->ciaida."\n".
  'Vuelo ida - número: '.$hola->numeroida."\n".
  'Vuelo ida - salida: '.$hola->salidaida."\n".
  'Vuelo ida - vuelta: '.$hola->salidavuelta."\n\n".
  
  
   'Vuelo vuelta - Cía: '.$hola->ciavuelta."\n".
  'Vuelo vuelta - número: '.$hola->salidavuelta."\n".
  'Vuelo vuelta - salida: '.$hola->retornoida."\n".
  'Vuelo vuelta - vuelta: '.$hola->retornovuelta."\n\n".
  
  'ControlIda123'
  
  

  ,
  'start' => array(
    'dateTime' => $salidaida,
    'timeZone' => 'Europe/Madrid',
  ),
  'end' => array(
    'dateTime' => $salidavuelta,
    'timeZone' => 'Europe/Madrid',
  ),
  'reminders' => array(
    'useDefault' => FALSE,
    'overrides' => array(
      array('method' => 'email', 'minutes' => 24 * 60),
      array('method' => 'popup', 'minutes' => 10),
    ),
  ),
));

$event->setColorId("3");

$event = $s->events->insert($calendarId, $event);
//printf('Event created: %s\n', $event->htmlLink);



}


function create_event_vuelovuelta($hola,$hola2,$s){
	
	$calendarId = 'itg6b7mh2bm9fa4742kt2b3tt4@group.calendar.google.com';

//echo $hola;
	
$hola = json_decode($hola);
$hola2 = json_decode($hola2);

$retornoida = strtotime($hola->retornoida);
	
$retornoida = date('Y-m-d\TH:i:s',$retornoida);

$retornovuelta = strtotime($hola->retornovuelta);
	
$retornovuelta = date('Y-m-d\TH:i:s',$retornovuelta);

if($hola2->rumbo == 'Espau00f1a') {
	
$hola2->rumbo = 'España';	
	
	}
	
$event = new Google_Service_Calendar_Event(array(
  'summary' => 'Vuelo Vuelta - '.$hola2->locata,
  'location' => html_entity_decode($hola2->origin).' - '.html_entity_decode($hola->destino),
  
  'description' => 
  		'Email: '.$hola2->email."\n".
  		'Teléfono: '.$hola2->phone."\n".
  		'Nombre: '.html_entity_decode($hola2->fullname)."\n\n".
  		
  'Hotel: '.html_entity_decode($hola->hotelname)."\n\n".
  
  'Vuelo ida - Cía: '.$hola->ciaida."\n".
  'Vuelo ida - número: '.$hola->numeroida."\n".
  'Vuelo ida - salida: '.$hola->salidaida."\n".
  'Vuelo ida - vuelta: '.$hola->salidavuelta."\n\n".
  
  
   'Vuelo vuelta - Cía: '.$hola->ciavuelta."\n".
  'Vuelo vuelta - número: '.$hola->salidavuelta."\n".
  'Vuelo vuelta - salida: '.$hola->retornoida."\n".
  'Vuelo vuelta - vuelta: '.$hola->retornovuelta."\n\n".
  
    'ControlVuelta456'

  ,
  'start' => array(
    'dateTime' => $retornoida,
    'timeZone' => 'Europe/Madrid',
  ),
  'end' => array(
    'dateTime' => $retornovuelta,
    'timeZone' => 'Europe/Madrid',
  ),
  'reminders' => array(
    'useDefault' => FALSE,
    'overrides' => array(
      array('method' => 'email', 'minutes' => 24 * 60),
      array('method' => 'popup', 'minutes' => 10),
    ),
  ),
));

$event->setColorId("3");


$event = $s->events->insert($calendarId, $event);
//printf('Event created: %s\n', $event->htmlLink);



}




function create_event_feedback($hola,$hola2,$s){
	

	
	$calendarId = 'itg6b7mh2bm9fa4742kt2b3tt4@group.calendar.google.com';

//echo $hola;
	
$hola = json_decode($hola);
$hola2 = json_decode($hola2);

	$url = "http://52.212.202.215/feedback.php?locator=".$hola->locator;	

">link</a>";




if($hola2->rumbo == 'Canarias') {
$r = 3;
}else {
	$r = 2;
}

$departure = strtotime($hola2->departure);

$arrival = $departure + $r * 24 * 60 * 60;

$feedback = $arrival + 3 * 24 * 60 * 60;


	
$dayofweek = date('D',$feedback);

if($dayofweek == 'Sat' || $dayofweek == 'Sun'){
$feedback = $feedback + 2 * 24 * 60 * 60;

}
	
$feedback = date('Y-m-d\TH:i:s',$feedback + 9 * 60 * 60);




//echo $departure1." - ".$arrival;

if($hola2->rumbo == 'Espau00f1a') {
	
$hola2->rumbo = 'España';	
	
	}
	
$event = new Google_Service_Calendar_Event(array(
  'summary' => 'Feedback - '.$hola2->locata,
  'location' => html_entity_decode($hola2->origin).' - '.html_entity_decode($hola->destino),
  'description' => 
  		'Email: '.$hola2->email."\n".
  		'Teléfono: '.$hola2->phone."\n".
  		'Nombre: '.html_entity_decode($hola2->fullname)."\n".
  		'Importe: '.$hola2->amount."\n\n".
  
    $url."\n\n".
  
    'ControlFeedback789'
  ,
  'start' => array(
    'dateTime' => $feedback,
    'timeZone' => 'Europe/Madrid',
  ),
  'end' => array(
    'dateTime' => $feedback,
    'timeZone' => 'Europe/Madrid',
  ),
  'reminders' => array(
    'useDefault' => FALSE,
    'overrides' => array(
      array('method' => 'email', 'minutes' => 24 * 60),
      array('method' => 'popup', 'minutes' => 10),
    ),
  ),
));

$event->setColorId("5");

$event = $s->events->insert($calendarId, $event);
//printf('Event created: %s\n', $event->htmlLink);



}




