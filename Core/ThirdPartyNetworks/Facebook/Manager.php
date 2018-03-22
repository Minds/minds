<?php


namespace Minds\Core\ThirdPartyNetworks\Facebook;

use Minds\Core;
use Minds\Core\ThirdPartyNetworks\Networks\Facebook;
use Minds\Entities\User;

class Manager
{
    protected $facebook;
    protected $lu;

    public function __construct(Facebook $fbNetwork = null, Core\Data\Call $lu = null)
    {
        $this->facebook = $fbNetwork ? $fbNetwork : new Facebook();
        $this->lu = $lu ? $lu : new Core\Data\Call('user_index_to_guid');
    }

    public function getRedirectUrl()
    {
        $helper = $this->facebook->getFb()->getRedirectLoginHelper();
        $url = $helper->getReRequestUrl(Core\Config::_()->site_url . 'api/v1/thirdpartynetworks/facebook/login-callback',
            [
                'email'
            ]);

        return $url;
    }

    public function checkFbAccount($fb_user)
    {
        $fb_uuid = $fb_user['id'];
        $user_guids = $this->lu->getRow("fb:$fb_uuid");
        if ($user_guids) {
            throw new \Exception('This account is already associated');
        }

        return true;
    }

    public function generateUsername($fb_user)
    {
        $username = strtolower(preg_replace("/[^[:alnum:]]/u", '', $fb_user['name']));

        while ($this->lu->getRow($username)) {
            $username .= rand(0, 100);
        }
        return $username;
    }

    /**
     * @param $username
     * @param $password
     * @param $fb_user
     * @return User
     * @throws \Exception
     * @throws \Minds\Exceptions\StopEventException
     * @throws \RegistrationException
     */
    public function register($username, $password, $fb_user)
    {
        $lu = new Core\Data\Call('user_index_to_guid');
        if ($lu->getRow($username)) {
            throw new \Exception('Username already exists.');
        }

        $user = register_user($username, $password, $fb_user['name'] ?: $username, $fb_user['email'], false);

        $params = [
            'user' => $user,
            'password' => $_POST['password'],
            'friend_guid' => "",
            'invitecode' => ""
        ];
        elgg_trigger_plugin_hook('register', 'user', $params, true);

        $fb_uuid = $fb_user['id'];

        //pull in avatar
        $avatar_url = "https://graph.facebook.com/{$fb_uuid}/picture?type=large&width=720&height=720";
        $user->icontime = $this->saveAvatar($user, $avatar_url);
        $user->save();

        elgg_trigger_plugin_hook('register', 'user', $params, true);
        Core\Events\Dispatcher::trigger('register', 'user', $params);
        Core\Events\Dispatcher::trigger('register/complete', 'user', $params);

        return $user;
    }

    private function saveAvatar($user, $url)
    {
        $icon_sizes = Core\Config::_()->get('icon_sizes');

        $img = file_get_contents($url);
        file_put_contents("/tmp/fb-" . md5($url), $img);

        $files = [];
        foreach ($icon_sizes as $name => $size_info) {
            $resized = get_resized_image_from_existing_file("/tmp/fb-" . md5($url), $size_info['w'], $size_info['h'],
                $size_info['square'], 0, 0, 0, 0, $size_info['upscale']);

            if ($resized) {
                //@todo Make these actual entities.  See exts #348.
                $file = new \ElggFile();
                $file->owner_guid = $user->guid;
                $file->setFilename("profile/{$user->guid}{$name}.jpg");
                $file->open('write');
                $file->write($resized);
                $file->close();
                $files[] = $file;
            } else {
                // cleanup on fail
                foreach ($files as $file) {
                    $file->delete();
                }
            }
        }
        unlink("/tmp/fb-" . md5($url));
        return time();
    }
}