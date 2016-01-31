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
use Traversable;

/**
 * Partitions a collection by callback result. The truthy partition is the first one
 * (array index "0"), the falsy the second one (array index "1")
 *
 * @param Traversable|array $collection
 * @param callable $callback
 * @return array
 */
function partition($collection, $callback)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);
    InvalidArgumentException::assertCallback($callback, __FUNCTION__, 2);

    $partitions = array(
        0 => array(),
        1 => array()
    );

    foreach ($collection as $index => $element) {
        $partitionKey = call_user_func($callback, $element, $index, $collection) ? 0 : 1;
        $partitions[$partitionKey][$index] = $element;
    }

    return $partitions;
}
