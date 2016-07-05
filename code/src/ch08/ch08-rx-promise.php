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