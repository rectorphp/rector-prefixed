<?php

namespace _PhpScoperbd5d0c5f7638;

class ZipArchive
{
    /** @return bool|int */
    public function open(string $filename, int $flags = 0)
    {
    }
    /** @return bool */
    public function setPassword(string $password)
    {
    }
    /** @return bool */
    public function close()
    {
    }
    /** @return int */
    public function count()
    {
    }
    /** @return string */
    public function getStatusString()
    {
    }
    /** @return bool */
    public function addEmptyDir(string $dirname, int $flags = 0)
    {
    }
    /** @return bool */
    public function addFromString(string $name, string $content, int $flags = \ZipArchive::FL_OVERWRITE)
    {
    }
    /** @return bool */
    public function addFile(string $filepath, string $entryname = "", int $start = 0, int $length = 0, int $flags = \ZipArchive::FL_OVERWRITE)
    {
    }
    /** @return bool */
    public function replaceFile(string $filepath, string $index, int $start = 0, int $length = 0, int $flags = 0)
    {
    }
    /** @return array|false */
    public function addGlob(string $pattern, int $flags = 0, array $options = [])
    {
    }
    /** @return array|false */
    public function addPattern(string $pattern, string $path = ".", array $options = [])
    {
    }
    /** @return bool */
    public function renameIndex(int $index, string $new_name)
    {
    }
    /** @return bool */
    public function renameName(string $name, string $new_name)
    {
    }
    /** @return bool */
    public function setArchiveComment(string $comment)
    {
    }
    /** @return string|false */
    public function getArchiveComment(int $flags = 0)
    {
    }
    /** @return bool|null */
    public function setCommentIndex(int $index, string $comment)
    {
    }
    /** @return bool|null */
    public function setCommentName(string $name, string $comment)
    {
    }
    #ifdef HAVE_SET_MTIME
    /** @return bool|null */
    public function setMtimeIndex(int $index, int $timestamp, int $flags = 0)
    {
    }
    /** @return bool|null */
    public function setMtimeName(string $name, int $timestamp, int $flags = 0)
    {
    }
    #endif
    /** @return string|false */
    public function getCommentIndex(int $index, int $flags = 0)
    {
    }
    /** @return string|false */
    public function getCommentName(string $name, int $flags = 0)
    {
    }
    /** @return bool */
    public function deleteIndex(int $index)
    {
    }
    /** @return bool */
    public function deleteName(string $name)
    {
    }
    /** @return array|false */
    public function statName(string $name, int $flags = 0)
    {
    }
    /** @return array|false */
    public function statIndex(int $index, int $flags = 0)
    {
    }
    /** @return int|false */
    public function locateName(string $name, int $flags = 0)
    {
    }
    /** @return string|false */
    public function getNameIndex(int $index, int $flags = 0)
    {
    }
    /** @return bool */
    public function unchangeArchive()
    {
    }
    /** @return bool */
    public function unchangeAll()
    {
    }
    /** @return bool */
    public function unchangeIndex(int $index)
    {
    }
    /** @return bool */
    public function unchangeName(string $name)
    {
    }
    /** @return bool */
    public function extractTo(string $pathto, array|string|null $files = null)
    {
    }
    /** @return string|false */
    public function getFromName(string $name, int $len = 0, int $flags = 0)
    {
    }
    /** @return string|false */
    public function getFromIndex(int $index, int $len = 0, int $flags = 0)
    {
    }
    /** @return resource|false */
    public function getStream(string $name)
    {
    }
    #ifdef ZIP_OPSYS_DEFAULT
    /** @return bool */
    public function setExternalAttributesName(string $name, int $opsys, int $attr, int $flags = 0)
    {
    }
    /** @return bool */
    public function setExternalAttributesIndex(int $index, int $opsys, int $attr, int $flags = 0)
    {
    }
    /**
     * @param int $opsys
     * @param int $attr
     * @return bool
     */
    public function getExternalAttributesName(string $name, &$opsys, &$attr, int $flags = 0)
    {
    }
    /**
     * @param int $opsys
     * @param int $attr
     * @return bool
     */
    public function getExternalAttributesIndex(int $index, &$opsys, &$attr, int $flags = 0)
    {
    }
    #endif
    /** @return bool */
    public function setCompressionName(string $name, int $method, int $compflags = 0)
    {
    }
    /** @return bool */
    public function setCompressionIndex(int $index, int $method, int $compflags = 0)
    {
    }
    #ifdef HAVE_ENCRYPTION
    /** @return bool */
    public function setEncryptionName(string $name, int $method, ?string $password = null)
    {
    }
    /** @return bool */
    public function setEncryptionIndex(int $index, int $method, ?string $password = null)
    {
    }
    #endif
    #ifdef HAVE_PROGRESS_CALLBACK
    /** @return bool */
    public function registerProgressCallback(float $rate, callable $callback)
    {
    }
    #endif
    #ifdef HAVE_CANCEL_CALLBACK
    /** @return bool */
    public function registerCancelCallback(callable $callback)
    {
    }
    #endif
    #ifdef HAVE_METHOD_SUPPORTED
    /** @return bool */
    public static function isCompressionMethodSupported(int $method, bool $enc = \true) : bool
    {
    }
    /** @return bool */
    public static function isEncryptionMethodSupported(int $method, bool $enc = \true) : bool
    {
    }
    #endif
}
\class_alias('_PhpScoperbd5d0c5f7638\\ZipArchive', 'ZipArchive', \false);
