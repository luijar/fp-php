<?php namespace App\Http\Controllers;

use P as F;
use Log;
use App\Item;
use App\User;
use App\Util\Tuple;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOption\Option as Nullable;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class Main extends Controller {
  
    /**
     * GET
     */
    public function __invoke(): View {
        // $point = Tuple::create('double', 'double');

        // $point(1.0, 2.5); // Tuple(1, 2.5)

        // Log::info('Point is: '. $point(1.5, 3.0)[1]); // 3.0


        return view('main')->with('items', Item::all()) ;
    }

    /**
     * POST
     */
    public function newItem(Request $request): RedirectResponse {

        $newItem = Nullable::fromValue($request->input('text'))
                ->reject('')                
                ->filter(F::allPass(['strlen']))                                
                ->map(function ($content) {
                    return Item::create([
                        'content' => $content
                    ]);
                })                
                ->getOrCall(function () {
                    Log::info('New item content not found. Skipping...');
                });

        return redirect('/main')->with('status', 'New item added!');
    }

    /**
     * POST
     */
    public function deleteItem($id): RedirectResponse {

        Log::info('Deleteing '. $id);
        return redirect('/main')->with('status', 'New item added!');
    }
}