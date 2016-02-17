<?php
include_once '../ch01/ch01.php';

// Chapter 02
	var_dump(function() { });

	function adderOf($a) {
		return function ($b) use ($a) {
			return $a + $b;	
		};
	}		
	
	$add5 = adderOf(5);

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
		return function($a, $b) use ($operator) {
			return $operator($a, $b);
		};
	}

	println(apply2($add)(5, 5));

	println(apply2($divide)(5, 5));	
	
	//apply2($divide)(5, 0);

	$safeDivide = function ($a, $b) {   
	   return empty($b) ? NAN : $a / $b;
	};

	println(apply2($safeDivide)(5, 0));

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


	function safeDivide2($a, $b) {   
	   return SafeNumber::of(empty($b) ? NAN : $a / $b);
	}

	$square = function ($a) {
		return $a * $a;
	};

	$increment = function ($a) {
		return $a + 1;
	};

	println(apply2(@safeDivide2)(5, 1)->map($square)->map($increment));

	println(apply2(@safeDivide2)(5, 0)->map($square)->map($increment));

