<?php
/**
 *  Chapter 4 samples
 *  Author : Luis Atencio
 */
include_once '../ch01/ch01.php';

abstract class Computation {

	protected $_a;
	protected $_b;

	public function __construct($a, $b) {
		$this->_a = $a;
		$this->_b = $b;	
	}

	public abstract function apply();
}

class Add extends Computation {

	public function apply() {
		return $this->_a + $this->_b;
	}
}

class Divide extends Computation {
	
	public function apply() {
		return $this->_a / $this->_b;
	}
}

$add = new Add(5, 5);
println($add->apply()); //->10

$divide = new Divide(5,5);
println($divide->apply()); //->1

function apply(callable $operator) {
	return function($a, $b) use ($operator) {
		return $operator($a, $b);
	};
}

$add = function ($a, $b) {
	return $a + $b;
};

$divide = function ($a, $b) {
	return $a / $b;
};

apply($add)(5, 5); //-> 10
apply($divide, 5, 5); //-> 1

println(apply($add)(5, 5));
println(apply($divide)(5, 5));	


$safeDivide = function ($a, $b) {   
   return empty($b) ? NAN : $a / $b;
};

apply($safeDivide)(5, 0); //-> NAN

println(apply($safeDivide)(5, 0));

require_once '../../vendor/autoload.php';
require_once '../ch08/model/User.php';
require_once '../ch08/model/Account.php';	

use \Model\Account as Account;
use \Model\User as User;
use \Rx\Observable as Observable;
use \Rx\Observer as Observer;


// SELECT firstname 
// FROM users 
// WHERE firstname IS NOT NULL 
// ORDER BY firstname DESC 
// LIMIT 1;


print_r(

P::pipe(
  '\Model\User::query',
	P::map(P::prop('firstname')),	
	P::filter(function ($n) { return !empty($n); }),
   'P::reverse',
	P::take(1)
)()

);

P::compose(
	P::take(1),
   'P::reverse',	
	P::filter(function ($n) { return !empty($n); }),
	P::map(P::prop('firstname')),	
	'\Model\User::query'
)();

  // PHP Warning:  f() expects 
  // at least 2 parameters, 1 given

// $format = P::compose('addExclamation', $repeat(2), 'strtoupper');

// $format('Hello World'); //-> HELLO WORLD HELLO WORLD!

$applyTax = P::curry2(function ($rate, $amount) {
	return $amount  + ($amount * ($rate / 100));
});

$applyTax(6.0);      //-> Closure
$applyTax(6.0, 100); //-> 106
$applyTax(6.0)(100); //-> 106

// $total = P::compose($currency('USD'), $applyTax(6.0), 'P::sum');

// $total([10.95, 16.99, 25.99]); //-> USD 57.1658

// function findUserById($id) {
// 	//...
// 	return Option::fromValue($user);
// }

// $userOpt = Option::fromValue(findUserById(10)); //-> Option(Option(User))



// $userOpt->flatMap(P::prop('address'))->map(P::prop('country'))->get();


$a = function () {
            yield 1;
            yield 3;
        };
        $modulo = function ($number) {
            return $number % 2;
        };
        $modded = P::toArray(P::map($modulo, $a()));
print_r($modded);


	// composition with currying

	$input = 'A complex system that works is 
	          invariably found to have evolved 
	          from a simple system that worked';

	$explodeOnSpace = P::curry2('explode')(' ');
	$countWords = P::compose('count', $explodeOnSpace);
	$countWords($input); //-> 17


