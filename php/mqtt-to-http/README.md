HTTP <-> MQTT ThingSpeak Bridge for PHP
========================================

You need MQTT extension for php - https://github.com/mgdm/Mosquitto-PHP

Steps:
------

1) Update configuration in config.php
2) Run http_listener.php 
..* php -S localhost:80 http_listener.php
..* curl --data "command=test" http://localhost
3) Run mqtt_listener
..* php mqtt_listener.php  