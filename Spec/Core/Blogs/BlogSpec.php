<?php

namespace Spec\Minds\Core\Blogs;

use Minds\Core\Blogs\Header;
use Minds\Core\Config;
use Minds\Core\Events\EventsDispatcher;
use Minds\Core\Security\ACL;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BlogSpec extends ObjectBehavior
{
    /** @var EventsDispatcher */
    protected $eventsDispatcher;

    /** @var Config */
    protected $config;

    /** @var Header */
    protected $header;

    /** @var ACL */
    protected $acl;

    function let(
        EventsDispatcher $eventsDispatcher,
        Config $config,
        Header $header,
        ACL $acl
    )
    {
        $this->beConstructedWith($eventsDispatcher, $config, $header, $acl);

        $this->eventsDispatcher = $eventsDispatcher;
        $this->config = $config;
        $this->header = $header;
        $this->acl = $acl;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blogs\Blog');
    }

    function it_should_build_a_guid_if_not_set_during_get_guid()
    {
        $this->getGuid()
            ->shouldNotBeNull();
    }

    function it_should_set_owner_obj_based_on_array()
    {
        $this->setOwnerObj([ 'guid' => 1000 ])
            ->shouldBeAnInstanceOf($this);

        expect($this->getOwnerObj()->getWrappedObject())
            ->shouldBe([ 'guid' => 1000 ]);

        expect($this->getOwnerGuid()->getWrappedObject())
            ->shouldBe(1000);
    }

    function it_should_set_owner_obj_based_on_json_string()
    {
        $this->setOwnerObj('{ "guid": 1000 }')
            ->shouldBeAnInstanceOf($this);

        expect($this->getOwnerObj()->getWrappedObject())
            ->shouldBe([ 'guid' => 1000 ]);

        expect($this->getOwnerGuid()->getWrappedObject())
            ->shouldBe(1000);
    }

    function it_should_set_owner_obj_based_on_user(
        User $user
    )
    {
        $user->export()
            ->shouldBeCalled()
            ->willReturn([ 'guid' => 1000 ]);

        $this->setOwnerObj($user)
            ->shouldBeAnInstanceOf($this);

        expect($this->getOwnerObj()->getWrappedObject())
            ->shouldBe([ 'guid' => 1000 ]);

        expect($this->getOwnerGuid()->getWrappedObject())
            ->shouldBe(1000);
    }

    function it_should_get_non_slugged_url()
    {
        $this->config->get('site_url')
            ->shouldBeCalled()
            ->willReturn('http://phpspec/');

        $this
            ->setGuid(5000)
            ->setSlug('')
            ->setOwnerObj([ 'guid' => 1000, 'username' => 'ps' ])
            ->getUrl(false)
            ->shouldReturn('http://phpspec/blog/view/5000');

        $this
            ->setGuid(5000)
            ->setSlug('')
            ->setOwnerObj([ 'guid' => 1000, 'username' => 'ps' ])
            ->getUrl(true)
            ->shouldReturn('blog/view/5000');
    }

    function it_should_get_slugged_url()
    {
        $this->config->get('site_url')
            ->shouldBeCalled()
            ->willReturn('http://phpspec/');

        $this
            ->setGuid(5000)
            ->setSlug('phpspec-test')
            ->setOwnerObj([ 'guid' => 1000, 'username' => 'ps' ])
            ->getUrl(false)
            ->shouldReturn('http://phpspec/ps/blog/phpspec-test-5000');

        $this
            ->setGuid(5000)
            ->setSlug('phpspec-test')
            ->setOwnerObj([ 'guid' => 1000, 'username' => 'ps' ])
            ->getUrl(true)
            ->shouldReturn('ps/blog/phpspec-test-5000');
    }

    function it_should_get_icon_url()
    {
        $this->header->resolve($this, 128)
            ->shouldBeCalled()
            ->willReturn('/icon.spec.ext');

        $this->getIconUrl(128)
            ->shouldReturn('/icon.spec.ext');
    }

    function it_should_set_and_get_slug()
    {
        $this
            ->getSlug()
            ->shouldReturn('');

        $this
            ->setSlug('phpspec')
            ->getSlug()
            ->shouldReturn('phpspec');

        $this
            ->setSlug('phpspec test')
            ->getSlug()
            ->shouldReturn('phpspec-test');
    }

    function it_should_set_and_get_custom_meta()
    {
        $this
            ->getCustomMeta()
            ->shouldReturn([
                'title' => '',
                'description' => '',
                'author' => ''
            ]);

        $this
            ->setCustomMeta([
                'title' => 'phpspec',
                'description' => 'a test >',
                'author' => 'ps'
            ])
            ->getCustomMeta()
            ->shouldReturn([
                'title' => 'phpspec',
                'description' => 'a test &#62;',
                'author' => 'ps'
            ]);
    }

    function it_should_get_excerpt()
    {
        $this
            ->setBody('a test &nbsp; :)')
            ->getExcerpt()
            ->shouldReturn('a test  :)');

        $this
            ->setExcerpt('custom excerpt')
            ->getExcerpt()
            ->shouldReturn('custom excerpt');
    }

    function it_should_check_if_user_can_edit(
        User $user
    )
    {
        $this->acl->write($this, $user)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->canEdit($user)
            ->shouldReturn(true);
    }

    function it_should_correctly_export_published_state()
    {
        $this->eventsDispatcher->trigger('export:extender', Argument::cetera())
            ->willReturn([]);

        $export = $this->export()->getWrappedObject();
        expect($export['published'])->toBe(true);

        $this->setPublished('');
        $export = $this->export()->getWrappedObject();
        expect($export['published'])->toBe(true);

        $this->setPublished('1');
        $export = $this->export()->getWrappedObject();
        expect($export['published'])->toBe(true);

        $this->setPublished('0');
        $export = $this->export()->getWrappedObject();
        expect($export['published'])->toBe(false);

        $this->setPublished(true);
        $export = $this->export()->getWrappedObject();
        expect($export['published'])->toBe(true);

        $this->setPublished(false);
        $export = $this->export()->getWrappedObject();
        expect($export['published'])->toBe(false);
    }
}
