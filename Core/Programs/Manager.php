<?php
namespace Minds\Core\Programs;

use Minds\Core;
use Minds\Entities;
use Minds\Core\Di\Di;

class Manager
{
    protected $timeline;

    protected static $defaultSettings = [
        'ads' => [
            'blogs' => 0,
        ],
    ];

    public function __construct($timeline = null)
    {
        // @todo: migrate to CQLv3 (when PR is merged)
        $this->timeline = $timeline ?: new Core\Data\Call('entities_by_time');
    }

    public function setUser($user)
    {
        if (!is_object($user)) {
            $user = new Entities\User($user);
        }

        if (!$user || !$user->guid) {
            throw new \Exception('Invalid user');
        }

        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function refreshUser()
    {
        if (!$this->user) {
            return;
        }

        $this->setUser(new Entities\User($this->user->guid, false));
    }

    public function apply($program, array $data = [])
    {
        if (!$this->user) {
            throw new \Exception('No user');
        }

        if (!isset($data['message']) || !$data['message']) {
            throw new \Exception('Missing message');
        }

        $optInRequest = new Entities\OptInRequest();

        $optInRequest
            ->setProgram('ads')
            ->setMessage($data['message'])
            ->setTimeCreated(time())
            ->setFrom($this->user);
        
        $optInRequest->save();

        Core\Events\Dispatcher::trigger('notification', 'program', [
            'to'=> [ $this->user->guid ],
            'from' => 100000000000000519,
            'notification_view' => 'program_queued',
            'params' => [ 'guid' => $optInRequest->getGuid(), 'program' => $optInRequest->getProgram() ]
        ]);

        return true;
    }

    public function accept($guid)
    {
        $optInRequest = new Entities\OptInRequest();
        $optInRequest->loadFromGuid($guid);

        $user = new Entities\User($optInRequest->getFrom()['guid']);

        if (!$user || !$user->guid) {
            throw new \Exception('Invalid user');
        }

        $programs = $user->getPrograms();
        $programs[] = $optInRequest->getProgram();
        $programs = array_unique($programs);

        $user->setPrograms($programs);
        $user->save();

        $optInRequest->delete();

        Core\Events\Dispatcher::trigger('notification', 'program', [
            'to'=> [ $optInRequest->getFrom()['guid'] ],
            'from' => 100000000000000519,
            'notification_view' => 'program_accepted',
            'params' => [ 'guid' => $optInRequest->getGuid(), 'program' => $optInRequest->getProgram() ]
        ]);

        return true;
    }

    public function reject($guid)
    {
        $optInRequest = new Entities\OptInRequest();
        $optInRequest->loadFromGuid($guid);

        $optInRequest->delete();

        Core\Events\Dispatcher::trigger('notification', 'program', [
            'to'=> [ $optInRequest->getFrom()['guid'] ],
            'from' => 100000000000000519,
            'notification_view' => 'program_declined',
            'params' => [ 'guid' => $optInRequest->getGuid(), 'program' => $optInRequest->getProgram() ]
        ]);

        return true;
    }

    public function isParticipant($program)
    {
        return in_array($program, $this->user->getPrograms());
    }

    public function isApplicant($program)
    {
        $rowKey = 'opt_in_requests:user:' . $this->user->guid . ':ads';
        $guids = $this->timeline->getRow($rowKey, [
            'limit' => 1
        ]);

        return !!$guids;
    }

    // Program settings

    public function getSettings($program)
    {
        if (!isset(static::$defaultSettings[$program])) {
            throw new \Exception('Program not found');
        }

        $settings = static::$defaultSettings[$program];

        $userRow = $this->user->getMonetizationSettings();
        if (isset($userRow[$program])) {
            $settings = array_merge($settings, $userRow[$program]);
        }

        return $settings;
    }

    public function setSettings($program, array $data = [])
    {
        if (!isset(static::$defaultSettings[$program])) {
            throw new \Exception('Program not found');
        }

        $userRow = $this->user->getMonetizationSettings();
        $settings = $this->getSettings($program);

        foreach ($data as $key => $value) {
            if (!isset(static::$defaultSettings[$program][$key])) {
                continue;
            }

            $settings[$key] = $value;
        }

        $userRow[$program] = $settings;

        $this->user->setMonetizationSettings($userRow);
        $this->user->save();

        return true;
    }
}
