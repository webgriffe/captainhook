<?php
declare(strict_types=1);

namespace Webgriffe\CaptainHook;

class ForceDetector
{
    public function isForceUsed(): bool
    {
        $parentPid = posix_getppid();
        $output = shell_exec('ps -ocommand= -p ' . $parentPid);
        return preg_match('/\s(-f|--force)\s/', $output) === 1;
    }
}
