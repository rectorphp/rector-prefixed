<?php

namespace _PhpScoperbd5d0c5f7638;

class FilesystemIterator extends \DirectoryIterator
{
    public function __construct(string $directory, int $flags = \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::SKIP_DOTS)
    {
    }
    /** @return void */
    public function rewind()
    {
    }
    /** @return string */
    public function key()
    {
    }
    /** @return string|SplFileInfo|self */
    public function current()
    {
    }
    /** @return int */
    public function getFlags()
    {
    }
    /** @return void */
    public function setFlags(int $flags)
    {
    }
}
\class_alias('_PhpScoperbd5d0c5f7638\\FilesystemIterator', 'FilesystemIterator', \false);
