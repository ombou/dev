<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Ombou\Library\JWTHandler;

/**/
$salt = 'LWHIsWobZNlfsIh5';
$token = 'OMA-f0501dcc861782a28bc717de9c068eb84cc5b5f5675c0254b67e29782708bb561933adbf7f57422d93c9b685fba046385e73';
/**/

$salt = 'fdghemcnml8kj4LhlZHD54';
$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vb3BlbmlkLWludC5tZWRpdGVsLm1hL29pZGMiLCJzdWIiOiIyMTI2NjAwOTEwOTIiLCJhdWQiOlsiY2xpZW50TWVkaXRlbEludGVncmF0aW9uIl0sImV4cCI6MTQ1Mzg5NzU3OSwiaWF0IjoxNDUzODkzOTc5LCJhdXRoX3RpbWUiOjE0NTM4OTM5NTUsIm5vbmNlIjoiTm9uY2UwLmFjM3oybXRjb3pzc3YydDkiLCJhY3IiOiIyIiwiYW1yIjpbIlNNUyBPVFAiXX0.XGv6roaynNb3ckMetWuZ66qE7ODOVcULhSfGAiS0-3Q';

$jwt = new JWTHandler($salt);
$return = $jwt->checkToken($token);
var_dump($return);

