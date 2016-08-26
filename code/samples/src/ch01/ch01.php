<?php 
    // Chapter 01
	// Luis Atencio

	require '../functions/Combinators.php';
	require '../../vendor/autoload.php';

// helpers
	function println($str) {
		print "\n${str}\n";
	}

// Exercise 0

	$data = [1,2,3,4,5,6,7,8,9];

	array_reduce(
		array_map(function ($evenNum) {
			return $evenNum * $evenNum;
			},
			array_filter($data, function ($num) {
				return $num % 2 === 0;
			})
		),
		function ($acc, $next) {
			return $acc + $next;
		}		
	); // -> 120

	println('Filter -> Map -> Reduce: '. $result);

// Excerscise 1
	$concat2 = function ($s1, $s2) {
		return $s1. ' '. $s2;
	};

	$addExclamation = function ($s) {
		return "${s}!\n";
	};

	println('Repeat 3');
	$repeat3 = function ($s) {
		$result = [];
		for($i = 0; $i < 3; $i++) {
			$result[] = $s;	
		}
		return implode(' ', $result);
	};

	$repeat = function ($times = 1, $s) {
		$result = [];
		for($i = 0; $i < $times; $i++) {
			$result[] = $s;	
		}
		return implode(' ', $result);
	};

	//echo $repeat(PHP_INT_MAX, 'Thanks!');		


	//define('SOME_CONST', 'value');
	//const SOME_CONST = 'value';


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

// Exercise 7.5	
	// Array map is not immutable

	$result = 

	array_map('strtolower', ['HELLO']); //-> hello

	print_r($result);

	$joe = new \stdclass();
	$joe->firstname = 'Joe';
	$joe->lastname = 'Smith';

	$dolly = P::map(function ($j) {
		$j->firstname = 'Dolly';	
		return $j;
	}, [$joe]);
	
	println('Joe:');
	print_r($joe);

	println('Dolly:');
	print_r(P::toArray($dolly));	

	$sam = array_map(function ($j) {
		$j->firstname = 'Sam';	
		return $j;
	}, [$joe])[0];

	println('Joe:');
	print_r($joe);

	println('Sam:');
	print_r($sam);

// Exercise 8

	// > ls -l | grep key | less

	class Container {
		protected $_value;

		private function __construct($value) {			
			$this->_value = $value;				
		}

		public static function of($val = null) {			
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

	$data = [1,2,3,4,5];

	sort($data);


	strtolower('HELLO'); //-> 'hello'

	$c = Container::of('</ Hello FP >')->map('htmlspecialchars')->map('strtolower');
	
	$c(); //-> Container(&lt;/ hello fp &gt;)
	
	
	println($c());  
	println($c);
	

	$c = Container::of('Hello FP')->map($repeat3)->map(@strlen);

	$c = 

		Container::of([1,2,3])->map('array_reverse'); //-> Container([3,2,1])
	

	println(print_r($c(), true));

	$c = 

	   Container::of(null)->map('array_reverse')();
	
	println(print_r($c, true));


	//container_map('strtolower', Container::of('HELLO'); //-> Container('hello')

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

	class Counter {

		private $_value;

		public function __construct($init) {
			$this->_value = $init;
		}

		public function increment() {
			return $this->_value++;
		}

		public function __invoke() {
			return $this->_value++;	
		}

		public static function increment2($val) {
			return $val  + 1;
		}
	}

	$c1 = new Counter(0);
	println('Count: '. $c1->increment());
	println('Count: '. $c1->increment()); 
	println('Count: '. $c1->increment()); 

	$c2 = new Counter(10);
	println('Count: '. $c2()); 
	println('Count: '. $c2()); 
	println('Count: '. $c2()); 

	println('Count: '. Counter::increment2(100)); 

	$user = new class {
	    public function getAddress() {
	    	$this->address = new class {
	    		public function getCountry() {
	    			$this->country = 'US';
	    			return $this->country;
	    		}	
	    	};
	    	return $this->address;
	    }
	};

	function printCountry($user) {
		$addr = $user->getAddress();
		if(!empty($addr)) {
			$country = $addr->getCountry();
			if(empty($country)) {
				return 'Country not found!';
			}	
			return $country;
		}
		return 'Country not found!';
	}

	println(printCountry($user));

	use \PhpOption\Option as Option;

	println(

		Option::fromValue($user)
			->map(P::prop('address'))
			->map(P::prop('country'))
			->getOrElse('Country not found!')
	);	
	

