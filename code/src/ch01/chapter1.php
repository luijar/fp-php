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


