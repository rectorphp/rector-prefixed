<?php

namespace _PhpScoper88fe6e0ad041;

class RecursiveDirectoryIterator extends \FilesystemIterator implements \RecursiveIterator
{
    public function __construct(string $directory, int $flags = \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_FILEINFO)
    {
    }
    /** @return bool */
    public function hasChildren(bool $allowLinks = \false)
    {
    }
    /** @return RecursiveDirectoryIterator */
    public function getChildren()
    {
    }
    /** @return string */
    public function getSubPath()
    {
    }
    /** @return string */
    public function getSubPathname()
    {
    }
}
\class_alias('_PhpScoper88fe6e0ad041\\RecursiveDirectoryIterator', 'RecursiveDirectoryIterator', \false);
