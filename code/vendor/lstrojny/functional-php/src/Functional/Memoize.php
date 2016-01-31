<?php
/**
 * Copyright (C) 2011-2015 by Lars Strojny <lstrojny@php.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Functional;

use Functional\Exceptions\InvalidArgumentException;

/**
 * Memoizes callbacks and returns their value instead of calling them
 *
 * @param callable $callback Callable closure or function
 * @param array $arguments Arguments
 * @param array|string $key Optional memoize key to override the auto calculated hash
 * @return mixed
 */
function memoize($callback, array $arguments = array(), $key = null)
{
    static $storage = array();

    if ($callback === null) {
        $storage = array();

        return null;
    }

    InvalidArgumentException::assertCallback($callback, __FUNCTION__, 1);

    static $keyGenerator = null;
    if (!$keyGenerator) {
        $keyGenerator = function($value) use (&$keyGenerator) {
            $type = gettype($value);
            if ($type === 'array') {
                $key = join(':', map($value, $keyGenerator));
            } elseif ($type === 'object') {
                $key = get_class($value) . ':' . spl_object_hash($value);
            } else {
                $key = (string) $value;
            }

            return $key;
        };
    }

    if ($key === null) {
        $key = $keyGenerator(array_merge(array($callback), $arguments));
    } else {
        $key = $keyGenerator($key);
    }

    if (!isset($storage[$key]) && !array_key_exists($key, $storage)) {
        $storage[$key] = call_user_func_array($callback, $arguments);
    }

    return $storage[$key];
}
