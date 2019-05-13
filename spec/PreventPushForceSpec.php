<?php

namespace spec\Webgriffe\CaptainHook;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Action;
use SebastianFeldmann\Git\Repository;
use Webgriffe\CaptainHook\PreventPushForce;
use PhpSpec\ObjectBehavior;
use Webgriffe\CaptainHook\StdinReader;

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
        Config\Action $action,
        StdinReader $stdinReader,
        Config\Options $options
    )
    {
        $stdinReader->read()->willReturn('refs/heads/master 1234 refs/heads/master 1234'.PHP_EOL);
        $this->beConstructedWith($stdinReader);
        $protectedBranches = ['master'];
        $options->get('protected-branches')->willReturn($protectedBranches);
        $action->getOptions()->willReturn($options);
        // TODO simulate a forced push
        $this
            ->shouldThrow(new \Error('Never force push or delete the "master" branch!'))
            ->during('execute', [$config, $io, $repository, $action])
        ;
    }

    function it_should_not_throw_an_exception_if_it_is_not_a_forced_push_to_a_protected_branch(
        Config $config,
        IO $io,
        Repository $repository,
        Config\Action $action,
        StdinReader $stdinReader,
        Config\Options $options
    )
    {
        $stdinReader->read()->willReturn('refs/heads/master 1234 refs/heads/master 1234'.PHP_EOL);
        $this->beConstructedWith($stdinReader);
        $protectedBranches = ['master'];
        $options->get('protected-branches')->willReturn($protectedBranches);
        $action->getOptions()->willReturn($options);
        // TODO simulate a not-forced push
        $this
            ->shouldNotThrow(\Throwable::class)
            ->during('execute', [$config, $io, $repository, $action])
        ;
    }

    function it_should_throw_an_exception_if_it_is_a_forced_push_with_all_option(
        Config $config,
        IO $io,
        Repository $repository,
        Config\Action $action,
        StdinReader $stdinReader,
        Config\Options $options
    )
    {
        $stdinReader->read()->willReturn(
            'refs/heads/master 1234 refs/heads/task123 1234'.PHP_EOL
            .'refs/heads/master 1234 refs/heads/master 1234'.PHP_EOL
        );
        $this->beConstructedWith($stdinReader);
        $protectedBranches = ['master'];
        $options->get('protected-branches')->willReturn($protectedBranches);
        $action->getOptions()->willReturn($options);
        // TODO simulate a forced push
        $this
            ->shouldThrow(new \Error('Never force push or delete the "master" branch!'))
            ->during('execute', [$config, $io, $repository, $action])
        ;
    }

    function it_should_not_throw_an_exception_if_it_is_a_forced_push_to_an_unprotected_branch(
        Config $config,
        IO $io,
        Repository $repository,
        Config\Action $action,
        StdinReader $stdinReader,
        Config\Options $options
    )
    {
        $stdinReader->read()->willReturn('refs/heads/task123 1234 refs/heads/task123 1234'.PHP_EOL);
        $this->beConstructedWith($stdinReader);
        $protectedBranches = ['master'];
        $options->get('protected-branches')->willReturn($protectedBranches);
        $action->getOptions()->willReturn($options);
        // TODO simulate a forced push
        $this
            ->shouldNotThrow(\Throwable::class)
            ->during('execute', [$config, $io, $repository, $action])
        ;
    }

    function it_should_not_throw_if_nothing_is_pushed(
        Config $config,
        IO $io,
        Repository $repository,
        Config\Action $action,
        StdinReader $stdinReader,
        Config\Options $options
    )
    {
        $stdinReader->read()->willReturn('');
        $this->beConstructedWith($stdinReader);
        $protectedBranches = ['master'];
        $options->get('protected-branches')->willReturn($protectedBranches);
        $action->getOptions()->willReturn($options);
        // TODO simulate a forced push
        $this
            ->shouldNotThrow(\Throwable::class)
            ->during('execute', [$config, $io, $repository, $action])
        ;
    }

    function it_should_throw_if_no_protected_branch_is_configured(
        Config $config,
        IO $io,
        Repository $repository,
        Config\Action $action,
        StdinReader $stdinReader,
        Config\Options $options
    )
    {
        $stdinReader->read()->willReturn('refs/heads/master 1234 refs/heads/master 1234' . PHP_EOL);
        $this->beConstructedWith($stdinReader);
        $options->get('protected-branches')->willReturn(null);
        $action->getOptions()->willReturn($options);
        // TODO simulate a forced push
        $this
            ->shouldThrow(
                new \Error(
                    sprintf(
                        'You must configure the "protected-branches" option for the action "%s".',
                        PreventPushForce::class
                    )
                )
            )
            ->during('execute', [$config, $io, $repository, $action])
        ;
    }
}
