<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\Order\Tests;

use Iterator;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Property;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\PropertyProperty;
use _PhpScoperb75b35f52b74\Rector\Core\HttpKernel\RectorKernel;
use _PhpScoperb75b35f52b74\Rector\NodeNameResolver\NodeNameResolver;
use _PhpScoperb75b35f52b74\Rector\Order\StmtOrder;
use _PhpScoperb75b35f52b74\Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
final class StmtOrderTest extends \_PhpScoperb75b35f52b74\Symplify\PackageBuilder\Testing\AbstractKernelTestCase
{
    /**
     * @var int[]
     */
    private const OLD_TO_NEW_KEYS = [0 => 0, 1 => 2, 2 => 1];
    /**
     * @var StmtOrder
     */
    private $stmtOrder;
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    protected function setUp() : void
    {
        $this->bootKernel(\_PhpScoperb75b35f52b74\Rector\Core\HttpKernel\RectorKernel::class);
        $this->stmtOrder = $this->getService(\_PhpScoperb75b35f52b74\Rector\Order\StmtOrder::class);
        $this->nodeNameResolver = $this->getService(\_PhpScoperb75b35f52b74\Rector\NodeNameResolver\NodeNameResolver::class);
    }
    public function dataProvider() : \Iterator
    {
        (yield [['first', 'second', 'third'], ['third', 'first', 'second'], [0 => 1, 1 => 2, 2 => 0]]);
        (yield [['first', 'second', 'third'], ['third', 'second', 'first'], [0 => 2, 1 => 1, 2 => 0]]);
        (yield [['first', 'second', 'third'], ['first', 'second', 'third'], [0 => 0, 1 => 1, 2 => 2]]);
    }
    /**
     * @dataProvider dataProvider
     * @param array<int,string> $desiredStmtOrder
     * @param array<int,string> $currentStmtOrder
     * @param array<int,int> $expected
     */
    public function testCreateOldToNewKeys(array $desiredStmtOrder, array $currentStmtOrder, array $expected) : void
    {
        $actual = $this->stmtOrder->createOldToNewKeys($desiredStmtOrder, $currentStmtOrder);
        $this->assertSame($expected, $actual);
    }
    public function testReorderClassStmtsByOldToNewKeys() : void
    {
        $class = $this->getTestClassNode();
        $classLike = $this->stmtOrder->reorderClassStmtsByOldToNewKeys($class, self::OLD_TO_NEW_KEYS);
        $expectedClass = $this->getExpectedClassNode();
        $this->assertSame($this->nodeNameResolver->getName($expectedClass->stmts[0]), $this->nodeNameResolver->getName($classLike->stmts[0]));
        $this->assertSame($this->nodeNameResolver->getName($expectedClass->stmts[1]), $this->nodeNameResolver->getName($classLike->stmts[1]));
        $this->assertSame($this->nodeNameResolver->getName($expectedClass->stmts[2]), $this->nodeNameResolver->getName($classLike->stmts[2]));
    }
    private function getTestClassNode() : \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_
    {
        $class = new \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_('ClassUnderTest');
        $class->stmts[] = new \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Property(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_::MODIFIER_PRIVATE, [new \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\PropertyProperty('name')]);
        $class->stmts[] = new \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Property(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_::MODIFIER_PRIVATE, [new \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\PropertyProperty('service')]);
        $class->stmts[] = new \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Property(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_::MODIFIER_PRIVATE, [new \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\PropertyProperty('price')]);
        return $class;
    }
    private function getExpectedClassNode() : \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_
    {
        $expectedClass = new \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_('ExpectedClass');
        $expectedClass->stmts[] = new \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Property(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_::MODIFIER_PRIVATE, [new \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\PropertyProperty('name')]);
        $expectedClass->stmts[] = new \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Property(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_::MODIFIER_PRIVATE, [new \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\PropertyProperty('price')]);
        $expectedClass->stmts[] = new \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Property(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_::MODIFIER_PRIVATE, [new \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\PropertyProperty('service')]);
        return $expectedClass;
    }
}
