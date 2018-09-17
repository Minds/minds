<?php


namespace Minds\Core\Email\Campaigns;

use Minds\Core;
use Minds\Core\Config;
use Minds\Core\Email\Mailer;
use Minds\Core\Email\Message;
use Minds\Core\Email\Template;
use Minds\Core\Blogs\Blog;

class WithBlogs extends EmailCampaign
{
    protected $template;
    protected $mailer;

    /** @var Blog[] */
    protected $blogs;

    public function __construct(Template $template = null, Mailer $mailer = null)
    {
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
        $this->campaign = 'with';
        $this->topic = 'top_posts';
    }

    /**
     * @param Blog[] $blogs
     * @return $this
     */
    public function setBlogs($blogs)
    {
        $this->blogs = $blogs;
        return $this;
    }

    public function send()
    {
        $this->template->setTemplate('default.tpl');

        $this->template->setBody("./Templates/missed-blogs.tpl");

        $validatorHash = sha1($this->campaign . $this->user->guid . Config::_()->get('emails_secret'));

        $this->template->set('user', $this->user);
        $this->template->set('guid', $this->user->guid);
        $this->template->set('username', $this->user->username);
        $this->template->set('email', $this->user->getEmail());

        $legacyEntity = new Core\Blogs\Legacy\Entity();

        $this->blogs = array_map(function ($blog) use ($legacyEntity) {
            if (get_class($blog) !== Blog::class) {
                return $legacyEntity->build((array) $blog->export());
            }
            return $blog;
        }, $this->blogs);

        $this->template->set('entities', $this->blogs);

        $this->template->set('campaign', $this->campaign);
        $this->template->set('topic', $this->topic);

        $this->template->set('validator', $validatorHash);

        $subject = "Here's 10 fascinating top articles";

        $message = new Message();
        $message->setTo($this->user)
            ->setMessageId(implode('-',
                [$this->user->guid, sha1($this->user->getEmail()), sha1($this->campaign . $this->topic . time())]))
            ->setSubject($subject)
            ->setHtml($this->template);

        //send email
        $this->mailer->send($message);
    }
}
