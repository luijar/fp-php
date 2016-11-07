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

function adder($a, $b) {
  return $a + $b;
}

// finish
function fetchEndPointStream(string $endpoint): Observable {
  return Observable::just($endpoint)
    ->flatMap(function ($url) {
         return Promise::toObservable(Promise::resolved(curl($url)));
    })
    ->map('json_decode')
    ->flatMap(function ($data) {
      return is_array($data) ? Observable::fromArray($data)
        : Observable::just($data);
    });
}

// use curried tracer that you can turn on and off
function findTotalBalance(int $userId): Subscription {
  return Observable::just($userId)
     ->map('isValidNumber')

     # First end point reached
     # Valid name provided, fetch all users
     ->flatMapLatest(function () {
        return fetchEndPointStream("http://localhost:8001/users");
     })
     ->filter(function ($user) use ($userId) {
        return $user->id === $userId;
     })
     ->map(P::prop('id'))
     ->doOnNext(function ($userId) {
        echo "[DEBUG] Found user with ID: $userId \n";
     })
     ->mapTo(0)
     //TODO: Figure out how to exclude the first event out of the main result
     # Second end point reached
     # Fetch stocks add up all of the user's stock prices
     ->merge(fetchEndPointStream("http://localhost:8002/stocks?id=$userId")
            ->map(function ($stock) {
                 list($symbol, $shares) = [$stock->symbol, $stock->shares];
                 echo "[DEBUG] Found stock symbol: $symbol \n";
                 return [$symbol, $shares];
            })
            ->flatMap(function ($stockData) {
                  list($symbol, $shares) = $stockData;
                  return Promise::toObservable(Promise::resolved(
                      curl("http://download.finance.yahoo.com/d/quotes.csv?s=$symbol&f=sa&e=.csv")))
                    ->map('str_getcsv')
                    ->map(function ($result) use ($shares) {
                        list($symbol, $price) = $result;
                        return $price * $shares;
                    });
            })
      )
    # Thirdf end point reached
    # Fetch accounts add up all of the user's accounts
    ->merge(fetchEndPointStream("http://localhost:8003/accounts?id=$userId")
              ->doOnNext(function ($account) {
                 echo "[DEBUG] Found account of type: $account->account_type \n";
              })
             ->map(P::prop('balance'))
      )
    ->reduce('adder', 0)
    ->subscribeCallback(
       function ($total) {
         echo "Computed user's total balance to: $total\n";  // format in dollars
       }
   );
}

findTotalBalance(2);
