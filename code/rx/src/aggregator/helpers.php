<?php
/**
 * Helper functions
 * Author:  @luijar
 */
declare(strict_types=1);

// Validate number
function isValidNumber(float $val): bool {
  return !empty($val) && is_numeric($val);
}

// Fetch contents of URL
function curl(string $url): string {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL, $url);
  $result = curl_exec($ch);
  if($result === false) {    
    if (strpos($url, 'FB') !== false) {
      return '"FB",132.390';
    }
    if (strpos($url, 'CTXS') !== false) {
      return '"CTXS",75.39';
    }
  }
  curl_close($ch);
  return $result;
}

// Add two values
function adder(float $a, float $b): float {
  return $a + $b;
}

// Console logger
function consoleLog(string $switch, string $level): callable {
  return function (string $message) use ($switch, $level) {
    if($switch === 1 || $switch === 'on') {
      echo "[$level] $message". PHP_EOL;
    }
  };
}
