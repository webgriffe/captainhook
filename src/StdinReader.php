<?php
declare(strict_types=1);

namespace Webgriffe\CaptainHook;

/**
 * @internal
 */
class StdinReader
{
    /**
     * @return string
     */
    public function read(): string
    {
        $in = fopen('php://stdin', 'rb');
        $buffer = '';
        while(!feof($in)){
            $buffer .= fgets($in, 4096);
        }
        return $buffer;
    }
}
