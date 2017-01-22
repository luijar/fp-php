#!/usr/local/bin/php7
<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Rx\{
  Observable as Observable,
  Observer as Observer,
  React\Promise as Promise,
  Disposable\CallbackDisposable as Subscription,
  P as P
};

$source = ['83', '85', null, '78', null, '83',
           '72', '73', '78', '69', '32', '80',
           '72', '80'];

Observable::fromArray($source)
  ->filter('notEmpty')
  ->map('chr')
  ->map('strtolower')
  ->bufferWithCount(20)
  ->map('join')
  ->subscribeCallback(
     function ($result) {
       echo "We're at: ${result} \n";
     }
  );

// $query = 'Muse';
// Observable::just("https://api.spotify.com/v1/search?q=${query}&type=album")
//     ->flatMap(function ($url) {
//          return Promise::toObservable(Promise::resolved(fetch($url)));
//     })
//     ->map('json_decode')
//     ->flatMap(function ($json) {
//       return Observable::fromArray(P::props(['albums', 'items'], $json));
//     })
//     ->subscribeCallback($console);

    //-> Muse
    //-> The Resistance
    //-> The 2nd Law


// const $query = 'Muse';
// Observable::just("https://api.spotify.com/v1/search?q=${query}&type=album")
//     ->flatMap(fn ($url) =>
//         Promise::toObservable(Promise::resolved(fetch($url)))
//     )
//     ->map('json_decode')
//     ->flatMap(fn ($json) =>
//         Observable::fromArray(P::props(['albums', 'items'], $json))
//     )
//     ->subscribeCallback($console);


// Observable::just(Observable::('sunshinephp'))->flatMap('strtoupper')
// //-> SUNSHINEPHP

function notEmpty($val) {
    return !empty($val);
}

function fetch(string $url): string {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL, $url);
  $result = curl_exec($ch);
  $errors = curl_error($ch);
  var_dump($errors);
  curl_close($ch);
  return $result;
}
