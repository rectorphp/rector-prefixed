<?php

namespace _PhpScoperbd5d0c5f7638\RingCentral\Tests\Psr7;

use _PhpScoperbd5d0c5f7638\RingCentral\Psr7;
use _PhpScoperbd5d0c5f7638\RingCentral\Psr7\NoSeekStream;
/**
 * @covers RingCentral\Psr7\NoSeekStream
 * @covers RingCentral\Psr7\StreamDecoratorTrait
 */
class NoSeekStreamTest extends \_PhpScoperbd5d0c5f7638\PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Cannot seek a NoSeekStream
     */
    public function testCannotSeek()
    {
        $s = $this->getMockBuilder('_PhpScoperbd5d0c5f7638\\Psr\\Http\\Message\\StreamInterface')->setMethods(array('isSeekable', 'seek'))->getMockForAbstractClass();
        $s->expects($this->never())->method('seek');
        $s->expects($this->never())->method('isSeekable');
        $wrapped = new \_PhpScoperbd5d0c5f7638\RingCentral\Psr7\NoSeekStream($s);
        $this->assertFalse($wrapped->isSeekable());
        $wrapped->seek(2);
    }
    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Cannot write to a non-writable stream
     */
    public function testHandlesClose()
    {
        $s = \_PhpScoperbd5d0c5f7638\RingCentral\Psr7\stream_for('foo');
        $wrapped = new \_PhpScoperbd5d0c5f7638\RingCentral\Psr7\NoSeekStream($s);
        $wrapped->close();
        $wrapped->write('foo');
    }
}