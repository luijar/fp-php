<?php namespace App\Http\Middleware;

use Log;
use Closure;
use Illuminate\Http\Request;
use PhpOption\Option as Nullable;

/**
 * Automatically convert any passed input items into nullables
 * Author: Luis Atencio
 */
class NullableParams {

	public function handle(Request $request, Closure $next, string $inputParam = 'input') {
        
        $value = $request->input($inputParam);

        if(!is_array($value)) {
        	$request->merge([$inputParam => Nullable::fromValue($value)]);
        }
        else {
        	$nullables = array_map(function ($id) {
				return Nullable::fromValue($id);
			}, $value);	
			$request->merge([$inputParam => $nullables]);
        }
        return $next($request);
    }    
}