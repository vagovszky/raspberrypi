<?php
set_time_limit(0);

$client = new Mosquitto\Client($config['mqtt_listener']['client_name']);
$client->setCredentials($config['mqtt_listener']['mqtt_user'], $config['mqtt_listener']['mqtt_user']);

$client->onConnect(function($code, $message){
  if($code != 0){
    die('ERROR: ' . $message);
  }
});

$client->onMessage(function($message)use($config) {
  $message = implode('&',(array) json_decode($message->payload));
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $config['mqtt_listener']['thingspeak_url']);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'X-THINGSPEAKAPIKEY: '.$config['mqtt_listener']['thingspeak_api_key'],
    'Content-Type: application/x-www-form-urlencoded',
    'Content-Length: ' . strlen($message)
  ));
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS,  $message);
  $data = curl_exec($ch);
  curl_close($ch);
});

$client->connect($config['mqtt_listener']['mqtt_host'], $config['mqtt_listener']['mqtt_port'] , $config['mqtt_listener']['timeout']);
$client->loop();
$client->subscribe($config['mqtt_listener']['mqtt_topic'], 0);
$client->loopForever();