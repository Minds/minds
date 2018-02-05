<?php

namespace Spec\Minds\Core\Security;

use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PasswordSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Security\Password');
    }

    public function it_should_generate_a_password_using_password_hash(User $user)
    {
        $this::generate($user, "foobar")->shouldBeString();
    }

    public function it_should_generate_a_password_using_sha256(User $user)
    {
        $user->get('salt')->willReturn($this::salt());
        $this::generate($user, "foobar", "sha256")->shouldBeString();
        $this::generate($user, "foobar", "sha256")->shouldBe($this::generate($user, "foobar", "sha256"));
    }

    public function it_should_generate_a_password_using_md5(User $user)
    {
        $user->get('salt')->willReturn($this::salt());
        $this::generate($user, "foobar", "md5")->shouldBeString();
        $this::generate($user, "foobar", "md5")->shouldBe($this::generate($user, "foobar", "md5"));
    }

    public function it_should_notice_to_rehash_password()
    {
        $check = new \stdClass();
        $check->shouldMigrate = true;
        $check->matches = true;

        $user = new \stdClass();
        $user->salt = (string)$this->getWrappedObject()->salt();
        $user->password = '';

        $user->password = $this->getWrappedObject()->generate($user, 'foobar', 'sha256');
        $this->shouldThrow('Minds\Core\Security\Exceptions\PasswordRequiresHashUpgradeException')->during('check', [$user, 'foobar']);
    }

    public function it_should_generate_a_salt()
    {
        $this::salt()->shouldBeString();
        $this::salt()->shouldNotBe($this::salt());
    }

    public function it_should_check_a_password_is_correct(User $user)
    {
        $check = new \stdClass();
        $check->shouldMigrate = false;
        $check->matches = true;
        $user->get('password')->willReturn($this::generate($user, "foobar"));
        $this::check($user, "foobar")->shouldBeLike($check);
    }

    public function it_should_check_a_password_is_incorrect(User $user)
    {
        $user->get('password')->willReturn($this::generate($user, "foobar"));
        // although salt is not needed for newer passwords, check method will use it for retro-compatibility it if the password was generated via sha256 or md5
        $user->get('salt')->willReturn("");
        $this::check($user, "foofoo")->shouldReturn(false);
    }
}
