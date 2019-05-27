<?php

namespace spec\Webgriffe\CaptainHook;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Action;
use PhpSpec\ObjectBehavior;
use SebastianFeldmann\Git\CommitMessage;
use SebastianFeldmann\Git\Repository;
use Webgriffe\CaptainHook\PreventCommitMessageWithDiff;

class PreventCommitMessageWithDiffSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(PreventCommitMessageWithDiff::class);
    }

    function it_should_implement_action_interface()
    {
        $this->shouldImplement(Action::class);
    }

    function it_should_throw_an_exception_if_diff_is_present_in_commit_message(
        Config $config,
        IO $io,
        Repository $repository,
        Config\Action $action
    ) {
        $commitMsg = new CommitMessage(
            "In this message is present the diff of the commit"
            . PHP_EOL . PHP_EOL
            . PreventCommitMessageWithDiff::STRING_ALWAYS_PRESENT_IN_DIFF
        );
        $repository->getCommitMsg()->willReturn($commitMsg);
        $this
            ->shouldThrow(new \Error('Never include git diff in commit message!'))
            ->during('execute', [$config, $io, $repository, $action]);
    }

    function it_should_not_throw_an_exception_if_diff_is_present_in_commit_message(
        Config $config,
        IO $io,
        Repository $repository,
        Config\Action $action
    ) {
        $commitMsg = new CommitMessage(
            "In this message is not present the diff of the commit"
            . PHP_EOL . PHP_EOL
            . "This is the Body of the commit"
        );
        $repository->getCommitMsg()->willReturn($commitMsg);
        $this
            ->shouldNotThrow(\Throwable::class)
            ->during('execute', [$config, $io, $repository, $action]);
    }
}
