<?php
/**
 * SocketDelegate
 *
 * @author edgebal
 */

namespace Minds\Core\Reports\Summons\Delegates;

use Exception;
use Minds\Core\Reports\Summons\Summon;
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
     * @param Summon $summon
     * @throws Exception
     */
    public function onSummon(Summon $summon)
    {
        $this->socketEvents
            ->setUser($summon->getJurorGuid())
            ->emit('moderation_summon', json_encode($summon));
    }
}
