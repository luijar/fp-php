<?php 
    // Chapter 08
	// Luis Atencio

	require '../functions/Combinators.php';
	require '../../vendor/autoload.php';

// helpers
	function println($str) {
		print "\n${str}\n";
	}

	$source = \Rx\Observable::fromArray([1, 2, 3, 4]);

	$subscription = $source->subscribe(new \Rx\Observer\CallbackObserver(
	    function ($x) {
	        echo 'Next: ', $x, PHP_EOL;
	    },
	    function (Exception $ex) {
	        echo 'Error: ', $ex->getMessage(), PHP_EOL;
	    },
	    function () {
	        echo 'Completed', PHP_EOL;
	    }
 	));

 	
