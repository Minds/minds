<?php
namespace Minds\Core\Email;

use Minds\Traits\MagicAttributes;

class EmailSubscription
{
    use MagicAttributes;

    /** @var string $userGuid */
    protected $userGuid;

    /** @var string campaign */
    protected $campaign;

    /** @var string $topic */
    protected $topic;

    /** @var bool $value  */
    protected $value = false;

    /**
     * EmailSubscription constructor.
     * @param null|array $data
     */
    public function __construct($data = null)
    {
        if ($data && is_array($data)) {
            $this->loadFromArray($data);
        }
    }

    private function loadFromArray($data)
    {
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }

    public function export() {
        $export = [];

        $export['campaign'] = $this->getCampaign();
        $export['topic'] = $this->getTopic();
        $export['user_guid'] = $this->getUserGuid();
        $export['value'] = $this->getValue();
        return $export;
    }

}