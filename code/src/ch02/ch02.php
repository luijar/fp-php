<?php

// helpers
	function println($str) {
		print "\n${str}\n";
	}

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


	

