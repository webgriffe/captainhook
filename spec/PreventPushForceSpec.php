<?php

namespace spec\Webgriffe\CaptainHook;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Action;
use SebastianFeldmann\Git\Repository;
use Webgriffe\CaptainHook\PreventPushForce;
use PhpSpec\ObjectBehavior;

class PreventPushForceSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(PreventPushForce::class);
    }

    function it_should_implement_action_interface()
    {
        $this->shouldImplement(Action::class);
    }

    function it_should_throw_an_exception_if_it_is_a_forced_push_to_a_protected_branch(
        Config $config,
        IO $io,
        Repository $repository,
        Config\Action $action
    )
    {
        $protectedBranches = ['master'];
        // TODO simulate push froce to master branch
        $this
            ->shouldThrow(new \Exception('Never force push or delete the "master" branch!'))
            ->during('execute', [$config, $io, $repository, $action])
        ;
    }

    function it_should_not_throw_an_exception_if_it_is_a_forced_push_to_an_unprotected_branch(
        Config $config,
        IO $io,
        Repository $repository,
        Config\Action $action
    )
    {
        $protectedBranches = ['master'];
        // TODO simulate push froce to task-123 branch
        $this
            ->shouldNotThrow(\Throwable::class)
            ->during('execute', [$config, $io, $repository, $action])
        ;
    }
}
