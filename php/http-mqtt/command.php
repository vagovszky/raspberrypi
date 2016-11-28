<?php
$received = false;

$client = new Mosquitto\Client('PHP - Client');
$client->setCredentials('user', 'password');

$client->onConnect(function($code, $message){
  if($code == 0){
    echo date('d-m-Y H:i:s')." - client connected\n";
  }else{
    echo date('d-m-Y H:i:s')." - ".$message."\n";
    exit;
  }
});

$client->onDisconnect(function($code){
  echo date('d-m-Y H:i:s')." - client disconected\n";
});

$client->onSubscribe(function($messageId, $QosCount){
  echo date('d-m-Y H:i:s')." - Subscribed to message id $messageId\n";
});

$client->onUnsubscribe(function($messageId){
  echo date('d-m-Y H:i:s')." - Unsubscribed to message $messageId\n";
});

$client->onMessage(function($message){
  global $received;
  echo date('d-m-Y H:i:s')." - msg received.\n";
  var_dump($message);
  $received = true;
});

//$client->setWill('/hello', "Client died :-(", 1, 0);

$client->connect("localhost", 1883, 10);
$client->loop();
$client->subscribe('test/topic', 0);
$client->loop();

for($i=0; $i<=10; $i++){
  echo date('d-m-Y H:i:s')." - loop iteration no. $i.\n";
  $client->loop(1000);
  if($received) break;
}

echo date('d-m-Y H:i:s')." - Disconnecting\n";
$client->disconnect();
