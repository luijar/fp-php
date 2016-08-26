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

use \Model\Account as Account;
use \Model\User as User;
use \Rx\Observable as Observable;
use \Rx\Observer as Observer;

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


println('Example 4 - Get all accounts of type SAVING');
	\Rx\Observable::fromArray(\Model\Account::all())		
	    ->filter(function (Account $account) {
	    	return $account->getType() === 'SAVINGS';	
	    })
	 	->map(function (Account $savingsAccount) {	 		
	 		return $savingsAccount->getBalance();
	 	})
		->subscribe($stdoutObserver());


println('Withdraw 1000 from Luis SAVINGS account');
	$id = 1;
	Observable::just($id)
		->map(function ($userId) {
			return User::find($userId);	
		})
		->doOnNext(function (User $user) {
			printf("Found user: %s \n", $user->getEmail());
		})
		->flatMap(function (User $user) {
			return Observable::fromArray(Account::query('user_id', $user->getId()));
		})
		->takeWhile(function (Account $account) {
			return $account->getType() === 'SAVINGS';
		})
		->doOnNext(function (Account $account) {
			printf("Found savings account. Current balance: %d \n", $account->getBalance());			
		})
		->map(function (Account $account) {
			return $account->withdraw(1000)->save();			
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
