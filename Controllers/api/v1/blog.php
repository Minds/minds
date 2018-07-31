<?php
/**
 * Minds Blog API
 *
 * @version 1
 * @author Mark Harding
 */

namespace Minds\Controllers\api\v1;

use Minds\Api\Exportable;
use Minds\Api\Factory;
use Minds\Core;
use Minds\Entities\Activity;
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
        $response = [];

        if (!isset($pages[0])) {
            $pages[0] = "top";
        }

        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 12;
        $offset = isset($_GET['offset']) ? (string) $_GET['offset'] : '';

        $repository = new Core\Blogs\Repository();
        $trending = new Core\Blogs\Trending();
        $manager = new Core\Blogs\Manager();
        $headerManager = new Core\Blogs\Header();

        switch ($pages[0]) {
            case "all":
                if (!Core\Session::isAdmin()) {
                    $response['entities'] = new Exportable([]);
                    $response['load-next'] = '';
                    break;
                }

                $blogs = $repository->getList([
                    'limit' => $limit,
                    'offset' => $offset,
                    'all' => true,
                ]);

                $response['entities'] = new Exportable($blogs);
                $response['load-next'] = $blogs->getPagingToken();
                break;

            case "trending":
            case "top":
                $blogs = $trending->getList([
                    'limit' => $limit,
                    'offset' => $offset,
                    'rating' => isset($_GET['rating']) ? (int) $_GET['rating'] : 1,
                ]);

                $response['entities'] = new Exportable($blogs);
                $response['load-next'] = $blogs->getPagingToken();
                break;

            case "network":
            case "owner":
                $opts = [
                    'limit' => $limit,
                    'offset' => $offset,
                ];

                $guid = isset($pages[1]) ? $pages[1] : Core\Session::getLoggedInUserGuid();

                if (isset($pages[1]) && !is_numeric($pages[1])) {
                    $lookup = new Core\Data\lookup();
                    $guid = key($lookup->get(strtolower($pages[1])));
                }

                if ($pages[0] === 'network') {
                    $opts['network'] = $guid;
                } elseif ($pages[0] === 'owner') {
                    $opts['container'] = $guid;
                }

                $blogs = $repository->getList($opts);

                $response['entities'] = new Exportable($blogs);
                $response['load-next'] = $blogs->getPagingToken();
                break;

            case "next":
                if (!isset($pages[1])) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => 'Not blog reference guid provided'
                    ]);
                }

                $blog = $manager->get($pages[1]);
                $response['blog'] = $manager->getNext($blog);
                break;

            case "header":
                $blog = $manager->get($pages[1]);
                $header = $headerManager->read($blog);

                header('Content-Type: image/jpeg');
                header('Expires: ' . date('r', time() + 864000));
                header("Pragma: public");
                header("Cache-Control: public");

                try {
                    echo $header->read();
                } catch (\Exception $e) { }

                exit;

            default:
                if (is_numeric($pages[0]) || Core\Luid::isValid($pages[0])) {
                    $blog = $manager->get($pages[0]);

                    if (
                        !$blog ||
                        Helpers\Flags::shouldFail($blog) ||
                        !Core\Security\ACL::_()->read($blog)
                    ) break;

                    $response['blog'] = $blog;
                }
                break;
        }

        return Factory::response($response);
    }

    public function post($pages)
    {
        Factory::isLoggedIn();

        $manager = new Core\Blogs\Manager();
        $header = new Core\Blogs\Header();

        $response = [];

        $editing = isset($pages[0]) && (is_numeric($pages[0]) || Core\Luid::isValid($pages[0]));

        if ($editing) {
            $blog = $manager->get($pages[0]);

            $originallyPublished = $blog->isPublished();
        } else {
            $blog = new Core\Blogs\Blog();
            $blog
                ->setOwnerObj(Core\Session::getLoggedinUser())
                ->setContainerGuid(Core\Session::getLoggedInUserGuid());
        }

        if (isset($_POST['title'])) {
            $blog->setTitle($_POST['title']);
        }

        if (isset($_POST['description'])) {
            $blog->setBody($_POST['description']);
        } elseif (isset($_POST['body'])) {
            $blog->setBody($_POST['body']);
        }

        if (isset($_POST['access_id'])) {
            $blog->setAccessId($_POST['access_id']);
        }

        if (isset($_POST['license'])) {
            $blog->setLicense($_POST['license']);
        }

        if (isset($_POST['category'])) {
            $blog->setCategories([ $_POST['category'] ]);
        } elseif (isset($_POST['categories'])) {
            $blog->setCategories($_POST['categories']);
        }

        if (isset($_POST['mature'])) {
            $blog->setMature(!!$_POST['mature']);
        }

        if (isset($_POST['wire_threshold'])) {
            $blog->setWireThreshold($_POST['wire_threshold']);
        }

        if (isset($_POST['published'])) {
            $blog->setPublished(!!$_POST['published']);
        }

        if (isset($_POST['monetized'])) {
            $blog->setMonetized(!!$_POST['monetized']);
        }

        if (isset($_POST['slug'])) {
            $blog->setSlug($_POST['slug']);
        }

        if (isset($_POST['custom_meta']) && is_array($_POST['custom_meta'])) {
            $blog->setCustomMeta($_POST['custom_meta']);
        }

        //draft
        if (!$_POST['published'] || $_POST['published'] === 'false') {
            $blog->setAccessId(0);
            $blog->setDraftAccessId($_POST['access_id']);
        } elseif ($blog->getTimePublished() == '') {
            $blog->setTimePublished(time());
        }

        $blog->setLastSave(time());

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

        if (isset($_POST['paywall']) && (!$_POST['paywall'] || $_POST['paywall'] === 'false')) {
            $blog->setWireThreshold(false);
            $blog->setPaywall(false);
        }

        if ($blog->isMonetized()) {
            if ($blog->isMature()) {
                return Factory::response([
                    'status' => 'error',
                    'message' => 'Cannot monetize an explicit blog'
                ]);
            } elseif (!Core\Session::isAdmin()) {
                $merchant = Core\Session::getLoggedInUser()->getMerchant();

                if (!$merchant || !isset($merchant['id'])) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => 'User is not a merchant'
                    ]);
                }
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

        if (!$blog->getBody()) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Sorry, your blog must have some content'
            ]);
        }

        try {
            if ($editing) {
                $saved = $manager->update($blog);
            } else {
                $saved = $manager->add($blog);
            }
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        if ($saved && is_uploaded_file($_FILES['file']['tmp_name'])) {
            $image = get_resized_image_from_uploaded_file('file', 2000, 10000);
            $header->write($blog, $image, isset($_POST['header_top']) ? (int) $_POST['header_top'] : 0);
        }

        if ($saved) {
            $createActivity = new Core\Blogs\Delegates\CreateActivity();

            if (
                !$editing &&
                $blog->isPublished() &&
                $blog->getAccessId() == 2
            ) {
                $createActivity->save($blog);
            } elseif (
                $editing &&
                !$originallyPublished &&
                $blog->isPublished() &&
                $blog->getAccessId() == 2
            ) {
                $createActivity->save($blog);
            }

            $response['guid'] = (string) $blog->getGuid();
            $response['slug'] = $blog->getSlug();
            $response['route'] = $blog->getUrl(true);
        }

        return Factory::response($response);
    }

    public function put($pages)
    {
        Factory::isLoggedIn();

        $manager = new Core\Blogs\Manager();
        $header = new Core\Blogs\Header();

        if (isset($pages[0]) && is_numeric($pages[0])) {
            $blog = $manager->get($pages[0]);
        } else {
            $blog = new Core\Blogs\Blog();
        }

        if (is_uploaded_file($_FILES['header']['tmp_name'])) {
            $image = get_resized_image_from_uploaded_file('header', 2000, 10000);
            $header->write($blog, $image, isset($_POST['header_top']) ? (int) $_POST['header_top'] : 0);
        }

        return Factory::response([]);
    }

    public function delete($pages)
    {
        Factory::isLoggedIn();

        $manager = new Core\Blogs\Manager();

        $blog = $manager->get($pages[0]);

        if ($blog && $blog->canEdit()) {
            $manager->delete($blog);
        }

        return Factory::response([]);
    }
}
