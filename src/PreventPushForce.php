<?php
declare(strict_types=1);

namespace Webgriffe\CaptainHook;

use CaptainHook\App\Config;
use CaptainHook\App\Config\Action as ConfigAction;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Action;
use SebastianFeldmann\Git\Repository;

class PreventPushForce implements Action
{
    /**
     * https://git-scm.com/docs/githooks#_pre_push to see pre-push standard input
     *
     * @param Config $config
     * @param IO $io
     * @param Repository $repository
     * @param ConfigAction $action
     * @param StdinReader|null $stdinReader
     * @return void
     * @throws \Exception
     */
    public function execute(Config $config, IO $io, Repository $repository, ConfigAction $action, StdinReader $stdinReader = null): void
    {
        if (!$stdinReader) {
            $stdinReader = new StdinReader();
        }
        $stdin = $stdinReader->read();
        if (empty($stdin)) {
            return;
        }

        if (!$this->isPushForce()){
            return;
        }

        $lines = explode(PHP_EOL, trim($stdin));
        $protectedBranches = $action->getOptions()->get('protected-branches');
        if (empty($protectedBranches)) {
            throw new \Error(
                sprintf(
                    'You must configure the "protected-branches" option for the action "%s".',
                    __CLASS__
                )
            );
        }
        foreach ($lines as $line) {
            $line = explode(' ', trim($line));
            /**
             * @see https://git-scm.com/docs/githooks#_pre_push
             * $line[0] => local ref/branch
             * $line[1] => local sha1
             * $line[2] => remote ref/branch
             * $line[3] => remote sha1
             */
            $remoteBranch = $line[2];
            foreach ($protectedBranches as $protectedBranch) {
                if (strpos($remoteBranch, $protectedBranch) !== false) {
                    throw new \Error(sprintf('Never force push or delete the "master" branch!'));
                }
            }
        }
    }

    /**
     * @return bool
     */
    public function isPushForce(): bool
    {
        // https://github.com/bigbinary/tiny_scripts/blob/master/git-hooks/hooks/pre-push
        $ppid = posix_getppid();
        $command = "ps -ocommand= -p ".$ppid;
        $output = shell_exec($command);
        return !(strpos($output, '-f') === false || strpos($output, '--force') === false);
    }
}
