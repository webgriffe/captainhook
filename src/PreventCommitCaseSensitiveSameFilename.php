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
        $caseSensitiveFilenames = [];
        $changedFiles = $repository->getIndexOperator()->getStagedFiles();
        $countChanged = count($changedFiles);
        for ($i = 0; $i < $countChanged - 1; ++$i) {
            for ($j = $i + 1; $j < $countChanged; ++$j) {
                if (strtolower($changedFiles[$i]) === strtolower($changedFiles[$j])) {
                    $caseSensitiveFilenames[] = $changedFiles[$i];
                    continue 2;
                }
            }
        }

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
