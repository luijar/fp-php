<?php 

macro { T_VARIABLE·COLLECTION->map(···expression) } >> {
    Decorators\Array(T_VARIABLE·COLLECTION)->map(···expression)->toArray()
}
​
Decorators\Array($collection)->map(function($item) {
    return $item * 2;
})->toArray();

$collection = [1, 2, 3];
​
$result = $collection->map(function($item) {
    return $item * 2;
});

print_r($echo, true);