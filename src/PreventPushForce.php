<?php

namespace Webgriffe\CaptainHook;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Action;
use SebastianFeldmann\Git\Repository;

class PreventPushForce implements Action
{
    /**
     * Executes the action.
     *
     * @param \CaptainHook\App\Config $config
     * @param \CaptainHook\App\Console\IO $io
     * @param \SebastianFeldmann\Git\Repository $repository
     * @param \CaptainHook\App\Config\Action $action
     * @return void
     * @throws \Exception
     */
    public function execute(Config $config, IO $io, Repository $repository, Config\Action $action): void
    {
        throw new \Exception(sprintf('Never force push or delete the "master" branch!'));
    }
}
