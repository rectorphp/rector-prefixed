<?php

namespace _PhpScoper88fe6e0ad041;

/**
 * @param string|int|float|bool $message
 * @param int $error_code
 */
function msg_send(\SysvMessageQueue $queue, int $message_type, $message, bool $serialize = \true, bool $blocking = \true, &$error_code = null) : bool
{
}
