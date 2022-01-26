<?php

require("vendor/autoload.php");

$openapi = \OpenApi\Generator::scan(['./src/UI/Http/Rest']);
$response = $openapi->toJson();

$fp = fopen('./public/swagger-ui/swagger.json', 'w');
fwrite($fp, $response);
fclose($fp);