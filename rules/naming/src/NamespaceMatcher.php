<?php

declare (strict_types=1);
namespace Rector\Naming;

use _PhpScoper17db12703726\Nette\Utils\Strings;
use Rector\Core\ValueObject\RenamedNamespace;
final class NamespaceMatcher
{
    /**
     * @param string[] $oldToNewNamespace
     */
    public function matchRenamedNamespace(string $name, array $oldToNewNamespace) : ?\Rector\Core\ValueObject\RenamedNamespace
    {
        \krsort($oldToNewNamespace);
        /** @var string $oldNamespace */
        foreach ($oldToNewNamespace as $oldNamespace => $newNamespace) {
            if (\_PhpScoper17db12703726\Nette\Utils\Strings::startsWith($name, $oldNamespace)) {
                return new \Rector\Core\ValueObject\RenamedNamespace($name, $oldNamespace, $newNamespace);
            }
        }
        return null;
    }
}
