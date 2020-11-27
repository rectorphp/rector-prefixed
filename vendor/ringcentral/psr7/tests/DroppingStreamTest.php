<?php

namespace _PhpScoperbd5d0c5f7638\RingCentral\Tests\Psr7;

use _PhpScoperbd5d0c5f7638\RingCentral\Psr7\BufferStream;
use _PhpScoperbd5d0c5f7638\RingCentral\Psr7\DroppingStream;
class DroppingStreamTest extends \_PhpScoperbd5d0c5f7638\PHPUnit_Framework_TestCase
{
    public function testBeginsDroppingWhenSizeExceeded()
    {
        $stream = new \_PhpScoperbd5d0c5f7638\RingCentral\Psr7\BufferStream();
        $drop = new \_PhpScoperbd5d0c5f7638\RingCentral\Psr7\DroppingStream($stream, 5);
        $this->assertEquals(3, $drop->write('hel'));
        $this->assertEquals(2, $drop->write('lo'));
        $this->assertEquals(5, $drop->getSize());
        $this->assertEquals('hello', $drop->read(5));
        $this->assertEquals(0, $drop->getSize());
        $drop->write('12345678910');
        $this->assertEquals(5, $stream->getSize());
        $this->assertEquals(5, $drop->getSize());
        $this->assertEquals('12345', (string) $drop);
        $this->assertEquals(0, $drop->getSize());
        $drop->write('hello');
        $this->assertSame(0, $drop->write('test'));
    }
}
