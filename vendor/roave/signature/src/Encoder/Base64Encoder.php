<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638\Roave\Signature\Encoder;

final class Base64Encoder implements \_PhpScoperbd5d0c5f7638\Roave\Signature\Encoder\EncoderInterface
{
    /**
     * {@inheritDoc}
     */
    public function encode(string $codeWithoutSignature) : string
    {
        return \base64_encode($codeWithoutSignature);
    }
    /**
     * {@inheritDoc}
     */
    public function verify(string $codeWithoutSignature, string $signature) : bool
    {
        return \hash_equals($this->encode($codeWithoutSignature), $signature);
    }
}
