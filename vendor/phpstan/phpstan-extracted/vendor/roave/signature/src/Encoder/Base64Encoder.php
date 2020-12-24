<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Roave\Signature\Encoder;

final class Base64Encoder implements \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Roave\Signature\Encoder\EncoderInterface
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
