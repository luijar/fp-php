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

function curl(string $url): string {
  echo 'Curling!';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  $result = curl_exec($ch);
  curl_close($ch);
  return $result;
}

// use curried tracer that you can turn on and off

function findTotalBalance(int $userId) {
  return Observable::just($userId)
     ->filter('isValidNumber')
     ->flatMap(function () {
          return Promise::toObservable(Promise::resolved(curl('http://localhost:8001/users')));
     })
     ->subscribeCallback(
        function ($val) {
            print_r($val);
        },
        function (Exception $ex) {
            echo 'Error: ', $ex->getMessage(), PHP_EOL;
        },
        function () {
            echo 'Completed', PHP_EOL;
        }
    );
}

findTotalBalance(2);
