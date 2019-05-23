<?php
/**
 * SocketDelegate
 *
 * @author edgebal
 */

namespace Minds\Core\Reports\Summons\Delegates;

use Exception;
use Minds\Core\Reports\Summons\Summons;
use Minds\Core\Sockets\Events as SocketEvents;

class SocketDelegate
{
    /** @var SocketEvents */
    protected $socketEvents;

    /**
     * SocketDelegate constructor.
     * @param SocketEvents $socketEvents
     */
    public function __construct(
        $socketEvents = null
    )
    {
        $this->socketEvents = $socketEvents ?: new SocketEvents();
    }

    /**
     * @param Summons $summons
     * @throws Exception
     */
    public function onSummon(Summons $summons)
    {
        $this->socketEvents
            ->setUser($summons->getJurorGuid())
            ->emit('moderation_summon', json_encode($summons));
    }
}
