<?php
return array(
  'http_listener' => array(
    'timeout' => 5,
    'client_name' => 'php_http_listener'
    'mqtt_host' => 'localhost'
    'mqtt_port' => 1883,
    'mqtt_user' => '',
    'mqtt_password' => '',
    'mqtt_request_topic' => 'bridge/command/request',
    'mqtt_response_topic' => 'bridge/command/response'
  ),
  'mqtt_listener' => array(
    'timeout' => 60,
    'client_name' => 'php_mqtt_listener'
    'mqtt_host' => 'localhost'
    'mqtt_port' => 1883,
    'mqtt_user' => '',
    'mqtt_password' => '',
    'mqtt_topic' => 'bridge/thingspeak/request',
    'thingspeak_url' => 'http://api.thingspeak.com/update',
    'thingspeak_api_key' => ''
  )
);