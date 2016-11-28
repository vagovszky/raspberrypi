<?php
set_time_limit(0);

function thingspeakRequest($temperature, $humidity, $adc, $pressure, $temperature2){

 $temperature = number_format(((($temperature + $temperature2) / 2) - 2), 1, '.', '');
 $humidity = number_format($humidity, 1, '.', '');
 $light = number_format((100 - (100 * ($adc/1024))),1,'.','');
 $pressure = number_format((($pressure / 100) + 24.4), 2, '.', '');

 $message = "field1=$temperature&field2=$humidity&field3=$light&field4=$pressure";

 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, "http://api.thingspeak.com/update");
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

 curl_setopt($ch, CURLOPT_TIMEOUT, 60);
 curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'X-THINGSPEAKAPIKEY: APIKEY',
  'Content-Type: application/x-www-form-urlencoded',
  'Content-Length: ' . strlen($message)
 ));
 curl_setopt($ch, CURLOPT_POST, true);
 curl_setopt($ch, CURLOPT_POSTFIELDS,  $message);

 $data = curl_exec($ch);
 curl_close($ch);
}

function saveData($temperature, $humidity, $adc, $pressure, $temperature2){

 $temperature = number_format((($temperature + $temperature2) / 2), 1, '.', '');
 $humidity = number_format($humidity, 1, '.', '');
 $light = number_format((100 - (100 * ($adc/1024))),1,'.','');
 $pressure = number_format((($pressure / 100) + 24.4), 2, '.', '');

 file_put_contents(__DIR__.'/data/temperature',(string) $temperature);
 file_put_contents(__DIR__.'/data/humidity',(string) $humidity);
 file_put_contents(__DIR__.'/data/lighting',(string) $light);
 file_put_contents(__DIR__.'/data/pressure',(string) $pressure);
	
}

 
$client = new Mosquitto\Client('MyClient');
$client->setCredentials('user', 'password');
 
$client->onConnect(function($code, $message) use ($client) {
    $client->subscribe('/nodemcu/meteostation', 0);
});
 
$client->onMessage(function($message) {
    $topic = $message->topic;
    $payload = $message->payload;
    $data = json_decode($payload);
    saveData($data->temperature, $data->humidity, $data->adc, $data->pressure, $data->temperature2);
    thingspeakRequest($data->temperature, $data->humidity, $data->adc, $data->pressure, $data->temperature2);
});
 
$client->connect('localhost', 1883);
 
$client->loopForever();
