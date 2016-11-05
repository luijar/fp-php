<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__. '/../bootstrap.php';

use Illuminate\Database\Capsule\Manager as DB;
use Rx\{Observable as Observable, Observer as Observer};

Observable::fromArray([1,2,3])
	->subscribe(new \Rx\Observer\CallbackObserver(
	    function ($val) {
	        echo 'Next: '. $val. PHP_EOL;
	    },
	    function (Exception $ex) {
	        echo 'Error: ', $ex->getMessage(), PHP_EOL;
	    },
	    function () {
	        echo 'Completed', PHP_EOL;
	    }
 	));
