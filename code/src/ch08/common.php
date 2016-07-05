<?php 
function println($str) {
	print "\n${str}\n";
}

function asString($value) {
    if (is_array($value)) {
        return json_encode($value);
    }
    return print_r($value, true);
}

$stdoutObserver = function ($prefix = '') {
    return new Rx\Observer\CallbackObserver(
        function ($value) use ($prefix) { echo $prefix . "Next value: " . asString($value) . "\n"; },
        function ($error) use ($prefix) { echo $prefix . "Exception: " . $error->getMessage() . "\n"; },
        function ()       use ($prefix) { echo $prefix . "Complete!\n"; }
    );
};