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
error_reporting(-1);
ini_set('display_errors', 'on');

/** Conditionally includes native implementation */
require_once __DIR__ . '/../../../src/Functional/_import.php';
/* @var Functional\Exceptions\InvalidArgumentException */
require_once __DIR__ . '/../../../src/Functional/Exceptions/InvalidArgumentException.php';
/* @var Functional\AbstractTestCase */
require_once __DIR__ . '/AbstractTestCase.php';
/* @var Functional\MathDataProvider */
require_once __DIR__ . '/MathDataProvider.php';

if (extension_loaded('functional')) {
    error_log('NATIVE');
} else {
    error_log('USERLAND');
}
