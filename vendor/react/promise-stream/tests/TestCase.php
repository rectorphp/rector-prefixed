<?php

namespace _PhpScoperbd5d0c5f7638\React\Tests\Promise\Stream;

use _PhpScoperbd5d0c5f7638\PHPUnit\Framework\TestCase as BaseTestCase;
class TestCase extends \_PhpScoperbd5d0c5f7638\PHPUnit\Framework\TestCase
{
    protected function expectCallableOnce()
    {
        $mock = $this->createCallableMock();
        $mock->expects($this->once())->method('__invoke');
        return $mock;
    }
    protected function expectCallableOnceWith($value)
    {
        $mock = $this->createCallableMock();
        $mock->expects($this->once())->method('__invoke')->with($this->equalTo($value));
        return $mock;
    }
    protected function expectCallableOnceParameter($type)
    {
        $mock = $this->createCallableMock();
        $mock->expects($this->once())->method('__invoke')->with($this->isInstanceOf($type));
        return $mock;
    }
    protected function expectCallableNever()
    {
        $mock = $this->createCallableMock();
        $mock->expects($this->never())->method('__invoke');
        return $mock;
    }
    /**
     * @link https://github.com/reactphp/react/blob/master/tests/React/Tests/Socket/TestCase.php (taken from reactphp/react)
     */
    protected function createCallableMock()
    {
        return $this->getMockBuilder('_PhpScoperbd5d0c5f7638\\React\\Tests\\Promise\\Stream\\CallableStub')->getMock();
    }
    protected function expectPromiseResolve($promise)
    {
        $this->assertInstanceOf('_PhpScoperbd5d0c5f7638\\React\\Promise\\PromiseInterface', $promise);
        $promise->then($this->expectCallableOnce(), $this->expectCallableNever());
        return $promise;
    }
    protected function expectPromiseResolveWith($with, $promise)
    {
        $this->assertInstanceOf('_PhpScoperbd5d0c5f7638\\React\\Promise\\PromiseInterface', $promise);
        $promise->then($this->expectCallableOnce($with), $this->expectCallableNever());
        return $promise;
    }
    protected function expectPromiseReject($promise)
    {
        $this->assertInstanceOf('_PhpScoperbd5d0c5f7638\\React\\Promise\\PromiseInterface', $promise);
        $promise->then($this->expectCallableNever(), $this->expectCallableOnce());
        return $promise;
    }
}
