<?php
/**
 * Write seed stock data to database and expose service endpoint
 * @author luijar
 */
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__. '/../bootstrap.php';

use Illuminate\Database\Capsule\Manager as DB;
use Carbon\Carbon;

// Query users
$stocks = DB::table('stocks')->get();

// Seed if empty
if($stocks->isEmpty()) {
    DB::table('stocks')->insert([
      'symbol'  => 'FB',
      'shares'  => 10,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now()
    ]);

    DB::table('stocks')->insert([
      'symbol'  => 'CTXS',
      'shares'  => 15,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now()
    ]);
}

print_r($stocks);


// $option = $_GET['option'];
//
// if ( $option == 1 ) {
//     $data = [ 'a', 'b', 'c' ];
//     // will encode to JSON array: ["a","b","c"]
//     // accessed as example in JavaScript like: result[1] (returns "b")
// } else {
//     $data = [ 'name' => 'God', 'age' => -1 ];
//     // will encode to JSON object: {"name":"God","age":-1}
//     // accessed as example in JavaScript like: result.name or result['name'] (returns "God")
// }
//
// header('Content-type: application/json');
// echo json_encode( $data );
