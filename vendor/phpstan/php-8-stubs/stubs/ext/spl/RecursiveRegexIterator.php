<?php

namespace _PhpScoperbd5d0c5f7638;

class RecursiveRegexIterator extends \RegexIterator implements \RecursiveIterator
{
    public function __construct(\RecursiveIterator $iterator, string $pattern, int $mode = self::MATCH, int $flags = 0, int $pregFlags = 0)
    {
    }
    /** @return bool */
    public function accept()
    {
    }
    /**
     * @return bool
     * @implementation-alias RecursiveFilterIterator::hasChildren
     */
    public function hasChildren()
    {
    }
    /** @return RecursiveRegexIterator */
    public function getChildren()
    {
    }
}
\class_alias('_PhpScoperbd5d0c5f7638\\RecursiveRegexIterator', 'RecursiveRegexIterator', \false);
