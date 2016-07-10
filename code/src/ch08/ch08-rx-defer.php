<?php 
    // Chapter 08
	// Luis Atencio

require_once '../functions/Combinators.php';
require_once '../../vendor/autoload.php';
require_once 'model/User.php';
require_once 'model/Account.php';	
require_once 'common.php';	

use \Rx\Observable as Observable;
use \Rx\Observer as Observer;
use \Model\Account as Account;
use \Model\User as User;

println('Example 1 - Simple defer');
Observable::defer(function () {
    	return \Rx\Observable::just(42);
	})
->subscribe($stdoutObserver());


println('Example 2 - Curl defer with flatMap using stock symbol FB');

Observable::just('FB')
	->flatMap(function ($symbol) {
			return Observable::defer(function () use ($symbol) {
				$ch = curl_init();
			    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			    curl_setopt($ch, CURLOPT_URL, "http://finance.yahoo.com/webservice/v1/symbols/{$symbol}/usd/quote?format=json");
			    $result = curl_exec($ch);
			    curl_close($ch);
				return Observable::just($result);
		});
	})
	->map('json_decode')	
	->flatMap(function ($result) {
		return Observable::fromArray($result->list->resources);
	})
	->subscribe(new Observer\CallbackObserver(
        function ($value) { echo "Next value: " . asString($value->resource->fields->name) . "\n"; }
    ));


/*
->combineLatest([$source2], function ($value1, $value2) {
    return "First: {$value1}, Second: {$value2}";
})
*/

 println('Buy 10 shares of FB from Luis CHECKING account');
	
	function findSavingsAccountObs($userId) {
		return Observable::just($userId)
			->skipWhile(function ($input) {
				return empty($input);		
			})
			->map(function ($userId) {
				return User::find($userId);	
			})			
			->flatMap(function (User $user) {
				return Observable::fromArray(Account::query('user_id', $user->getId()));
			})
			->takeWhile(function (Account $account) {
				return $account->getType() === 'SAVINGS';
			});
	}

	function findSharePrice($symbol) {
		return Observable::just($symbol)
			->flatMap(function ($symbol) {
					return Observable::defer(function () use ($symbol) {
						$ch = curl_init();
					    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					    curl_setopt($ch, CURLOPT_URL, "http://finance.yahoo.com/webservice/v1/symbols/{$symbol}/usd/quote?format=json");
					    $result = curl_exec($ch);
					    curl_close($ch);
						return Observable::just($result);
				});
			})
			->map('json_decode')	
			->map(function ($result) {				
				return $result->list->resources[0]->resource->fields->price;
			});
	}

	findSavingsAccountObs(1)
		->combineLatest([findSharePrice('FB')])			
		->doOnNext(function (array $data) {
			list($account, $pricePerShare) = $data;
			printf("Found %s acccount and price per share of %d \n", $account->getType(), $pricePerShare);					        
		})
		->map(function ($data) {
			list($account, $pricePerShare) = $data;
			return $account->withdraw($pricePerShare * 10)->save();			
		})		
		->subscribe(new Observer\CallbackObserver(
		    function ($account) {
		    	printf("New account balance: %d \n", $account->getBalance());					        
		    },
		    function (Exception $ex) {
		        print 'Error: ' . $ex->getMessage();
		    },
		    function () {
		        print 'Completed!';
		    }
	 	)); 