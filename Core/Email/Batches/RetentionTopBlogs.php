<?php


namespace Minds\Core\Email\Batches;


use Minds\Core\Analytics\Iterators\SignupsIterator;
use Minds\Core\Di\Di;
use Minds\Core\Email\Campaigns\WithBlogs;
use Minds\Core\Email\EmailSubscription;
use Minds\Core\Email\Manager;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Trending\Repository;
use Minds\Core\Security\ACL;

class RetentionTopBlogs implements EmailBatchInterface
{
    /** @var Manager */
    protected $manager;
    /** @var Repository */
    protected $repository;
    /** @var EntitiesBuilder */
    protected $builder;

    /** @var boolean $dryRun */
    protected $dryRun = false;

    protected $offset;
    protected $period = 28;

    public function __construct($manager = null, $trendingRepository = null, $builder = null)
    {
        $this->manager = $manager ?: Di::_()->get('Email\Manager');
        $this->repository = $trendingRepository ?: Di::_()->get('Trending\Repository');
        $this->builder = $builder ?: Di::_()->get('EntitiesBuilder');
    }

    /**
     * @param boolean $dryRun
     * return RetentionTopBlogs
     */
    public function setDryRun($dryRun)
    {
        $this->dryRun = $dryRun;
        return $this;
    }

    /**
     * @param string $subject 
     * return RetentionTopBlogs
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @param string $templateKey 
     * return RetentionTopBlogs
     */
    public function setTemplateKey($templateKey)
    {
        $this->templateKey = $templateKey;
        return $this;
    }

    /**
     * @param string $offset
     * @return RetentionTopBlogs
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * The retention period
     * @param int $period
     * @return RetentionTopBlogs
     */
    public function setPeriod($period)
    {
        $this->period = $period;
        return $this;
    }

    /**
     * @throws \Exception
     */
    public function run()
    {
        $iterator = new SignupsIterator();
        $iterator->setPeriod($this->period);

        $trendingBlogs = $this->getTrendingBlogs();

        $i = 0;
        foreach ($iterator as $user) {
            $i++;
            echo "\n[$i]:$user->guid ";

            //check if the user is subscribed
            $subscription = (new EmailSubscription())
                ->setUserGuid($user->guid)
                ->setCampaign('with')
                ->setTopic('top_posts');

            $campaign = new WithBlogs();
            
            echo "... sending";

            $campaign
                ->setUser($user)
                ->setBlogs($trendingBlogs)
                ->send();
        }
    }

    private function getTrendingBlogs()
    {
        ACL::$ignore = true;
        $result = $this->repository->getList([
            'type' => 'blogs',
            'limit' => 10
        ]);

        if (!$result || !$result['guids'] || count($result['guids']) === 0) {
            return [];
        }

        ksort($result['guids']);
        $options['guids'] = $result['guids'];

        $blogs = $this->builder->get(array_merge([
            'subtype' => 'blog',
        ], $options));

        return $blogs;
    }
}
