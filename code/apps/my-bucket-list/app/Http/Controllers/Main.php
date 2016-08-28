<?php namespace App\Http\Controllers;

use P;
use Carbon;
use Log;
use App\Item;
use App\State;
use App\Service\ItemService;
use App\User;
use App\Util\Tuple;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOption\Option as Nullable;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class Main extends Controller {
  
    /*
    |--------------------------------------------------------------------------
    | Web Handlers
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    */
    /**
     * GET
     */
    public function __invoke(): View {
        // $point = Tuple::create('double', 'double');

        // $point(1.0, 2.5); // Tuple(1, 2.5)

        // Log::info('Point is: '. $point(1.5, 3.0)[1]); // 3.0

        // allow functions into with clauses

        
        return view('main')->with('items', Item::all());
    }

    /**
     * POST
     */
    public function newItem(Request $request): RedirectResponse {

        $newItem = Nullable::fromValue($request->input('text'))
                ->reject('')                
                ->filter(P::allPass(['strlen']))                                
                ->map(ItemService::class. '::createNewItem')                
                ->getOrCall(function () {
                    Log::info('New item content not found. Skipping...');
                });

        return redirect('/main')->with('status', 'New item added!');
    }

    /**
     * POST
     */
    public function deleteItems(Request $request): RedirectResponse {

        $nullableItems = $request->input('items');

        array_map(function ($nul_id) {
            return $nul_id->reject('')
                    ->map('intval')
                    ->filter(P::lt(0))
                    ->map(function ($id) {
                        Log::info("Deleting item with {$id}...");
                        return Item::destroy($id);
                    })                              
                    ->getOrCall(function () {
                        Log::info('Invalid item ID. Skipping delete...');
                        return 0;
                    });
        }, $nullableItems);

        return redirect('/main')->with('status', 'Items deleted!');
    }

    /*
    |--------------------------------------------------------------------------
    | API Handlers
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    */
    /**
     * GET
     */
    public function deleteItem($id): RedirectResponse {

        $count = Nullable::fromValue($id)
            ->reject('')
            ->map('intval')
            ->filter(P::lt(0))
            ->map(function ($id) {
                Log::info("Deleting item with {$id}...");
                return Item::destroy($id);
            })                              
            ->getOrCall(function () {
                Log::info('Invalid item ID. Skipping delete...');
                return 0;
            });
            
        return redirect('/main')->with('status', "{$count} item deleted!");      
    }
}