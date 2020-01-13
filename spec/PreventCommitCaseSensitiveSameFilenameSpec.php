<?php

namespace spec\Webgriffe\CaptainHook;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Action;
use PhpSpec\ObjectBehavior;
use SebastianFeldmann\Git\Operator\Index;
use SebastianFeldmann\Git\Repository;
use Webgriffe\CaptainHook\PreventCommitCaseSensitiveSameFilename;

class PreventCommitCaseSensitiveSameFilenameSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(PreventCommitCaseSensitiveSameFilename::class);
    }

    function it_should_implement_action_interface()
    {
        $this->shouldImplement(Action::class);
    }

    function it_should_throw_an_exception_when_there_are_case_sensitive_files(
        Config $config,
        IO $io,
        Repository $repository,
        Config\Action $action,
        Index $index
    ) {
        $index->getStagedFiles()->shouldBeCalled()->willReturn(['a', 'A', 'b']);
        $repository->getIndexOperator()->shouldBeCalled()->willReturn($index);

        $this
            ->shouldThrow(
                new \Error('Found some files that has the same filename but with different case: ' . PHP_EOL . 'A')
            )
            ->during('execute', [$config, $io, $repository, $action]);
    }

    function it_should_not_throw_an_exception_when_there_are_no_case_sensitive_files(
        Config $config,
        IO $io,
        Repository $repository,
        Config\Action $action,
        Index $index
    ) {
        $index->getStagedFiles()->shouldBeCalled()->willReturn(['a', 'b', 'c']);
        $repository->getIndexOperator()->shouldBeCalled()->willReturn($index);

        $this
            ->shouldNotThrow(\Throwable::class)
            ->during('execute', [$config, $io, $repository, $action]);
    }
}
