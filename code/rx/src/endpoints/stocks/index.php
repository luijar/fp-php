<?php
/**
 * Write seed stock data to database and expose service endpoint
 * Author:  @luijar
 */
declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__. '/bootstrap.php';

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

header('Content-type: application/json');
echo json_encode($stocks, JSON_PRETTY_PRINT);
