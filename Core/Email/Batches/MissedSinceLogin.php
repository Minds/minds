<?php
namespace Minds\Core\Email\Batches;

use Minds\Core;
use Minds\Core\Email\Campaigns;
use Minds\Core\Email\EmailSubscribersIterator;

class MissedSinceLogin implements EmailBatchInterface
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

        $blogs = $this->getTrendingBlogs();

        foreach ($iterator as $user) {

            if ($user->getTimeCreated() > strtotime('-28 days ago')) {
                echo "[done]";
                return true;
            }

            $campaign = new Campaigns\WithBlogs();

            $campaign
                ->setUser($user)
                ->setTemplateKey($this->templatePath)
                ->setSubject($this->subject)
                ->setBlogs($blogs)
                ->send();
        }
    }

    private function getTrendingBlogs()
    {
        ACL::$ignore = true;
        /*$result = $this->repository->getList([
            'type' => 'blogs',
            'limit' => 10
        ]);

        if (!$result || !$result['guids'] || count($result['guids']) === 0) {
            return [];
        }

        ksort($result['guids']);
        $options['guids'] = $result['guids'];*/

        $options['guids'] = [
            '835202980980359168',
            '830474920123518976',
            '829173579273281536',
            '843244085469986816',
            '833486289467371520',
            '832564175645118464',
            '836606521873772544',
            '834540240379695104',
            '837814892017152000',
            '826188573910073344',
        ];

        $blogs = $this->builder->get(array_merge([
            'subtype' => 'blog',
        ], $options));

        return $blogs;
    }

}
