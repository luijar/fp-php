<?php 
    // Chapter 01
	// Luis Atencio

	require '../functions/Combinators.php';
	require '../../vendor/autoload.php';

// helpers
	function println($str) {
		print "\n${str}\n";
	}

// Excerscise 1
	$concat2 = function ($s1, $s2) {
		return $s1. ' '. $s2;
	};

	$addExclamation = function ($s) {
		return "${s}!\n";
	};

	$repeat3 = function ($s) {
		$result = $s;
		for($i = 0; $i < 2; $i++) {
			$result.= $s;	
		}
		return $result;
	};

	$run = Combinators::compose($addExclamation, $repeat3, $concat2);

	println($run('Hello', 'FP'));

// ------------------------------------------------//

// Excersise 2
	$file = fopen("ch01.txt", "w");	
	$bytesWritten = fwrite($file, 'Hello FP!');
	
	println('Wrote: '. $bytesWritten. ' bytes');

// Excercise 3
	use Cypress\Curry as C;

	$adder = function ($a, $b, $c, $d) {
  		return $a + $b + $c + $d;
	};

	$firstTwo = C\curry($adder, 1, 2);
	println($firstTwo(3, 4)); // output 10

// Exercise 4
	$writeFile = @C\curry(fwrite, $file);
	$bytesWritten = $writeFile('Hello FP with Curry');
	println('Wrote: '. $bytesWritten. ' bytes');

// Excersise 5
	$run = Combinators::compose($writeFile, $addExclamation, $repeat3, $concat2);

	$bytesWritten = $run('Hello', 'FP!');
	println('Wrote '. $bytesWritten. ' bytes');

// Exercise 6
	$array = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
	for($i = 0; $i < count($array); $i++) {
	   $array[$i] = pow($array[$i], 2);
	}
	println(print_r($array, true)); //-> [0, 1, 4, 9, 16, 25, 36, 49, 64, 81]


// Exercise 7
	$array = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
	
	$square = function ($num) {
		return pow($num, 2);
	};
	
	$result = array_map($square, $array);
	
	println(print_r($result, true)); //-> [0, 1, 4, 9, 16, 25, 36, 49, 64, 81]

// Exercise 8
	class Container {
		protected $_value;

		private function __construct($value) {			
			$this->_value = $value;				
		}

		public static function of($val) {			
			return new static($val);	
		}
		
		public function map(callable $f) {			
			return static::of(call_user_func($f, $this->_value));		
		}

		public function __toString() {
        	return "Container[ {$this->_value} ]";
    	}

    	public function __invoke() {
        	return empty($this->_value) ? 'Nothing': $this->_value;
    	}
	}

	$c = Container::of('</ Hello FP >')->map(@htmlspecialchars)->map(@strtolower);
	println($c);
	println($c());

	$c = Container::of('Hello FP')->map($repeat3)->map(@strlen);

	$c = Container::of([1,2,3])->map(@array_reverse);
	println(print_r($c(), true));

	$c = Container::of(null)->map(@array_reverse);
	println(print_r($c(), true));


// Exercise 9
	class SafeContainer extends Container {
		
		// Performs null checks
		public function map(callable $f) {
		 	if(!empty($this->_value)) {
		 		return static::of(call_user_func($f, $this->_value));			
		 	}
		 	return static::of(null); 			
		}	
	}	

	$c = SafeContainer::of(null)->map(@array_reverse);
	println(print_r($c(), true));


