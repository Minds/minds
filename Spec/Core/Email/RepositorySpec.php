<?php

namespace Spec\Minds\Core\Email;

use Cassandra\Varint;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Email\EmailSubscription;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Spec\Minds\Mocks\Cassandra\Rows;

class RepositorySpec extends ObjectBehavior
{

    protected $db;

    function let(Client $db)
    {
        $this->db = $db;
        $this->beConstructedWith($db);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Email\Repository');
    }

    function it_should_get_a_list_of_subscriptions()
    {
        $opts = [
            'campaign' => 'when',
            'topic' => 'boost_completed',
            'limit' => 2000,
        ];

        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] === 'SELECT * FROM email_subscriptions WHERE campaign IN ? AND topic IN ?';
        }))
            ->shouldBeCalled()
            ->willReturn(new Rows([
                    [
                        'campaign' => 'when',
                        'topic' => 'boost_completed',
                        'user_guid' => new Varint(123),
                        'value' => true,
                    ],
                    [
                        'campaign' => 'when',
                        'topic' => 'wire_received',
                        'user_guid' => new Varint(456),
                        'value' => true
                    ]
                ], ''));

        $this->getList($opts)->shouldBeArray();
    }

    function it_should_throw_if_calling_add_without_user_guid() 
    {
        $this->shouldThrow(new \Exception('user_guid is required'))->duringAdd(new EmailSubscription());
    }

    function it_should_throw_if_calling_add_without_campaign() 
    {
        $model = new EmailSubscription();
        $model->setUserGuid(123);

        $this->shouldThrow(new \Exception('campaign is required'))->duringAdd($model);
    }

    function it_should_throw_if_calling_add_without_topic() 
    {
        $model = new EmailSubscription();
        $model->setUserGuid(123)
            ->setCampaign('when');

        $this->shouldThrow(new \Exception('topic is required'))->duringAdd($model);
    }

    function it_should_insert_a_new_subscription()
    {
        $model = new EmailSubscription();
        $model->setUserGuid(123)
            ->setCampaign('when')
            ->setTopic('boost_completed')
            ->setValue(true);

        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] === 'INSERT INTO email_subscriptions (topic, campaign, user_guid, value) VALUES (?, ?, ?, ?)';
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->add($model)->shouldReturn(true);
    }

}
