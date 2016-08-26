<?php namespace App\Http\Middleware;

use Log;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use PhpOption\Option as Nullable;

/**
 * Automatically convert any passed input items into nullables
 * Author: Luis Atencio
 */
class NullableParams {

	public function handle(Request $request, Closure $next, string $inputParam = 'input'): RedirectResponse {
        
		$nullables = array_map(function ($id) {
			return Nullable::fromValue($id);
		}, $request->input($inputParam));

		$request->merge(['items' => $nullables]);

        return $next($request);
    }    
}