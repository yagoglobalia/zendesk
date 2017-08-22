
<?php

echo date("Y-m-d H:i:s")." -- Add Booking\n";

//Recupero último id de reserva guardada en mysql

$con = mysqli_connect("localhost", "root","238500","zendesk") or die('Could not connect: ' . mysqli_error($con));

//$get = mysqli_query($con,"SELECT count(locata) as id FROM reservas where new_calendar != 1 and estado != 'TRAVELLER_INFO_LIMIT_PASSED'");
//$got = mysqli_fetch_array($get);
//$numero = $got['id'];



	
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

$qqq = "SELECT locata as id, json FROM reservas where new_calendar != 1 and estado != 'TRAVELLER_INFO_LIMIT_PASSED'";



$result=mysqli_query($con,$qqq);

$a = 0;

while($rowa=mysqli_fetch_assoc($result))
{



$json = $rowa['json'];
$id2 = $rowa['id'];
	

create_event_booking($json,$service);
create_event_notification($json,$service);
create_event_trip($json,$service);

//echo $id2."\n";

$sql = "UPDATE reservas SET new_calendar = 1 WHERE locata = '".$id2."'";
if(!mysqli_query($con,$sql)){die('Error : ' . mysqli_error($con));}

sleep(1);
$a++;

}

echo "Eventos añadidos: ".$a."\n";


function create_event_booking($hola,$s){
	
	$calendarId = 'itg6b7mh2bm9fa4742kt2b3tt4@group.calendar.google.com';

//echo $hola;
	
$hola = json_decode($hola);

$creation = strtotime($hola->creation);
	
$creation = date('Y-m-d\TH:i:s',$creation);

if($hola->rumbo == 'Espau00f1a') {
	
$hola->rumbo = 'España';	
	
	}
	
$event = new Google_Service_Calendar_Event(array(
  'summary' => 'Nueva Reserva - '.$hola->locata,
  'location' => $hola->origin.' - '.$hola->rumbo,
  
  'description' => 
  		'Email: '.$hola->email."\n".
  		'Teléfono: '.$hola->phone."\n".
  		'Nombre: '.$hola->fullname."\n".
  		'Importe: '.$hola->amount."\n"
  
  
  
  
  ,
  'start' => array(
    'dateTime' => $creation,
    'timeZone' => 'Europe/Madrid',
  ),
  'end' => array(
    'dateTime' => $creation,
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

$event->setColorId("11");

$event = $s->events->insert($calendarId, $event);
//printf('Event created: %s\n', $event->htmlLink);



}


function create_event_notification($hola,$s){
	
	$calendarId = 'itg6b7mh2bm9fa4742kt2b3tt4@group.calendar.google.com';

//echo $hola;
	
$hola = json_decode($hola);

$departure = strtotime($hola->departure);
	
$departure = date('Y-m-d\TH:i:s',$departure - 7 * 24 * 60 * 60 + 9 * 60 * 60);

if($hola->rumbo == 'Espau00f1a') {
	
$hola->rumbo = 'España';	
	
	}
	
$event = new Google_Service_Calendar_Event(array(
  'summary' => 'Notificación - '.$hola->locata,
  'location' => $hola->origin.' - '.$hola->rumbo,
  'description' => 
  		'Email: '.$hola->email."\n".
  		'Teléfono: '.$hola->phone."\n".
  		'Nombre: '.$hola->fullname."\n".
  		'Importe: '.$hola->amount."\n"
  
  
  
  
  ,
  'start' => array(
    'dateTime' => $departure,
    'timeZone' => 'Europe/Madrid',
  ),
  'end' => array(
    'dateTime' => $departure,
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

$event->setColorId("9");


$event = $s->events->insert($calendarId, $event);
//printf('Event created: %s\n', $event->htmlLink);



}

function create_event_trip($hola,$s){
	
	$calendarId = 'itg6b7mh2bm9fa4742kt2b3tt4@group.calendar.google.com';

//echo $hola;
	
$hola = json_decode($hola);

if($hola->rumbo == 'Canarias') {
$r = 3;
}else {
	$r = 2;
}

$departure = strtotime($hola->departure);
	
$departure1 = date('Y-m-d\TH:i:s',$departure + 9 * 60 * 60);

$arrival = date('Y-m-d\TH:i:s',$departure + 24 * $r * 60 * 60 + 21 * 60 * 60);

//echo $departure1." - ".$arrival;

if($hola->rumbo == 'Espau00f1a') {
	
$hola->rumbo = 'España';	
	
	}
	
$event = new Google_Service_Calendar_Event(array(
  'summary' => 'Viaje - '.$hola->locata,
  'location' => $hola->origin.' - '.$hola->rumbo,
  'description' => 
  		'Email: '.$hola->email."\n".
  		'Teléfono: '.$hola->phone."\n".
  		'Nombre: '.$hola->fullname."\n".
  		'Importe: '.$hola->amount."\n"
  
  
  
  
  ,
  'start' => array(
    'dateTime' => $departure1,
    'timeZone' => 'Europe/Madrid',
  ),
  'end' => array(
    'dateTime' => $arrival,
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




function create_event_feedback($hola,$s){
	
	$calendarId = 'itg6b7mh2bm9fa4742kt2b3tt4@group.calendar.google.com';

//echo $hola;
	
$hola = json_decode($hola);

if($hola->rumbo == 'Canarias') {
$r = 3;
}else {
	$r = 2;
}

$departure = strtotime($hola->departure);

$arrival = $departure + $r * 24 * 60 * 60;

$feedback = $arrival + 3 * 24 * 60 * 60;


	
$dayofweek = date('D',$feedback);

if($dayofweek == 'Sat' || $dayofweek == 'Sun'){
$feedback = $feedback + 2 * 24 * 60 * 60;

}
	
$feedback = date('Y-m-d\TH:i:s',$feedback + 9 * 60 * 60);




//echo $departure1." - ".$arrival;

if($hola->rumbo == 'Espau00f1a') {
	
$hola->rumbo = 'España';	
	
	}
	
$event = new Google_Service_Calendar_Event(array(
  'summary' => 'Feedback - '.$hola->locata,
  'location' => $hola->origin.' - '.$hola->rumbo,
  'description' => 
  		'Email: '.$hola->email."\n".
  		'Teléfono: '.$hola->phone."\n".
  		'Nombre: '.$hola->fullname."\n".
  		'Importe: '.$hola->amount."\n"
  
  
  
  
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




