<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\PHPStan\Reflection\BetterReflection\SourceLocator;

/**
 * @template-covariant T of \PhpParser\Node
 */
class FetchedNode
{
    /** @var T */
    private $node;
    /** @var \PhpParser\Node\Stmt\Namespace_|null */
    private $namespace;
    /** @var string */
    private $fileName;
    /**
     * @param T $node
     * @param \PhpParser\Node\Stmt\Namespace_|null $namespace
     * @param string $fileName
     */
    public function __construct(\_PhpScoperb75b35f52b74\PhpParser\Node $node, ?\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Namespace_ $namespace, string $fileName)
    {
        $this->node = $node;
        $this->namespace = $namespace;
        $this->fileName = $fileName;
    }
    /**
     * @return T
     */
    public function getNode() : \_PhpScoperb75b35f52b74\PhpParser\Node
    {
        return $this->node;
    }
    public function getNamespace() : ?\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Namespace_
    {
        return $this->namespace;
    }
    public function getFileName() : string
    {
        return $this->fileName;
    }
}
