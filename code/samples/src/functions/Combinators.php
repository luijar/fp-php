<?php 
	class Combinators {		
	  
	  /**
		*  Performs the composition of N functions
		*/
		public static function compose() {
			$functions = array_reverse(func_get_args());
		    return function() use($functions) {
		        $params = func_get_args();
		        return array_reduce($functions,
	            	function($result, $next) use ($params) {
                        return $result === null
                            ? call_user_func_array($next, $params)
                            : $next($result);
                    }
		        );
		    };		
		}
	}
?>