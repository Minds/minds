<?php
namespace Minds\Core\Notification\Polyfills;

use Minds\Core;
use Minds\Core\Di\Di;

class Migration
{
    protected $owner;
    protected $filter = "";

    public function setOwner($guid)
    {
        if (is_object($guid)) {
            $guid = $guid->guid;
        } elseif (is_array($guid)) {
            $guid = $guid['guid'];
        }

        $this->owner = $guid;

        return $this;
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        return $this;
    }

    public function migrate($quantity = 150)
    {
        if (!$this->owner) {
            throw new \Exception('Should call to setOwner() first');
        }

        if (php_sapi_name() === 'cli') {
            echo("Migrating {$this->owner} notifications to CQL table…" . PHP_EOL);
        } else {
            error_log("Migrating {$this->owner} notifications to CQL table…" . PHP_EOL);
        }
        $repository = Di::_()->get('Notification\Repository');
        $repository->setOwner($this->owner);

        $filter = $this->filter ? ":$this->filter" : "";

        $db = new Core\Data\Call('entities_by_time');
        $rows = $db->getRow("notifications:{$this->owner}$filter", [
            'reversed' => true,
            'limit' => $quantity
        ]);

        if (!$rows) {
            return;
        }

        foreach ($rows as $guid => $row) {
            if (is_string($row)) {
                $row = json_decode($row, true);
            }

            $repository->store($row, time() - $row['time_created']);
        }
    }
}
