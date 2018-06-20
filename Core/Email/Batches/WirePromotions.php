<?php
namespace Minds\Core\Email\Batches;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Security\ACL;
use Minds\Core\Email\Campaigns;
use Minds\Core\Email\EmailSubscribersIterator;

class WirePromotions implements EmailBatchInterface
{

    /** @var Manager */
    protected $manager;
    /** @var Repository */
    protected $repository;
    /** @var EntitiesBuilder */
    protected $builder;

    /** @var string $offset */
    protected $offset;

    /** @var string $offset */
    protected $templatePath;

    /** @var string $subject */
    protected $subject;
    
    public function __construct($manager = null, $trendingRepository = null, $builder = null)
    {
        $this->manager = $manager ?: Di::_()->get('Email\Manager');
        $this->repository = $trendingRepository ?: Di::_()->get('Trending\Repository');
        $this->builder = $builder ?: Di::_()->get('EntitiesBuilder');
    }

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
        //    throw new \Exception('You must set the templatePath');
        }
        if (!$this->subject || $this->subject == '') {
        //    throw new \Exception('You must set the subject');
        }

        $iterator = new EmailSubscribersIterator();
        $iterator->setCampaign('when')
            ->setTopic('wire_received')
            ->setValue(true)
            ->setOffset($this->offset);

        $i = 0;
        foreach ($iterator as $user) {
            $i++;
            echo "\n[$i]: $user->guid ($iterator->offset)";

            $campaign = new Campaigns\WhenWire();

            $campaign
                ->setUser($user)
                //->setTemplateKey($this->templatePath)
                ->setSubject("You've received a wire!")
                ->setPromotionKey('june-18')
                ->send();

            echo " sent";
        }
    }

}
