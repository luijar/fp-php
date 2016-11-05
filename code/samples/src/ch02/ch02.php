<?php
  /**  Chapter 2 
    *  Author: Luis Atencio
	*/

	include_once '../ch01/ch01.php';

	function adderOf($a) {
		return function ($b) use ($a) {
			return $a + $b;	
		};
	}		
	
	$add5 = adderOf(5);

	$add5(5); //-> 10

	println('is callable: '. is_callable($add5));

	println('Adding 5 to 5: '. $add5(5));

	function apply(callable $operator, $a, $b) {
		return $operator($a, $b);
	}

	$add = function ($a, $b) {
		return $a + $b;
	};

	$divide = function ($a, $b) {
		return $a / $b;
	};

	println(apply($add, 5, 5));

	println(apply($divide, 5, 5));	
	

	function apply2(callable $operator) {
		return function ($a, $b) use ($operator) {
			return $operator($a, $b);
		};
	}

	println(apply2($add)(5, 5));

	println(apply2($divide)(5, 5));	
	
	//apply2($divide)(5, 0);

	$safeDivide = function (float $a, float $b): float {   
	   return empty($b) ? NAN : $a / $b;
	};

	println(

		apply($divide, 5, 0)

		);

	println('Is NAN: '. is_nan(apply2($safeDivide)(5, 0)));

	println('1 + NAN' . 1 + NAN);

	class SafeNumber extends Container {
		
		public function map(callable $f) {					
			if(!isset($this->_value) || is_nan($this->_value)) {
				return static::of(); // empty container				
			}
			else {
				return static::of(call_user_func($f, $this->_value));			
			}			
		}
	}

	function safeDivide2($a, $b): SafeNumber {   
	   return SafeNumber::of(empty($b) ? NAN : $a / $b);
	}

	function divideBy(float $a): callable {
		return function (float $b) use ($a): float {
			return $a / $b;
		};
	}

	function square(int $a): int {
		return $a * $a;
	};

	function increment(int $a): int {
		return $a + 1;
	};

	println(apply2(@safeDivide2)(5, 1)->map(@square)->map(@increment));

	println(apply2(@safeDivide2)(5, 0)->map(@square)->map(@increment));

	println(apply2(function ($a, $b): SafeNumber {   
	   return SafeNumber::of(empty($b) ? NAN : $a / $b);
	})
	(7, 2));
	
	println('As closure: '. $add->call(new class {}, 2, 3));

	// Mixin
	$filter = function (callable $f): Container {
		return Container::of(call_user_func($f, $this->_value) ? $this->_value : 0);
	};

	$wrappedInput = Container::of('abc');	

	$validatableContainer = $filter->bindTo($wrappedInput, 'Container');

	println($validatableContainer('is_numeric')->map(adderOf(25)));
	
	// Closure
	$global = 'Global';
	$msg = 'Hello';

	function outer($args) {
	  global $global;
      
      // function arguments + global data via the 'global'

      return function () use ($args) {
      	  // function arguments + outer functions closure via 'use' + global data via 'global'          
      };
	}

	$inner = outer('Arg');
	$inner();


