<?php

namespace Spec\Minds\Core\Email\CampaignLogs;


use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Email\CampaignLogs\Repository;
use Minds\Core\Email\CampaignLogs\CampaignLog;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Spec\Minds\Mocks\Cassandra\Rows;
use Cassandra\Varint;

class RepositorySpec extends ObjectBehavior
{
    protected $db;

    public function let(Client $db)
    {
        $this->db = $db;
        $this->beConstructedWith($db);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    public function it_should_write_a_log ()
    {
        $campaignLog = (new CampaignLog())
            ->setReceiverGuid(123)
            ->setTimeSent(0)
            ->setEmailCampaignId('test');

        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] === 'INSERT INTO email_campaign_logs (receiver_guid, time_sent, email_campaign_id) VALUES (?, ?, ?)';
        }))
        ->shouldBeCalled()
        ->willReturn(true);

        $this->add($campaignLog)->shouldReturn(true);
    }

    public function it_should_get_a_list_of_logs()
    {
        $opts = [
           'receiver_guid' => 123,
           'limit' => 10,
           'offset' => '',
       ];
        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();

            return $built['string'] === 'SELECT * FROM email_campaign_logs WHERE receiver_guid = ?';
        }))
        ->shouldBeCalled()
        ->willReturn(new Rows([
                [
                    'receiver_guid' => new Varint(123),
                    'time_sent' => 1,
                    'email_campaign_id' => 'test' 
                ],
                [
                    'receiver_guid' => new Varint(123),
                    'time_sent' => 1,
                    'email_campaign_id' => 'test2' 
                ],
            ], ''));

        $this->getList($opts)->shouldBeArray();
    }
}
