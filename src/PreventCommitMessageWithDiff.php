<?php

namespace Webgriffe\CaptainHook;

use CaptainHook\App\Config;
use CaptainHook\App\Config\Action as ConfigAction;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Action;
use Exception;
use SebastianFeldmann\Git\Repository;

class PreventCommitMessageWithDiff implements Action
{
    const STRING_ALWAYS_PRESENT_IN_DIFF = "diff --git a/";

    /**
     * Executes the action.
     *
     * @param Config $config
     * @param IO $io
     * @param Repository $repository
     * @param ConfigAction $action
     * @return void
     * @throws Exception
     */
    public function execute(Config $config, IO $io, Repository $repository, ConfigAction $action): void
    {
        if (strpos($repository->getCommitMsg()->getContent(), self::STRING_ALWAYS_PRESENT_IN_DIFF) !== false) {
            throw new \Error(sprintf('Never include git diff in commit message!'));
        }
    }
}
