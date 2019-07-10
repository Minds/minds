<?php
/**
 * Email Campaign Log Repository.
 */

namespace Minds\Core\Email\CampaignLogs;

use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Di\Di;
use Cassandra\Varint;
use Cassandra\Timestamp;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Email\CampaignLogs\CampaignLog;

class Repository
{
    /**
     * @var Client
     */
    protected $db;

    /**
     * Repository constructor.
     *
     * @param null $db
     */
    public function __construct($db = null)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * Gets the list of email states, they are clustered on time_sent, so they will be in descending order.
     *
     * @param array $options
     *
     * receiver_guid the user guid
     * limit 10
     *
     * @return CampaignLog[]
     */
    public function getList(array $options = [])
    {
        $options = array_merge([
            'limit' => 10,
            'offset' => '',
            'receiver_guid' => null,
        ], $options);

        $template = 'SELECT * FROM email_campaign_logs';
        $where = "";
        $values = [];

        if (isset($options['receiver_guid'])) {
            $where = 'receiver_guid = ?';
            $values[] = new Varint($options['receiver_guid']);
        }

        if ($where) {
            $template .= ' WHERE ' . $where;
        }

        $query = new Custom();
        $query->query($template, $values);

        $query->setOpts([
            'page_size' => (int) $options['limit'],
            'paging_state_token' => $options['offset'],
        ]);

        try {
            $result = $this->db->request($query);
        } catch (\Exception $e) {
            error_log($e);
        }

        $campaignLog = [];
        $token = '';
        if ($result) {
            foreach ($result as $row) {
                $campaignLog = (new CampaignLog())
                    ->setReceiverGuid($row['receiver_guid']->value())
                    ->setTimeSent($row['time_sent'])
                    ->setEmailCampaignId($row['email_campaign_id']);
                $campaignLogs[] = $campaignLog;
            }
            $token = base64_encode($result->pagingStateToken());
        }

        return [
            'data' => $campaignLogs,
            'next' => $token,
        ];
    }

    /**
     * Inserts an email campaign log into cassandra.
     *
     * @param CampaignLog $campaignLog
     *
     * @return bool the write results
     *
     * @throws \Exception
     */
    public function add(CampaignLog $campaignLog)
    {
        $template = 'INSERT INTO email_campaign_logs (receiver_guid, time_sent, email_campaign_id) VALUES (?, ?, ?)';
        $values = [
            new Varint($campaignLog->getReceiverGuid()),
            new Timestamp($campaignLog->getTimeSent()),
            (string) $campaignLog->getEmailCampaignId(),
        ];
        $query = new Custom();
        $query->query($template, $values);

        return $this->db->request($query);
    }
}
