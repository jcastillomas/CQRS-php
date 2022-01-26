<?php

require("vendor/autoload.php");

$openapi = \OpenApi\Generator::scan(['./src/UI/Http/Rest']);
echo $openapi->toJson();