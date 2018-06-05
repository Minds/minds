<?php
/**
 * Minds Channel API
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Helpers;
use Minds\Interfaces;
use Minds\Entities;
use Minds\Api\Factory;
use ElggFile;

class channel implements Interfaces\Api
{
    /**
     * Return channel profile information
     * @param array $pages
     *
     * API:: /v1/channel/:username
     */
    public function get($pages)
    {
        if ($pages[0] == 'me') {
            $pages[0] = elgg_get_logged_in_user_guid();
        }

        if (is_string($pages[0]) && !is_numeric($pages[0])) {
            $pages[0] = strtolower($pages[0]);
        }

        $user = new Entities\User($pages[0]);
        if (!$user->username || Helpers\Flags::shouldFail($user)) {
            return Factory::response(array('status'=>'error', 'message'=>'The user could not be found'));
        }

        if ($user->enabled != "yes") {
            return Factory::response(array('status'=>'error', 'message'=>'The user is disabled'));
        }

        if ($user->banned == 'yes' && !Core\Session::isAdmin()) {
            return Factory::response(array('status'=>'error', 'message'=>'The user is banned'));
        }

        $user->fullExport = true; //get counts
        $user->exportCounts = true;
        $return = Factory::exportable(array($user));

        $response['channel'] = $return[0];
        if (Core\Session::getLoggedinUser()->guid == $user->guid) {
            $response['channel']['admin'] = $user->admin;
        }
        $response['channel']['avatar_url'] = array(
            'tiny' => $user->getIconURL('tiny'),
            'small' => $user->getIconURL('small'),
            'medium' => $user->getIconURL('medium'),
            'large' => $user->getIconURL('large'),
            'master' => $user->getIconURL('master')
        );

        $response['channel']['briefdescription'] = $response['channel']['briefdescription'] ?: '';
        $response['channel']['city'] = $response['channel']['city'] ?: "";
        $response['channel']['gender'] = $response['channel']['gender'] ?: "";
        $response['channel']['dob'] = $response['channel']['dob'] ?: "";

        if (!$user->merchant || !$supporters_count) {
            $db = new Core\Data\Call('entities_by_time');
            //$feed_count = $db->countRow("activity:user:" . $user->guid);
            $response['channel']['activity_count'] = $feed_count;
        }

        $carousels = Core\Entities::get(array('subtype'=>'carousel', 'owner_guid'=>$user->guid));
        if ($carousels) {
            foreach ($carousels as $carousel) {
                $response['channel']['carousels'][] = array(
                  'guid' => (string) $carousel->guid,
                  'top_offset' => $carousel->top_offset,
                  'src'=> Core\Config::_()->cdn_url . "fs/v1/banners/$carousel->guid/fat/$carousel->last_updated"
                );
            }
        }

        $block = Core\Security\ACL\Block::_();
        $response['channel']['blocked'] = $block->isBlocked($user);

        return Factory::response($response);
    }

    public function post($pages)
    {
        Factory::isLoggedIn();
        $owner = Core\Session::getLoggedinUser();
        $guid = Core\Session::getLoggedinUser()->guid;
        if (Core\Session::getLoggedinUser()->legacy_guid) {
            $guid = Core\Session::getLoggedinUser()->legacy_guid;
        }

        $response = [];

        switch ($pages[0]) {
            case "avatar":
                $icon_sizes = Core\Config::_()->get('icon_sizes');
                // get the images and save their file handlers into an array
                // so we can do clean up if one fails.
                $files = array();
                foreach ($icon_sizes as $name => $size_info) {
                    $resized = get_resized_image_from_uploaded_file('file', $size_info['w'], $size_info['h'], $size_info['square'], $size_info['upscale']);

                    if ($resized) {
                        //@todo Make these actual entities.  See exts #348.
                        $file = new ElggFile();
                        $file->owner_guid = Core\Session::getLoggedinUser()->guid;
                        $file->setFilename("profile/{$guid}{$name}.jpg");
                        $file->open('write');
                        $file->write($resized);
                        $file->close();
                        $files[] = $file;
                    } else {
                        // cleanup on fail
                        foreach ($files as $file) {
                            $file->delete();
                        }

                        return Factory::response([
                          'status' => 'error',
                          'message' => 'Could not resize'
                        ]);
                    }
                }

                $db = new Core\Data\Call('entities');
                $db->insert($owner->guid, [
                    'x1' => 0,
                    'x2' => 0,
                    'y1' => 0,
                    'y2' => 0,
                    'icontime' => time()
                ]);

                break;
            case "banner":
                //remove all older banners
                try {
                    $db = new Core\Data\Call('entities_by_time');
                    $db->removeRow("object:carousel:user:" . elgg_get_logged_in_user_guid());
                } catch (\Exception $e) {
                }

                $item = new \Minds\Entities\Object\Carousel();
                $item->title = '';
                $item->owner_guid = elgg_get_logged_in_user_guid();
                $item->access_id = ACCESS_PUBLIC;
                $item->save();

                if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                    $resized = get_resized_image_from_uploaded_file('file', 2000, 10000);
                    $file = new Entities\File();
                    $file->owner_guid = $item->owner_guid;
                    $file->setFilename("banners/{$item->guid}.jpg");
                    $file->open('write');
                    $file->write($resized);
                    $file->close();

                    $response['uploaded'] = true;
                }

                break;
            case "carousel":
              $item = new \Minds\Entities\Object\Carousel(isset($_POST['guid']) ? $_POST['guid'] : null);
              $item->access_id = ACCESS_PUBLIC;
              $item->top_offset = $_POST['top'];
              $item->last_updated = time();
              $item->save();

              $response['carousel'] = array(
                 'guid' => (string) $item->guid,
                 'top_offset' => $item->top_offset,
                 'src'=> Core\Config::build()->cdn_url . "fs/v1/banners/$item->guid/fat/$item->last_updated"
              );

              if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                  $resized = get_resized_image_from_uploaded_file('file', 2000, 10000);
                  $file = new Entities\File();
                  $file->owner_guid = $item->owner_guid;
                  $file->setFilename("banners/{$item->guid}.jpg");
                  $file->open('write');
                  $file->write($resized);
                  $file->close();

                  $response['uploaded'] = true;
              }


              break;
            case "info":
            default:
                if (!$owner->canEdit()) {
                    return Factory::response(array('status'=>'error'));
                }

                $update = array();
                foreach (['name', 'website', 'briefdescription', 'gender',
                  'dob', 'city', 'coordinates', 'monetized'] as $field) {
                    if (isset($_POST[$field])) {
                        $update[$field] = $_POST[$field];
                        $owner->$field = $_POST[$field];
                    }
                }

                /*try {
                    $spam = new Core\Security\Spam();
                    $spam->check($owner);
                } catch (\Exception $e) {
                    return Factory::response(['status'=>'error', 'message' => $e->getMessage() ]);
                }*/

                if (isset($_POST['social_profiles']) && is_array($_POST['social_profiles'])) {
                    $profiles = [];

                    foreach ($_POST['social_profiles'] as $profile) {
                        if (!isset($profile['key']) || !isset($profile['value'])) {
                            continue;
                        }

                        $key = $profile['key'];
                        $value = $profile['value'];

                        if (!$value || !is_string($value)) {
                            continue;
                        }

                        $profiles[] = [
                            'key' => $profile['key'],
                            'value' => $profile['value'],
                        ];
                    }

                    $owner->setSocialProfiles($profiles);
                    $update['social_profiles'] = json_encode($profiles);
                }

                //always update icon time on profile edit...
                $update['icontime'] = time();
                $owner->icontime = time();

                $db = new Core\Data\Call('entities');
                $db->insert($owner->guid, $update);
                //update session also
                Core\Session::regenerate(false, $owner);
                //sync our change to our other sessions
                (new Core\Data\Sessions())->syncAll($owner->guid);
       }

        return Factory::response($response);
    }

    public function put($pages)
    {
        return Factory::response(array());
    }

    /**
     * Deactivate an account
     */
    public function delete($pages)
    {
        if (!Core\Session::getLoggedinUser()) {
            return Factory::response(array('status' => 'error', 'message' => 'not logged in'));
        }

        switch ($pages[0]) {
            case "carousel":
                $db = new Core\Data\Call('entities_by_time');
                //  $db->removeAttributes("object:carousel:user:" . elgg_get_logged_in_user_guid());
                $item = new \Minds\Entities\Object\Carousel($pages[1]);
                $item->delete();
                break;
            default:
                $channel = Core\Session::getLoggedinUser();
                $channel->enabled = 'no';
                $channel->save();

                $customer = (new Core\Payments\Customer())
                    ->setUser($channel);

                $stripe = Core\Di\Di::_()->get('StripePayments');
                $customer = $stripe->getCustomer($customer);
                if ($customer) {
                    $stripe->deleteCustomer($customer);
                }
                (new Core\Data\Sessions())->destroyAll($channel->guid);
        }

        return Factory::response(array());
    }
}
