<?php
return array(
  'http_listener' => array(
    'timeout' => 5,
    'client_name' => 'php_http_listener'
    'mqtt_host' => 'localhost'
    'mqtt_port' => 1883,
    'mqtt_user' => '',
    'mqtt_password' => '',
    'mqtt_request_topic' => 'bridge/request',
    'mqtt_response_topic' => 'bridge/response'
  )
);