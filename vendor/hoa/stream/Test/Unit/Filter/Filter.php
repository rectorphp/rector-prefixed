<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2017, Hoa community. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */
namespace Hoa\Stream\Test\Unit\Filter;

use Hoa\Stream as LUT;
use Hoa\Stream\Filter as SUT;
use Hoa\Test;
/**
 * Class \Hoa\Stream\Test\Unit\Filter\Filter.
 *
 * Test suite of the filter class.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Filter extends \Hoa\Test\Unit\Suite
{
    public function case_constants()
    {
        $this->boolean(\Hoa\Stream\Filter::OVERWRITE)->isTrue()->boolean(\Hoa\Stream\Filter::DO_NOT_OVERWRITE)->isFalse()->integer(\Hoa\Stream\Filter::READ)->isEqualTo(\STREAM_FILTER_READ)->integer(\Hoa\Stream\Filter::WRITE)->isEqualTo(\STREAM_FILTER_WRITE)->integer(\Hoa\Stream\Filter::READ_AND_WRITE)->isEqualTo(\STREAM_FILTER_ALL);
    }
    public function case_register()
    {
        $this->when($result = \Hoa\Stream\Filter::register('foo', \_PhpScoperbd5d0c5f7638\StdClass::class))->then->boolean($result)->isTrue();
    }
    public function case_register_already_registered_do_not_overwrite()
    {
        $this->given($name = 'foo', $class = \_PhpScoperbd5d0c5f7638\StdClass::class, \Hoa\Stream\Filter::register($name, $class))->exception(function () use($name, $class) {
            \Hoa\Stream\Filter::register($name, $class);
        })->isInstanceOf(\Hoa\Stream\Filter\Exception::class)->hasMessage('Filter foo is already registered.');
    }
    public function case_register_already_registered_do_overwrite()
    {
        $this->given($name = 'foo', \Hoa\Stream\Filter::register($name, \_PhpScoperbd5d0c5f7638\StdClass::class), new \_PhpScoperbd5d0c5f7638\Mock\StdClass())->when($result = \Hoa\Stream\Filter::register($name, \_PhpScoperbd5d0c5f7638\Mock\StdClass::class, \Hoa\Stream\Filter::OVERWRITE))->then->boolean($result)->isFalse();
    }
    public function case_register_empty_name()
    {
        $this->exception(function () {
            \Hoa\Stream\Filter::register('', \_PhpScoperbd5d0c5f7638\StdClass::class);
        })->isInstanceOf(\Hoa\Stream\Filter\Exception::class)->hasMessage('Filter name cannot be empty ' . '(implementation class is StdClass).');
    }
    public function case_register_unknown_class()
    {
        $this->exception(function () {
            \Hoa\Stream\Filter::register('foo', '42Foo');
        })->isInstanceOf(\Hoa\Stream\Filter\Exception::class)->hasMessage('Cannot register the 42Foo class for the filter foo ' . 'because it does not exist.');
    }
    public function case_append()
    {
        $this->given($stream = \fopen('hoa://Test/Vfs/Foo?type=file', 'r'), $name = 'string.toupper')->when($result = \Hoa\Stream\Filter::append($stream, $name))->then->resource($result)->isStreamFilter();
    }
    public function case_prepend()
    {
        $this->given($stream = \fopen('hoa://Test/Vfs/Foo?type=file', 'r'), $name = 'string.toupper')->when($result = \Hoa\Stream\Filter::prepend($stream, $name))->then->resource($result)->isStreamFilter();
    }
    public function case_remove()
    {
        $this->given($stream = \fopen('hoa://Test/Vfs/Foo?type=file', 'r'), $name = 'string.toupper', $filter = \Hoa\Stream\Filter::append($stream, $name))->when($result = \Hoa\Stream\Filter::remove($filter))->then->boolean($result)->isTrue();
    }
    public function case_remove_by_name()
    {
        $this->given($stream = \fopen('hoa://Test/Vfs/Foo?type=file', 'r'), $name = 'string.toupper', $filter = \Hoa\Stream\Filter::append($stream, $name))->when($result = \Hoa\Stream\Filter::remove($name))->then->boolean($result)->isTrue();
    }
    public function case_remove_unknown()
    {
        $this->exception(function () {
            \Hoa\Stream\Filter::remove('foo');
        })->isInstanceOf(\Hoa\Stream\Filter\Exception::class)->hasMessage('Cannot remove the stream filter foo ' . 'because no resource was found with this name.');
    }
    public function case_is_registered()
    {
        $this->when($result = \Hoa\Stream\Filter::isRegistered('string.toupper'))->then->boolean($result)->isTrue();
    }
    public function case_is_not_registered()
    {
        $this->when($result = \Hoa\Stream\Filter::isRegistered('foo'))->then->boolean($result)->isFalse();
    }
    public function case_get_registered()
    {
        $this->when($result = \Hoa\Stream\Filter::getRegistered())->then->array($result)->containsValues(['string.rot13', 'string.toupper', 'string.tolower', 'string.strip_tags', 'consumed', 'dechunk']);
    }
}