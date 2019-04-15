<?php

namespace Webgriffe\CaptainHook;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Action;
use SebastianFeldmann\Git\Repository;

class PreventPushForce implements Action
{
    /**
     * https://git-scm.com/docs/githooks#_pre_push to see pre-push standard input
     *
     * @param \CaptainHook\App\Config $config
     * @param \CaptainHook\App\Console\IO $io
     * @param \SebastianFeldmann\Git\Repository $repository
     * @param \CaptainHook\App\Config\Action $action
     * @return void
     * @throws \Exception
     */
    public function execute(Config $config, IO $io, Repository $repository, Config\Action $action, StdinReader $stdinReader = null): void
    {
        if (!$stdinReader) {
            $stdinReader = new StdinReader();
        }
        $stdin = $stdinReader->read();
        $lines = explode(PHP_EOL, $stdin);
        array_pop($lines);
        $protectedBranches = $action->getOptions()->get('protected-branches');
        foreach ($lines as $line) {
            list($localBranch, $localHash, $remoteBranch, $remoteHash) = explode(' ', $line);
            foreach ($protectedBranches as $protectedBranch) {
                if (strpos($remoteBranch, $protectedBranch) !== false) {
                    throw new \Exception(sprintf('Never force push or delete the "master" branch!'));
                }
            }
        }
    }
}
