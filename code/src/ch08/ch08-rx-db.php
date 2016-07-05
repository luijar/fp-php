<?php 
    // Chapter 08 Database Example
    // Uses http://php.net/manual/en/book.mysqli.php
    // http://codular.com/php-mysqli
	// Luis Atencio

require_once '../functions/Combinators.php';
require_once '../../vendor/autoload.php';
require_once 'model/User.php';
require_once 'model/Account.php';	
require_once 'common.php';

// --- samples --- //	
println('Example 1 - Filter simple');
	\Rx\Observable::fromArray(\Model\User::all())
	 	->filter(function ($user)  { 
	 		return $user->getFirstname() === 'Luis';
	 	})
		->subscribe(new \Rx\Observer\CallbackObserver(
		    function ($user) {
		        echo 'Next: ', $user->getEmail(), PHP_EOL;
		    },
		    function (Exception $ex) {
		        echo 'Error: ', $ex->getMessage(), PHP_EOL;
		    },
		    function () {
		        echo 'Completed', PHP_EOL;
		    }
	 	));


println('Example 2 - Take');
	\Rx\Observable::fromArray(\Model\User::all())
	 	->take(1)
		->subscribe(new \Rx\Observer\CallbackObserver(
		    function ($user) {
		        echo 'Next: ', $user->getEmail(), PHP_EOL;
		    },
		    function (Exception $ex) {
		        echo 'Error: ', $ex->getMessage(), PHP_EOL;
		    },
		    function () {
		        echo 'Completed', PHP_EOL;
		    }
	 	));

println('Example 3 - Just and Map');
	\Rx\Observable::just(\Model\User::all())		
	 	->map(function ($results) {	 		
	 		return count($results);
	 	})
		->subscribe($stdoutObserver());





