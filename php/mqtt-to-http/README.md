HTTP <-> MQTT ThingSpeak Bridge for PHP
========================================

You need MQTT extension for php - https://github.com/mgdm/Mosquitto-PHP

Steps:
------

1. Update configuration in ```config.php```
2. Run http_listener.php
   * ```php -S localhost:8080 http_listener.php```
   * ```curl http://localhost:8080?command=hello```
3. Run mqtt_listener
   * ```php mqtt_listener.php```
   * ```mosquitto_pub -t 'bridge/thingspeak/request' -u user -P XXX '{"field1": 300}'```
