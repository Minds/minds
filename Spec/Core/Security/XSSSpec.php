<?php

namespace Spec\Minds\Core\Security;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class XSSSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Security\XSS');
    }

    function it_should_not_allowed_none_allowed_tags()
    {
        $dirty = "<p><script>alert('this should be stripped');</script><p>this should be allowed</p></p>";
        $this->clean($dirty)->shouldReturn("<p>alert('this should be stripped');<p>this should be allowed</p></p>");
    }

    function it_should_encode_a_lone_close()
    {
        $dirty = "<b>I think that > is a really cool thing</b>";
        $this->clean($dirty)->shouldReturn("<b>I think that &gt; is a really cool thing</b>");
    }

    function it_should_encode_a_lone_openers()
    {
        $dirty = "<b>I think that < is a really cool thing</b>";
        $this->clean($dirty)->shouldReturn("<b>I think that &lt; is a really cool thing</b>");
    }

    function it_should_encode_both_lone_openers_and_closers()
    {
        $dirty = "<b>I think that < > is a really cool thing</b>";
        $this->clean($dirty)->shouldReturn("<b>I think that &lt; &gt; is a really cool thing</b>");
    }

    function it_should_dissallow_onClick_attributes()
    {
        $dirty = "<a onclick=\"console.log('hmmm...')\">click me</a>";
        $this->clean($dirty)->shouldReturn("<a target=\"_blank\">click me</a>");
    }

    function it_should_allow_href_on_anchor_tags()
    {
        $dirty = "<a href=\"https://www.minds.com\">take me home</a>";
        $this->clean($dirty)->shouldReturn("<a href=\"https://www.minds.com\" target=\"_blank\">take me home</a>");
    }

    function it_should_not_allow_bad_url_schemes(){
        $dirty = "<a href=\"javascript:alert('HEYHO')\">bad scheme here</a>";
        $this->clean($dirty)->shouldReturn("<a href=\"alert('HEYHO')\" target=\"_blank\">bad scheme here</a>");
    }

    function it_should_set_an_image_src(){
        $dirty = "<img src=\"https://minds.com/fakeimg.png\">";
        $this->clean($dirty)->shouldReturn("<img src=\"https://minds.com/fakeimg.png\">");
    }

    function it_should_set_width_and_height(){
        $dirty = "<img src=\"https://minds.com/fakeimg.png\" width=\"300\" height=\"600\">";
        $this->clean($dirty)->shouldReturn("<img src=\"https://minds.com/fakeimg.png\" width=\"300\" height=\"600\">");
    }

}
