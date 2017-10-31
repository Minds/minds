<?php
/**
 * Blog Lite View
 */

namespace Minds\Core\Blogs\Lite;

use Minds\Core\Email;


class View
{

    private $blog;

    public function setBlog($blog)
    {
        $this->blog = $blog;
        return $this;
    }

    public function render()
    {
        //lets use email templates
        $template = new Email\Template;
        $template->setTemplate('./lite.tpl');

        $template->setBody('./view.tpl');
        $template->set('title', $this->blog->title);

        $description = strip_tags($this->blog->description);

        if (strlen($description) > 140) {
            $description = substr($description, 0, 139) . '…';
        }
        $template->set('meta', [
          'title' => $this->blog->title,
          'description' => $description,
          'og:title' => $this->blog->title,
          'og:description' => $description,
          'og:url' => str_replace('http://', 'https://', $this->blog->getPermaUrl()),
          'og:type' => 'article',
          'og:image' => $this->blog->getIconUrl(800),
          'og:image:width' => 2000,
        ]);
        $template->set('blog', $this->blog);

        return $template->render();
    }

}
