<?php


namespace Minds\Core\Email\Batches;

use Minds\Core\Email\Campaigns;
use Minds\Core\Email\EmailSubscribersIterator;

class Catchup implements EmailBatchInterface
{
    protected $offset;
    protected $templatePath;
    protected $subject;

    /**
     * @param string $offset
     * @return Catchup
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param string $templatePath
     * @return Catchup
     */
    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;
        return $this;

    }

    /**
     * @param string $subject
     * @return Catchup
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
        if (!$this->templatePath || $this->templatePath == '') {
            throw new \Exception('You must set the templatePath');
        }
        if (!$this->subject || $this->subject == '') {
            throw new \Exception('You must set the subject');
        }

        $iterator = new EmailSubscribersIterator();
        $iterator->setCampaign('with')
            ->setTopic('posts_missed_since_login')
            ->setValue(true)
            ->setOffset($this->offset);

        foreach ($iterator as $user) {

            $campaign = new Campaigns\Catchup();

            $campaign
                ->setUser($user)
                ->setTemplateKey($this->templatePath)
                ->setSubject($this->subject)
                ->send();
        }
    }
}