<?php 
    // Chapter 08
	// Luis Atencio

	require_once '../functions/Combinators.php';
	require_once '../../vendor/autoload.php';
	require_once 'model/User.php';
	require_once 'model/Account.php';	
	require_once 'common.php';	

println('Example 0');

\Rx\Observable::just('Hello World!')
	->subscribe($stdoutObserver());

println('Example 1');
	\Rx\Observable::fromArray([1, 2, 3, 4])->subscribe(new \Rx\Observer\CallbackObserver(
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


println('Example 2 Reduce Map Filter with Curry');

	$isEven = function ($num) {
		return $num % 2 === 0;
	};

	$add = function ($x, $y) {
		return $x + $y;
	};

	$raiseTo = function ($power, $num) {
		return pow ($num, $power);
	};
	
	$computeSquare = P::curry2($raiseTo)(2);

	\Rx\Observable::fromArray([1, 2, 3, 4])
		->filter($isEven)
		->map($computeSquare)
		->reduce($add, 0)
		->subscribe($stdoutObserver());  //-> 20

println('Example 3 - Map');
	\Rx\Observable::fromArray([1, 2, 3, 4])
	 	->map(function ($num) {
	 		return $num * $num;	
	 	})
		->subscribe(new \Rx\Observer\CallbackObserver(
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
