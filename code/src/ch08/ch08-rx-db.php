<?php 
    // Chapter 08 Database Example
    // Uses http://php.net/manual/en/book.mysqli.php
    // http://codular.com/php-mysqli
	// Luis Atencio

	require_once '../functions/Combinators.php';
	require_once '../../vendor/autoload.php';
	require_once 'model/User.php';
	require_once 'model/Account.php';	

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

	foreach(\Model\User::all() as $user) {

	}

	$user = new \Model\User();
	$user->setFirstname('Luis');
	$user->setLastname('Atencio');
	$user->setEmail('luis.ss@as.com');
	//$user->save();





