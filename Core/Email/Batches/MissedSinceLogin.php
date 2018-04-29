<?php
namespace Minds\Core\Email\Batches;

use Minds\Core;
use Minds\Core\Email\Campaigns;
use Minds\Core\Email\EmailSubscribersIterator;

class MissedSinceLogin implements EmailBatchInterface
{
    protected $offset;
    protected $templatePath;
    protected $subject;


    public function setDryRun($dry)
    {
        return $this;
    }

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
    public function setTemplateKey($template)
    {
        $this->templatePath = $template;
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

        $entities = Core\Entities::get([
            'guids' => [
                826188573910073344,
                836607056186159104,
                836167299971796992,
                742327794419113984,
                829801531647447040,
                831649915973378048,
                798792752159326208,
                721609138500542464,
                813793227177394176,
                823256224013205504,
                817408411232890880
            ]
        ]);

        foreach ($iterator as $user) {

            $campaign = new Campaigns\MissedSinceLogin();

            $campaign
                ->setUser($user)
                ->setTemplateKey($this->templatePath)
                ->setSubject($this->subject)
                ->setEntities($entities)
                ->send();
        }
    }
}
