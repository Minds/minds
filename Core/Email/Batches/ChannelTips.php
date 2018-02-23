<?php

namespace Minds\Core\Email\Batches;

use Minds\Core\Di\Di;
use Minds\Core\Email\Campaigns\WithChannelTips;
use Minds\Core\Email\EmailSubscribersIterator;
use Minds\Core\Entities;
use Minds\Core\Trending\Repository;

class ChannelTips implements EmailBatchInterface
{
    protected $offset;

    /**
     * @param string $offset
     * @return ChannelTips
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function run()
    {
        $iterator = new EmailSubscribersIterator();
        $iterator->setCampaign('with')
            ->setTopic('new_channels')
            ->setValue(true)
            ->setOffset($this->offset);

        $channels = $this->getNewChannels();
        if (!$channels || count($channels) === 0) {
            error_log('No trending channels were found!');
            return;
        }

        foreach ($iterator as $user) {
            $campaign = new WithChannelTips();

            $campaign
                ->setUser($user)
                ->setChannels($channels)
                ->send();
        }
    }

    private function getNewChannels()
    {
        /** @var Repository $repository */
        $repository = Di::_()->get('Trending\Repository');
        $result = $repository->getList(['type' => 'channels', 'limit' => 10, 'offset' => '']);
        if (!$result) {
            return [];
        }

        ksort($result['guids']);

        return Entities::get(['guids' => $result['guids']]);
    }
}