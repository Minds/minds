<?php

namespace Minds\Core\Email\Batches;

use Minds\Core\Email\Campaigns;
use Minds\Core\Email\EmailSubscribersIterator;
use Minds\Traits\MagicAttributes;

class News implements EmailBatchInterface
{
    use MagicAttributes;
    protected $offset;
    protected $templateKey;
    protected $subject;

    /**
     * @param string $offset
     *
     * @return Promotion
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    public function setDryRun($bool)
    {
        return $this;
    }

    /**
     * @param string $templateKey
     *
     * @return Promotion
     */
    public function setTemplateKey($templateKey)
    {
        $this->templateKey = $templateKey;

        return $this;
    }

    /**
     * @param string $subject
     *
     * @return Promotion
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function run()
    {
        if (!$this->templateKey || $this->templateKey == '') {
            throw new \Exception('You must set the templatePath');
        }
        if (!$this->subject || $this->subject == '') {
            throw new \Exception('You must set the subject');
        }

        $iterator = new EmailSubscribersIterator();
        $iterator->setCampaign('global')
            ->setTopic('minds_news')
            ->setValue(true)
            ->setOffset($this->offset);

        $i = 0;
        foreach ($iterator as $user) {
            $user = new \Minds\Entities\User('mark');
            $user->bounced = false;
            if ($user->bounced) {
                echo "\n[$i]: $user->guid ($iterator->offset) bounced";
                continue;
            }
            ++$i;

            $campaign = new Campaigns\News();

            $campaign
                ->setUser($user)
                ->setTemplateKey($this->templateKey)
                ->setSubject($this->subject)
                ->send();
            echo "\n[$i]: $user->guid ($iterator->offset)";
            exit;
        }
    }
}
