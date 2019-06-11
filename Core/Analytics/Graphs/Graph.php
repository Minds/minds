<?php
/**
 * Graph export
 */
namespace Minds\Core\Analytics\Graphs;

use Minds\Traits\MagicAttributes;

/**
 * Class Graph
 * @package Minds\Core\Analytics\Graph
 * @method string getKey()
 * @method Graph setKey(string $value)
 * @method int getLastSynced()
 * @method Graph setLastSynced(int $value)
 * @method mixed getData()
 * @method Graph setData(mixed $value)
 */
class Graph
{
    use MagicAttributes;

    /** @var string $key */
    protected $key;

    /** @var int $lastSynced */
    protected $lastSynced;

    /** @var string $data */
    protected $data;

    /**
     * Urn
     * @return string
     */
    public function getUrn()
    {
        return "urn:graph:$this->report";
    }
}
