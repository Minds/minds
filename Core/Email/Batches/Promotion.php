<?php


namespace Minds\Core\Email\Batches;

use Minds\Core\Email\Campaigns;
use Minds\Core\Email\EmailSubscribersIterator;

class Promotion implements EmailBatchInterface
{
    protected $offset;
    protected $templateKey;
    protected $subject;

    /**
     * @param string $offset
     * @return Promotion
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param string $templateKey
     * @return Promotion
     */
    public function setTemplateKey($templateKey)
    {
        $this->templateKey = $templateKey;
        return $this;

    }

    /**
     * @param string $subject
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
            ->setTopic('exclusive_promotions')
            ->setValue(true)
            ->setOffset($this->offset);

        foreach ($iterator as $user) {
            $campaign = new Campaigns\Promotion();

            $campaign
                ->setUser($user)
                ->setTemplateKey($this->templateKey)
                ->setSubject($this->subject)
                ->send();
        }
    }
}