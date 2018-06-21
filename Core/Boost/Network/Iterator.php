<?php

namespace Minds\Core\Boost\Network;

use Minds\Core;
use Minds\Core\Data;
use MongoDB\BSON\ObjectID;
use Minds\Entities\Boost;

class Iterator implements \Iterator
{
    protected $mongo;

    protected $rating = 1;
    protected $quality = 0;
    protected $offset = null;
    protected $limit = 1;
    protected $type = 'newsfeed'; // newsfeed, content
    protected $priority = false;
    protected $categories = null;
    protected $increment = false;

    protected $tries = 0;

    public $list = null;

    const MONGO_LIMIT = 50;

    public function __construct(Data\Interfaces\ClientInterface $mongo = null)
    {
        $this->mongo = $mongo ?: Data\Client::build('MongoDB');
    }

    public function setRating($rating)
    {
        $this->rating = (int) $rating;
        return $this;
    }

    public function setQuality($quality)
    {
        $this->quality = (int) $quality;
        return $this;
    }

    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function setLimit($limit)
    {
        $this->limit = (int) $limit;
        return $this;
    }

    public function setType($type)
    {
        if ($type === 'newsfeed' || $type === 'content') {
            $this->type = $type;
        }
        return $this;
    }

    public function setPriority($priority)
    {
        $this->priority = (bool) $priority;
        return $this;
    }

    public function setCategories($categories)
    {
        $this->categories = $categories;
        return $this;
    }

    public function setIncrement($increment)
    {
        $this->increment = (bool) $increment;
        return $this;
    }

    public function getList()
    {
        $match = [
            'type' => $this->type,
            'state' => 'approved',
            'rating' => [
                //'$exists' => true,
                '$lte' => $this->rating ? $this->rating : (int) Core\Session::getLoggedinUser()->getBoostRating()
            ],
            'quality' => [
                '$gte' => $this->quality
            ]
        ];

        $sort = ['_id' => 1];

        if ($this->offset) {
            $match = array_merge([
                '_id' => [
                    '$gt' => new ObjectId($this->offset),
                ]
            ], $match);
        }

        if ($this->priority) {
            $sort = ['priority' => -1, '_id' => 1];
        }

        // TODO: Settle experimental feature
        // Enable with $CONFIG->set('allowExperimentalCategories', true); in engine/settings.php
        $allowExperimentalCategories = Core\Di\Di::_()->get('Config')->get('allowExperimentalCategories');

        //if (!$this->categories || !$allowExperimentalCategories /* TODO: Settle experimental feature */) {
            $boosts = $this->mongo->find("boost", $match, [
                'limit' => self::MONGO_LIMIT,
                'sort' => $sort,
            ]);
        //} else {
        //    $boosts = $this->getBoostsByCategories($match, $sort);
        //}

        if (!$boosts) {
            return null;
        }

        /** @var Expire $expire */
        $expire = Core\Di\Di::_()->get('Boost\Network\Expire');
        $return = [];
        foreach ($boosts as $data) {
            if (count($return) >= $this->limit) {
                break;
            }

            if (isset($data['_document'])) {
                $data = $data['_document'];
            }

            $impressions = $data['impressions'];

            $boost = $this->getBoostEntity($data['guid']);
            //if(!$boost)
            //var_dump(print_r($data['guid'], true)); die();
            //var_dump(print_r($boost, true)); die();
            $count = 0;

            if ($this->increment) {
                /** @var Metrics $metrics */
                $metrics = Core\Di\Di::_()->get('Boost\Network\Metrics');

                $count = $metrics->incrementViews($boost);
            }


            if ($count > $impressions) {
                $expire->setBoost($boost);
                $expire->expire();
                continue; //max count met
            }

            /*if ($legacy_boost) {
                $return[] = $entity;
            } else {
                $return[$data['guid']] = $boost->getEntity();
            }*/
            $return[$data['guid']] = $boost->getEntity();
            $this->offset = (string) $data['_id'];
        }

        if (empty($return) && $this->tries++ <= 1) {
            return null;
            //$this->offset = "";
            //return $this->getList();
        }

        $return = $this->patchThumbs($return);
        $return = $this->filterBlocked($return);

        $this->list = $return;
        return $return;
    }

    private function getBoostsByCategories($match, $sort)
    {
        $pipeline_match = array_merge([
            'categories' => [
                '$exists' => true
            ]
        ], $match);

        $pipeline_sort = array_merge([
            'score' => -1
        ], $sort);

        $boosts = $this->mongo->aggregate('boost', [
            ['$match' => $pipeline_match],
            [
                '$project' => [
                    '_document' => '$$ROOT',
                    'score' => [
                        '$let' => [
                            'vars' => [
                                'matchSize' => [
                                    '$size' => [
                                        '$setIntersection' => [
                                            '$categories',
                                            $this->categories
                                        ] // $setIntersection
                                    ] // $size
                                ] // matchSize
                            ], // vars
                            'in' => [
                                '$add' => [
                                    '$$matchSize',
                                    [
                                        '$cond' => [
                                            [
                                                '$eq' => [
                                                    '$$matchSize',
                                                    [
                                                        '$size' => '$categories'
                                                    ]
                                                ]
                                            ],
                                            '$$matchSize',
                                            0
                                        ] // $cond
                                    ]
                                ] // $add
                            ] // in
                        ] // $let
                    ] // score
                ]
            ], // $project
            ['$sort' => $pipeline_sort],
            ['$limit' => self::MONGO_LIMIT]
        ]);
        return $boosts;
    }

    /**
     * Gets a single boost entity
     * @param  mixed $guid
     * @return object
     */
    private function getBoostEntity($guid)
    {
        /** @var Core\Boost\Repository $repository */
        $repository = Core\Di\Di::_()->get('Boost\Repository');
        return $repository->getEntity($this->type, $guid);
    }

    /**
     * Polyfills boost thumbs
     * @param  string[] $boosts
     * @return string[]
     */
    private function patchThumbs($boosts)
    {
        $keys = [];
        /** @var Boost\Network $boost */
        foreach ($boosts as $boost) {
            $keys[] = "thumbs:up:entity:$boost->guid";
        }
        $db = new Data\Call('entities_by_time');
        $thumbs = $db->getRows($keys, [
            'offset' => Core\Session::getLoggedInUserGuid(),
            'limit' => 1,
        ]);
        foreach ($boosts as $k => $boost) {
            $key = "thumbs:up:entity:$boost->guid";
            if (isset($thumbs[$key])) {
                $boosts[$k]->{'thumbs:up:user_guids'} = array_keys($thumbs[$key]);
            }
        }
        return $boosts;
    }

    private function filterBlocked($boosts)
    {
        //owner_guids
        $owner_guids = [];
        foreach ($boosts as $boost) {
            $owner_guids[] = $boost->owner_guid;
        }
        $blocked = array_flip(Core\Security\ACL\Block::_()->isBlocked($owner_guids,
            Core\Session::getLoggedInUserGuid()));

        foreach ($boosts as $i => $boost) {
            if (isset($blocked[$boost->owner_guid])) {
                unset($boosts[$i]);
            }
        }

        return $boosts;
    }

    public function current()
    {
        return current($this->list);
    }

    public function next()
    {
        next($this->list);
    }

    public function key()
    {
        return key($this->list);
    }

    public function valid()
    {
        if (!$this->list) {
            return false;
        }
        return key($this->list) !== null;
    }

    public function rewind()
    {
        if ($this->list) {
            reset($this->list);
        }
        $this->getList();
    }


}
