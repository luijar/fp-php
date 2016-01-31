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

use ArrayIterator;

class ProductTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->intArray = array(1 => 1, 2, "foo" => 3, 4);
        $this->intIterator = new ArrayIterator($this->intArray);
        $this->floatArray = array("foo" => 1.5, 1.1, 1);
        $this->floatIterator = new ArrayIterator($this->floatArray);
    }

    public function test()
    {
        $this->assertSame(240, product($this->intArray, 10));
        $this->assertSame(240, product($this->intArray, 10));
        $this->assertSame(24, product($this->intArray));
        $this->assertSame(24, product($this->intIterator));
        $this->assertEquals(1.65, product($this->floatArray), '', 0.01);
        $this->assertEquals(1.65, product($this->floatIterator), '', 0.01);
    }

    /** @dataProvider Functional\MathDataProvider::injectErrorCollection */
    public function testElementsOfWrongTypeAreIgnored($collection)
    {
        $this->assertEquals(3, product($collection), '', 0.01);
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\product() expects parameter 1 to be array or instance of Traversable');
        product('invalidCollection', 'strlen');
    }
}
