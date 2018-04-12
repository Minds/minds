<?php
/**
 * Minds Blog API
 *
 * @version 1
 * @author Mark Harding
 */

namespace Minds\Controllers\api\v1;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Entities\Activity;
use Minds\Entities\User;
use Minds\Helpers;
use Minds\Interfaces;

class blog implements Interfaces\Api
{
    /**
     * Returns the conversations or conversation
     * @param array $pages
     *
     * API:: /v1/blog/:filter
     */
    public function get($pages)
    {
        $response = array();

        if (!isset($pages[0])) {
            $pages[0] = "featured";
        }

        $limit = isset($_GET['limit']) ? $_GET['limit'] : 12;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : "";

        switch ($pages[0]) {
            case "all":
                $entities = core\Entities::get(array(
                    'subtype' => 'blog',
                    'offset' => $offset,
                    'limit' => $limit
                ));
                $response['entities'] = Factory::exportable($entities);
                $response['load-next'] = (string) end($entities)->guid;
                break;
            case "featured":
                $guids = Core\Data\indexes::fetch('object:blog:featured',
                    array('offset' => $offset, 'limit' => $limit));
                if (!$guids) {
                    break;
                }
                $entities = core\Entities::get(array('guids' => $guids));
                usort($entities, function ($a, $b) {
                    if ((int) $a->featured_id == (int) $b->featured_id) {
                        return 0;
                    }
                    return ((int) $a->featured_id < (int) $b->featured_id) ? 1 : -1;
                });
                $response['blogs'] = Factory::exportable($entities);
                $response['load-next'] = (string) end($entities)->featured_id;
                break;
            case "trending":
            case "top":
                $repository = Core\Di\Di::_()->get('Trending\Repository');
                $result = $repository->getList([
                    'type' => 'blogs',
                    'rating' => isset($_GET['rating']) ? (int) $_GET['rating'] : 1,
                    'limit' => $limit,
                    'offset'=> $offset,
                ]);

                if (!$result) {
                    break;
                }
                $entities = core\Entities::get(['guids' => $result['guids']]);
                $response['entities'] = Factory::exportable($entities);
                $response['load-next'] = base64_encode($result['token']);
                break;
            case "network":
                if (isset($pages[1]) && !is_numeric($pages[1])) {
                    $lookup = new Core\Data\lookup();
                    $pages[1] = key($lookup->get(strtolower($pages[1])));
                }
                $entities = core\Entities::get([
                    'subtype' => 'blog',
                    'offset' => $offset,
                    'limit' => $limit,
                    'network' => isset($pages[1]) ? $pages[1] : Core\Session::getLoggedinUserGuid()
                ]);
                $response['entities'] = Factory::exportable($entities);
                $response['load-next'] = $entities ? (string) end($entities)->guid : null;
                break;
                break;
            case "owner":
                if (isset($pages[1]) && !is_numeric($pages[1])) {
                    $lookup = new Core\Data\lookup();
                    $pages[1] = key($lookup->get(strtolower($pages[1])));
                }
                $entities = core\Entities::get([
                    'subtype' => 'blog',
                    'owner_guid' => isset($pages[1]) ? $pages[1] : \Minds\Core\Session::getLoggedInUser()->guid,
                    'offset' => $offset,
                    'limit' => $limit
                ]);
                $response['entities'] = Factory::exportable($entities);
                $response['load-next'] = $entities ? (string) end($entities)->guid : null;
                break;
            case "next":
                if (!isset($pages[1])) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => 'Not blog reference guid provided'
                    ]);
                }
                $blog = new \Minds\Entities\Blog($pages[1]);
                $db = new Core\Data\Call('entities_by_time');

                //try to get same owner first
                //$blogs = $db->getRow("object:blog:user:$blog->owner_guid", ['limit'=> 1, 'offset' => $blog->guid-1]);
                //if(!$blogs){
                $offset = $blog->featured_id ? $blog->featured_id - 1 : "";
                $blogs = $db->getRow("object:blog:featured", ['limit' => 1, 'offset' => $offset]);
                //}

                if (!$blogs) {
                    return Factory::response([]);
                }

                //$guids = array_keys($blogs);
                $guids = array_values($blogs);
                $blog = new \Minds\Entities\Blog($guids[0]);
                $response['blog'] = $blog->export();
                $owner = new user($blog->ownerObj);
                $response['blog']['ownerObj'] = $owner->export();
                $response['blog']['description'] = (new Core\Security\XSS())->clean($response['blog']['description']);
                break;
            case is_numeric($pages[0]):
                $guid = $pages[0];
                if (strlen($guid) < 15) {
                    $guid = (new \GUID())->migrate($guid);
                }
                $blog = new \Minds\Entities\Blog($guid);
                if (!$blog->title && !$blog->description || Helpers\Flags::shouldFail($blog) || !Core\Security\ACL::_()->read($blog)) {
                    break;
                }
                $blog->fullExport = true;
                $response['blog'] = $blog->export();
                //provide correct subscribe info for userobj (renormalize)
                $owner = new user($blog->ownerObj);
                $response['blog']['ownerObj'] = $owner->export();
                $response['blog']['description'] = (new Core\Security\XSS())->clean($response['blog']['description']);
                break;
            case "header":
                $blog = new \Minds\Entities\Blog($pages[1]);
                $header = new \ElggFile();
                $header->owner_guid = $blog->owner_guid;
                $header->setFilename("blog/{$blog->guid}.jpg");
                $header->open('read');

                header('Content-Type: image/jpeg');
                header('Expires: ' . date('r', time() + 864000));
                header("Pragma: public");
                header("Cache-Control: public");

                try {
                    echo $header->read();
                } catch (\Exception $e) {
                }
                exit;
                break;
        }


        return Factory::response($response);
    }

    public function post($pages)
    {
        Factory::isLoggedIn();

        $response = [];
        $newBlog = false;

        if (isset($pages[0]) && is_numeric($pages[0])) {
            $blog = new \Minds\Entities\Blog($pages[0]);
        } else {
            $blog = new \Minds\Entities\Blog();
            $newBlog = true;
        }

        $original_access = $blog->access_id;
        $original_published = $blog->published;
        $allowed = array('title', 'description', 'access_id', 'status', 'license', 'mature', 'monetized', 'wire_threshold', 'category', 'categories', 'published');

        foreach ($allowed as $v) {
            if (isset($_POST[$v])) {
                $blog->$v = $_POST[$v];
            }
        }

        if (isset($_POST['slug'])) {
            $blog->setSlug($_POST['slug']);
        }

        if (isset($_POST['custom_meta']) && is_array($_POST['custom_meta'])) {
            $blog->setCustomMeta($_POST['custom_meta']);
        }

        //draft
        if (!$_POST['published']) {
            $blog->access_id = 0;
            $blog->draft_access_id = $_POST['access_id'];
        }
        $blog->last_save = time();

        if (isset($_POST['wire_threshold'])) {
            if (is_array($_POST['wire_threshold']) && ($_POST['wire_threshold']['min'] <= 0 || !$_POST['wire_threshold']['type'])) {
                return Factory::response([
                    'status' => 'error',
                    'message' => 'Invalid Wire threshold'
                ]);
            }

            $blog->setWireThreshold($_POST['wire_threshold']);
            $blog->setPaywall(!!$_POST['wire_threshold']);
        }

        if (isset($_POST['paywall']) && !$_POST['paywall']) {
            $blog->setWireThreshold(false);
            $blog->setPaywall(false);
        }

        if ($blog->monetized && $blog->mature) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Cannot monetize an explicit blog'
            ]);
        }

        if ($blog->monetized && !Core\Session::isAdmin()) {
            $merchant = Core\Session::getLoggedInUser()->getMerchant();

            if (!$merchant || !isset($merchant['id'])) {
                return Factory::response([
                    'status' => 'error',
                    'message' => 'User is not a merchant'
                ]);
            }
        }

        if (isset($_POST['mature']) && $_POST['mature']) {
            $user = Core\Session::getLoggedInUser();
            if (!$user->getMatureContent()) {
                $user->setMatureContent(true);
                $user->save();
            }
        }

        if (!$blog->canEdit()) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Sorry, you do not have permission'
            ]);
        }

        $blog->save();

        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            $resized = get_resized_image_from_uploaded_file('file', 2000, 10000);
            $file = new \ElggFile();
            $file->owner_guid = $blog->owner_guid;
            $file->setFilename("blog/{$blog->guid}.jpg");
            $file->open('write');
            $file->write($resized);
            $file->close();
            $blog->header_bg = true;
            $blog->header_top = $_POST['header_top'] ?: 0;
            $blog->last_updated = time();
            $blog->save();
        }

        $response['guid'] = (string) $blog->guid;
        $response['slug'] = $blog->slug;
        $response['route'] = $blog->getUrl(true);

        if ($blog->monetized) {
            (new Core\Payments\Plans\PaywallReview())
                ->setEntityGuid($blog->guid)
                ->add();
        }

        $activity_post = false;
        if ((!isset($pages[0]) || $pages[0] == "new") && $blog->access_id == 2) {
            $activity_post = true;
        } elseif ($original_published !== $blog->published && !$original_published && $original_access != 2 && $blog->access_id == 2) {
            $activity_post = true;
        }

        if ($activity_post) {
            (new Activity())
                ->setTitle($blog->title)
                ->setBlurb(strip_tags($blog->description))
                ->setURL($blog->getURL())
                ->setThumbnail($blog->getIconUrl())
                ->setFromEntity($blog)
                ->setMature($blog->getMature())
                ->save();
        }

        if ($newBlog) {
            Helpers\Wallet::createTransaction($blog->owner_guid, 15, $blog->guid, 'Blog');
        }

        return Factory::response($response);
    }

    public function put($pages)
    {
        Factory::isLoggedIn();

        if (isset($pages[0]) && is_numeric($pages[0])) {
            $blog = new \Minds\Entities\Blog($pages[0]);
        } else {
            $blog = new \Minds\Entities\Blog();
        }

        if (is_uploaded_file($_FILES['header']['tmp_name'])) {
            $resized = get_resized_image_from_uploaded_file('header', 2000, 10000);
            $file = new \ElggFile();
            $file->owner_guid = $blog->owner_guid;
            $file->setFilename("blog/{$blog->guid}.jpg");
            $file->open('write');
            $file->write($resized);
            $file->close();
            $blog->header_bg = true;
            $blog->last_updated = time();
        }

        $blog->save();

        return Factory::response(array());
    }

    public function delete($pages)
    {
        $blog = new \Minds\Entities\Blog($pages[0]);
        $deleted = $blog->delete();

        if ($deleted) {
            Helpers\Wallet::createTransaction($blog->owner_guid, -15, $blog->guid, 'Blog Deleted');
        }
        return Factory::response(array());
    }
}
