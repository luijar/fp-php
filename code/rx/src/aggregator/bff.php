#!/usr/local/bin/php7
<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__. '/../bootstrap.php';

use Rx\{
  Observable as Observable,
  Observer as Observer,
  React\Promise as Promise,
  Disposable\CallbackDisposable as Subscription
};

function isValidNumber($val) {
  return !empty($val) && is_numeric($val);
}

function curl(string $url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL, $url);
  $result = curl_exec($ch);
  curl_close($ch);
  return $result;
}

// use curried tracer that you can turn on and off
function findTotalBalance(int $userId): Subscription {
  return Observable::just($userId)
     ->filter('isValidNumber')
     ->flatMap(function () {
          return Promise::toObservable(Promise::resolved(curl('http://localhost:8001/users')));
     })
     ->map('json_decode')
     ->flatMap(function (array $users) {
       return Observable::fromArray($users);
     })
    //  ->flatMap(function ($json) {
    //    $js = json_decode($json);
     //
    //    return Observable::fromArray($js);
    //  })
    //  ->map(function ($response) {
    //    echo gettype($response);
    //    return $response;
    //  })
    ->subscribeCallback(
       function ($value) {
         echo 'Next'. PHP_EOL;
         print_r($value);
       }
   );
}

findTotalBalance(2);
