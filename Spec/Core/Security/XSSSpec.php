<?php

namespace Spec\Minds\Core\Security;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class XSSSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Security\XSS');
    }

    public function it_should_not_allowed_none_allowed_tags()
    {
        $dirty = "<p><script>alert('this should be stripped');</script><p>this should be allowed</p></p>";
        $this->clean($dirty)->shouldReturn('<?xml encoding="utf-8" ?>'."<p>alert('this should be stripped');</p><p>this should be allowed</p>");
    }

    public function it_should_encode_a_lone_close()
    {
        $dirty = "<b>I think that > is a really cool thing</b>";
        $this->clean($dirty)->shouldReturn('<?xml encoding="utf-8" ?>'."<b>I think that &gt; is a really cool thing</b>");
    }

    public function it_should_encode_a_lone_openers()
    {
        $dirty = "<b>I think that < is a really cool thing</b>";
        $this->clean($dirty)->shouldReturn('<?xml encoding="utf-8" ?>'."<b>I think that &lt; is a really cool thing</b>");
    }

    public function it_should_encode_both_lone_openers_and_closers()
    {
        $dirty = "<b>I think that < > is a really cool thing</b>";
        $this->clean($dirty)->shouldReturn('<?xml encoding="utf-8" ?>'."<b>I think that &lt; &gt; is a really cool thing</b>");
    }

    public function it_should_dissallow_onClick_attributes()
    {
        $dirty = "<a onclick=\"console.log('hmmm...')\">click me</a>";
        $this->clean($dirty)->shouldReturn('<?xml encoding="utf-8" ?>'."<a target=\"_blank\">click me</a>");
    }

    public function it_should_allow_href_on_anchor_tags()
    {
        $dirty = "<a href=\"https://www.minds.com\">take me home</a>";
        $this->clean($dirty)->shouldReturn('<?xml encoding="utf-8" ?>'."<a href=\"https://www.minds.com\" target=\"_blank\">take me home</a>");
    }

    public function it_should_not_allow_bad_url_schemes()
    {
        $dirty = "<a href=\"javascript:alert('HEYHO')\">bad scheme here</a>";
        $this->clean($dirty)->shouldReturn('<?xml encoding="utf-8" ?>'."<a href=\"alert('HEYHO')\" target=\"_blank\">bad scheme here</a>");
    }

    public function it_should_not_allow_bad_url_schemes_with_case_hacks()
    {
        $dirty = "<a href=\"Javascript:alert('HEYHO')\">bad scheme here</a>";
        $this->clean($dirty)->shouldReturn('<?xml encoding="utf-8" ?>'."<a href=\"alert('HEYHO')\" target=\"_blank\">bad scheme here</a>");
    }

    public function it_should_not_allow_bad_url_schemes_from_multiple_keywords()
    {
        $dirty = "<iframe src=\"javascriptjavascript::document.loadFromBogusFunction()\"></iframe>";
        $this->clean($dirty)->shouldReturn('<?xml encoding="utf-8" ?>'."<iframe src=\"document.loadFromBogusFunction()\"></iframe>");
        $dirty = "<iframe src=\"javascriptjavascriptjavascript:::document.loadFromBogusFunction()\"></iframe>";
        $this->clean($dirty)->shouldReturn('<?xml encoding="utf-8" ?>'."<iframe src=\"document.loadFromBogusFunction()\"></iframe>");
    }

    public function it_should_set_an_image_src()
    {
        $dirty = "<img src=\"https://minds.com/fakeimg.png\">";
        $this->clean($dirty)->shouldReturn('<?xml encoding="utf-8" ?>'."<img src=\"https://minds.com/fakeimg.png\">");
    }

    public function it_should_set_width_and_height()
    {
        $dirty = "<img src=\"https://minds.com/fakeimg.png\" width=\"300\" height=\"600\">";
        $this->clean($dirty)->shouldReturn('<?xml encoding="utf-8" ?>'."<img src=\"https://minds.com/fakeimg.png\" width=\"300\" height=\"600\">");
    }

    public function it_should_close_open_tags()
    {
        $dirty = "<p><p>";
        $this->clean($dirty)->shouldReturn('<?xml encoding="utf-8" ?>'."<p></p><p></p>");
    }
}
