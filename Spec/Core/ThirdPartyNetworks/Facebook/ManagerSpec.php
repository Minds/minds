<?php

namespace Spec\Minds\Core\ThirdPartyNetworks\Facebook;

use Facebook\Facebook as FacebookSDK;
use Facebook\Helpers\FacebookRedirectLoginHelper;
use Minds\Core;
use Minds\Core\ThirdPartyNetworks\Networks\Facebook;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;


class ManagerSpec extends ObjectBehavior
{
    protected $_fbNetwork;
    protected $_lu;
    protected $_config;

    function let(Facebook $fbNetwork, Core\Data\Call $lu, Core\Config\Config $config)
    {
        $this->_fbNetwork = $fbNetwork;
        $this->_lu = $lu;
        $this->_config = $config;

        Core\Di\Di::_()->bind('Config', function ($di) use ($config) {
            return new $config;
        }, ['useFactory' => true]);


        $this->beConstructedWith($fbNetwork, $lu);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\ThirdPartyNetworks\Facebook\Manager');
    }

    function it_should_get_redirect_url(FacebookSDK $sdk, FacebookRedirectLoginHelper $helper)
    {
        $this->_fbNetwork->getFb()
            ->shouldBeCalled()
            ->willReturn($sdk);

        $sdk->getRedirectLoginHelper()
            ->shouldBeCalled()
            ->willReturn($helper);

        $helper->getReRequestUrl(Argument::any(), ['email'])
            ->shouldBeCalled()
            ->willReturn('url');

        $this->getRedirectUrl()->shouldReturn('url');
    }

    function it_should_check_fb_account_wasnt_associated(FacebookSDK $sdk, FacebookRedirectLoginHelper $helper)
    {
        $fb_user = [
            'id' => '123',
            'name' => 'User Name'
        ];
        $this->_lu->getRow(Argument::containingString('fb:' . $fb_user['id']))
            ->willReturn(null);

        $this->checkFbAccount($fb_user)->shouldReturn(true);
    }

    function it_should_check_fb_account_wasnt_associated_and_throw_exception()
    {
        $fb_user = [
            'id' => '123',
            'name' => 'User Name'
        ];
        $this->_lu->getRow(Argument::containingString('fb:' . $fb_user['id']))
            ->willReturn(['123' => '123']);

        $this->shouldThrow(new \Exception('This account is already associated'))->duringCheckFbAccount($fb_user);
    }

    function it_should_generate_a_username()
    {
        $fb_user = [
            'id' => '123',
            'name' => 'User Name'
        ];

        $this->_lu->getRow(Argument::any())
            ->willReturn(null);

        $this->generateUsername($fb_user)->shouldContain('username');
    }
}
