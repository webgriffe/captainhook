<?php

namespace Webgriffe\CaptainHook;

use CaptainHook\App\Config;
use CaptainHook\App\Config\Action as ConfigAction;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Action;
use Exception;
use SebastianFeldmann\Git\Repository;

class PreventCommitCaseSensitiveSameFilename implements Action
{
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
        $changedFiles = $repository->getIndexOperator()->getStagedFiles();
        $uniqueFileNames = array_unique(array_map('strtolower', $changedFiles));
        $caseSensitiveFilenames = array_diff($changedFiles, $uniqueFileNames);

        if (!empty($caseSensitiveFilenames)) {
            throw new \Error(
                sprintf(
                    'Found some files that have the same filename but different letters capitalization: ' . PHP_EOL .
                    '%s',
                    implode("\n", $caseSensitiveFilenames)
                )
            );
        }
    }
}
