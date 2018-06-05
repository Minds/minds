<?php


namespace Minds\Core\Email\Batches;

use Minds\Core\Email\Campaigns;
use Minds\Core\Email\EmailSubscribersIterator;

class InactiveUsers implements EmailBatchInterface
{
    /** @var string $offset */
    protected $offset;

    /** @var string $templateKey */
    protected $templateKey;

    /** @var string $subject */
    protected $subject;

    /** @var bool $dryRun */
    protected $dryRun = false;

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
     * @param bool $dryRun
     * @return Promotion
     */
    public function setDryRun($dryRun)
    {
        $this->dryRun = $dryRun;
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
        $iterator
            ->setDryRun($this->dryRun)
            ->setCampaign('global')
            ->setTopic('minds_news')
            ->setValue(true)
            ->setOffset($this->offset);

        $i = 0;
        foreach ($iterator as $user) {
            if ($user->last_login > strtotime('1 year ago')) {
                echo "\n[$i]:$user->guid ($iterator->offset) (active)";
                continue;
            }
            
            $i++;
            echo "\n[$i]:$user->guid ($iterator->offset)";

            $campaign = new Campaigns\InactiveUsers();

            $campaign
                ->setUser($user)
                ->setTemplateKey($this->templateKey)
                ->setSubject($this->subject)
                ->send();
            echo " (queued)";
        }
    }
}
