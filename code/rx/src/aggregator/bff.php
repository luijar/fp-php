#!/usr/local/bin/php
<?php
/**
 * Building an aggregator of multiple endpoints using RxPHP
 * Author:  @luijar
 */
declare(strict_types=1);
setlocale(LC_MONETARY, 'en_US');

require_once __DIR__ . '/vendor/autoload.php';
require_once 'helpers.php';

use Rx\{
  Observable as Observable,
  Observer as Observer,
  React\Promise as Promise,
  Disposable\CallbackDisposable as Subscription
};

const DEBUG = 'on';
const LEVEL = 'DEBUG';

const ACCOUNTS_SERVICE = 'http://accounts.sunshine.com';
const USERS_SERVICE = 'http://users.sunshine.com';
const STOCKS_SERVICE = 'http://stocks.sunshine.com';

/**
 * fetchEndPointStream :: String -> Observable
 * Stream that fetches the contents of  particular URL endpoint and
 * wraps it in an array
 */
function fetchEndPointStream(string $endpoint): Observable {
  return Observable::just($endpoint)
    ->doOnNext(function ($endpoint) {
       trace("Querying ${endpoint} ...");
    })
    ->flatMap(function ($url) {
         return Promise::toObservable(Promise::resolved(curl($url)));
    })
    ->doOnNext(function () {
       trace("Decoding JSON ...");
    })
    ->map('json_decode')
    ->flatMap(function ($data) {
      return is_array($data) ? Observable::fromArray($data)
        : Observable::just($data);
    });
}

function findTotalBalance(int $userId): Subscription {
  return Observable::just($userId)
    ->doOnNext(function ($userId) {
       trace("Validating  user ID: $userId ...");
    })
     ->map('isValidNumber')
     # First end point reached
     # Valid name provided, fetch all users
     ->flatMapLatest(function () {
        return fetchEndPointStream(USERS_SERVICE);
     })
     ->filter(function ($user) use ($userId) {
        return $user->id === $userId;
     })
     ->map(P::prop('id'))
     ->doOnNext(function ($userId) {
        trace("Found user with ID: $userId");
     })
     ->skip(1)
     # Second end point reached
     # Fetch stocks add up all of the user's stock prices
     ->merge(fetchEndPointStream(STOCKS_SERVICE. "?userid=$userId")
            ->map(function ($stock) {
                 list($symbol, $shares) = [$stock->symbol, $stock->shares];
                 trace("Found stock symbol: $symbol");
                 return [$symbol, $shares];
            })
            ->doOnNext(function () {
               trace("Querying External Yahoo Service ...");
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
    # Third end point reached
    # Fetch accounts add up all of the user's accounts
    ->merge(fetchEndPointStream(ACCOUNTS_SERVICE. "?id=$userId")
              ->doOnNext(function ($account) {
                 trace("Found account of type: $account->account_type");
              })
             ->map(P::prop('balance'))
      )
    ->doOnNext(function () {
       trace("Aggregating total balance...");
    })
    ->reduce('adder', 0)
    ->catchError(function (\Exception $e) {
        return Observable::error($e);
    })
    ->subscribeCallback(
       function ($total) {
         echo "Computed user's total balance to: ". money_format('%i', $total). "\n";
       },
       function ($error) {
         echo "Oh oh! $error \n";
       },
       function () {
         echo "Done! \n";
       }
   );
}

/**
 * Tracer function to print DEBUG messages
 */
function trace(string $message) {
    consoleLog(DEBUG, LEVEL)($message);
}

findTotalBalance(2);
