#!/usr/local/bin/php7
<?php
/**
 * Building an aggregator of multiple endpoints using RxPHP (refactored version)
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

function findTotalBalance(int $userId): Subscription {
  return
     findUser($userId)
     ->merge(
        readStocks($userId)
          ->flatMap('getCurrentStockPrices')
      )
    ->merge(readAccounts($userId))
    ->reduce('adder', 0)
    ->catchError(function (\Exception $e) {
        return Observable::error($e);
    })
    ->subscribeCallback(
       function ($total) {
         echo "Computed user's total balance to: ". money_format('%i', $total). "\n";
       },
       function ($error) {
         echo "Error!\n";
       }
   );
}

findTotalBalance(2);

/*------------------------------------------*/
/*            Helper streams                */
/*------------------------------------------*/
/**
 * Read accounts (third endpoint)
 */
function readAccounts(int $userId): Observable {
  return fetchEndPointStream(ACCOUNTS_SERVICE . "?id=$userId")
      ->doOnNext(function ($account) {
         trace("Found account of type: $account->account_type");
      })
     ->map(P::prop('balance'));
}

/**
 * Use Yahoo service to fetch current stock prices
 */
function getCurrentStockPrices($stockData): Observable {
  list($symbol, $shares) = $stockData;
  return Promise::toObservable(Promise::resolved(
        curl("http://download.finance.yahoo.com/d/quotes.csv?s=$symbol&f=sa&e=.csv")))
      ->retry(1)
      ->map('str_getcsv')
      ->map(function ($result) use ($shares) {
          list($symbol, $price) = $result;
          return $price * $shares;
      });
}

/**
 * Read stocks stream (second endpoint)
 */
function readStocks(int $userId): Observable {
  return fetchEndPointStream(STOCKS_SERVICE . "?id=$userId")
     ->retry(1)
     ->map(function ($stock) {
          list($symbol, $shares) = [$stock->symbol, $stock->shares];
          trace("Found stock symbol: $symbol");
          return [$symbol, $shares];
     });
}

/**
 * Find user stream (first endpoint)
 */
function findUser(int $userId): Observable {
    return Observable::just($userId)
       ->map('isValidNumber')
       ->flatMapLatest(function () {
          return fetchEndPointStream(USERS_SERVICE .  "/users");
       })
       ->retry(1)
       ->filter(function ($user) use ($userId) {
          return $user->id === $userId;
       })
       ->map(P::prop('id'))
       ->doOnNext(function ($userId) {
          trace("Found user with ID: $userId");
       })
       ->skip(1);
}

/**
 * Tracer function to print DEBUG messages
 */
function trace(string $message): void {
    consoleLog(DEBUG, LEVEL)($message);
}

/**
 * Stream that fetches the contents of  particular URL endpoint and
 * wraps it in an array
 */
function fetchEndPointStream(string $endpoint): Observable {
  return Observable::just($endpoint)
    ->flatMap(function ($url) {
         return Promise::toObservable(Promise::resolved(curl($url)));
    })
    ->retry(1)
    ->map('json_decode')
    ->flatMap(function ($data) {
      return is_array($data) ? Observable::fromArray($data)
        : Observable::just($data);
    });
}
