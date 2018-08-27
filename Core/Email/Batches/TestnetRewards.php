<?php


namespace Minds\Core\Email\Batches;

use Minds\Core\Email\Campaigns;
use Minds\Core\Email\EmailSubscribersIterator;

class TestnetRewards implements EmailBatchInterface
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

    public function setDryRun($bool)
    {
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
        $iterator = new EmailSubscribersIterator();
        $iterator->setCampaign('global')
            ->setTopic('minds_news')
            ->setValue(true)
            ->setOffset($this->offset);

        $i = 0;
        foreach ($iterator as $user) {
            $user = new \Minds\Entities\User('ottman');
            if ($user->bounced && false) {
                continue;
            }
            $i++;
            $campaign = new Campaigns\TestnetRewards();

            $campaign
                ->setUser($user)
                ->send();
            echo "\n[$i]: $user->guid ($iterator->offset)";
            exit;
        }
    }
}
