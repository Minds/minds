<?php

namespace Spec\Minds\Core\Security;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Entities\User;

class PasswordSpec extends ObjectBehavior {

    function it_is_initializable(){
        $this->shouldHaveType('Minds\Core\Security\Password');
    }

    function it_should_generate_a_password(User $user){
      $user->get('salt')->willReturn($this::salt());
      $this::generate($user, "foobar")->shouldBeString();
      $this::generate($user, "foobar")->shouldBe($this::generate($user, "foobar"));
    }

    function it_should_generate_a_salt(){
      $this::salt()->shouldBeString();
      $this::salt()->shouldNotBe($this::salt());
    }

    function it_should_check_a_password_is_correct(User $user){
      $salt = $this::salt();
      $user->get('salt')->willReturn($salt);
      $user->get('password')->willReturn($this::generate($user, "foobar"));
      $this::check($user, "foobar")->shouldBe(true);
    }

    function it_should_check_a_password_is_incorrect(User $user){
      $user->get('salt')->willReturn(time());
      $user->get('password')->willReturn($this::generate($user, "foobar"));
      $user->get('salt')->willReturn("");
      $this::check($user, "foobar")->shouldBe(false);
      $this::check($user, "foofoo")->shouldBe(false);
    }
}
