<?php

namespace Spec\Minds\Core\Provisioner;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InstallerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Provisioner\Installer');
    }

    function it_should_check_options_valid()
    {
        $this->setOptions([
            'domain' => 'phpspec.minds.io',
            'username' => 'phpspec',
            'password' => 'phpspec1',
            'email' => 'phpspec@minds.io',
        ]);

        $this
            ->shouldNotThrow('Minds\\Exceptions\\ProvisionException')
            ->duringCheckOptions();
    }

    function it_should_check_options_invalid_domain()
    {
        $this->setOptions([
            'username' => 'phpspec',
            'password' => 'phpspec1',
            'email' => 'phpspec@minds.io',
        ]);

        $this
            ->shouldThrow('Minds\\Exceptions\\ProvisionException')
            ->duringCheckOptions();

        $this->setOptions([
            'domain' => '!@#!$asdasd%!%.com!',
            'username' => 'phpspec',
            'password' => 'phpspec1',
            'email' => 'phpspec@minds.io',
        ]);

        $this
            ->shouldThrow('Minds\\Exceptions\\ProvisionException')
            ->duringCheckOptions();
    }

    function it_should_check_options_invalid_username()
    {
        $this->setOptions([
            'domain' => 'phpspec.minds.io',
            'password' => 'phpspec1',
            'email' => 'phpspec@minds.io',
        ]);

        $this
            ->shouldThrow('Minds\\Exceptions\\ProvisionException')
            ->duringCheckOptions();
        
        $this->setOptions([
            'domain' => 'phpspec.minds.io',
            'username' => 'foo.bar$',
            'password' => 'phpspec1',
            'email' => 'phpspec@minds.io',
        ]);

        $this
            ->shouldThrow('Minds\\Exceptions\\ProvisionException')
            ->duringCheckOptions();
    }

    function it_should_check_options_invalid_password()
    {
        $this->setOptions([
            'domain' => 'phpspec.minds.io',
            'username' => 'phpspec',
            'email' => 'phpspec@minds.io',
        ]);

        $this
            ->shouldThrow('Minds\\Exceptions\\ProvisionException')
            ->duringCheckOptions();

        $this->setOptions([
            'domain' => 'phpspec.minds.io',
            'username' => 'phpspec',
            'password' => '000',
            'email' => 'phpspec@minds.io',
        ]);

        $this
            ->shouldThrow('Minds\\Exceptions\\ProvisionException')
            ->duringCheckOptions();
    }

    function it_should_check_options_invalid_email()
    {
        $this->setOptions([
            'domain' => 'phpspec.minds.io',
            'username' => 'phpspec',
            'password' => 'phpspec1',
        ]);

        $this
            ->shouldThrow('Minds\\Exceptions\\ProvisionException')
            ->duringCheckOptions();

        $this->setOptions([
            'domain' => 'phpspec.minds.io',
            'username' => 'phpspec',
            'password' => 'phpspec1',
            'email' => 'asldkj!@#!@#...net)',
        ]);

        $this
            ->shouldThrow('Minds\\Exceptions\\ProvisionException')
            ->duringCheckOptions();
    }

    function it_should_build_config()
    {
        $this->setApp((object) [
            'root' => __MINDS_ROOT__
        ]);
        
        $this->setOptions([
            'overwrite-settings' => true,
            'domain' => 'phpspec.minds.io',
            'username' => 'phpspec',
            'password' => 'phpspec1',
            'email' => 'phpspec@minds.io',
        ]);

        $this
            ->shouldNotThrow('\\Exception')
            ->duringBuildConfig([ 'returnResult' => true ]);
    }
}
