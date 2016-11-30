<?php

header('Content-type: application/json');

$config = require(__DIR__ . '/config.php');

$mqttResponseReceived = false;

if(empty($_GET) || empty($_GET['command'])){
  $response = new \stdClass();
  $response->status = 'ERROR';
  $response->message = 'GET command not set!';
  echo json_encode($response);
  exit;
}

$client = new Mosquitto\Client($config['http_listener']['client_name']);
$client->setCredentials($config['http_listener']['mqtt_user'], $config['http_listener']['mqtt_password']);

$client->onConnect(function($code, $message){
  if($code != 0){
    $response = new \stdClass();
    $response->status = 'ERROR';
    $response->message = $message;
    echo json_encode($response);
    exit;
  }
});

$client->onMessage(function($message)use(&$mqttResponseReceived){
  $mqttResponseReceived = true;
  $response = new \stdClass();
  $response->status = 'SUCCESS';
  $response->message = $message->payload;
  echo json_encode($response);
});

$client->connect($config['http_listener']['mqtt_host'], $config['http_listener']['mqtt_port'] , $config['http_listener']['timeout']);
$client->loop();
$client->subscribe($config['http_listener']['mqtt_response_topic'], 1);
$client->loop();
$client->publish($config['http_listener']['mqtt_request_topic'], $_GET['command'], 1);
$client->loop();

for($i=0; $i <= $config['http_listener']['timeout']; $i++){
  if($mqttResponseReceived) break;
  $client->loop(1000);
}

if(!$mqttResponseReceived){
  $response = new \stdClass();
  $response->status = 'ERROR';
  $response->message = 'Timeout reached - MQTT response was not received';
  echo json_encode($response);
}

$client->unsubscribe($config['http_listener']['mqtt_response_topic']);
$client->loop();
$client->disconnect();
