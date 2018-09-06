<?php
namespace Minds\Core\Trending;

use Cassandra;
use Cassandra\Varint;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;
use Minds\Core\EntitiesBuilder;

class Manager
{

    private $repository;
    private $validator;

    private $entities = [];
    private $from;
    private $to;

    public function __construct(
        $repository = null,
        $validator = null,
        $maps = null,
        $entitiesBuilder = null
    )
    {
        $this->repository = $repository ?: Di::_()->get('Trending\Repository');
        $this->validator = $validator ?: new EntityValidator;
        $this->maps = $maps ?: Maps::$maps;
        $this->entitiesBuilder = $entitiesBuilder ?: new EntitiesBuilder;

        $this->from = strtotime('-24 hours') * 1000;
        $this->to = time() * 1000;
    }

    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    public function run()
    {
        \Minds\Core\Security\ACL::$ignore = true;
        $ratings = [1, 2];
        //$ratings = [ 2 ];
        $scores = [];
        foreach ($ratings as $rating) {
            foreach ($this->maps as $key => $map) {
                if (!isset($scores[$key])) {
                    $scores[$key] = [];
                }
                foreach ($map['aggregates'] as $aggregate) {
                    $class = is_string($aggregate) ? new $aggregate : $aggregate;
                    $class->setLimit(500);
                    $class->setType($map['type']);
                    $class->setSubtype($map['subtype']);
                    $class->setFrom($this->from);
                    $class->setTo($this->to);

                    foreach ($class->get() as $guid => $score) {
                        //collect the entity
                        $entity = $this->entitiesBuilder->single($guid);

                        //validate this entity is ok
                        if (!$this->validator->isValid($entity, $rating)) {
                            echo "\n[$rating] $key: $guid ($score) invalid";
                            continue;
                        }

                        //is this an activity entity?
                        //if so, let add it the guids for this key
                        if ($entity->custom_type == 'batch' && $entity->entity_guid) {
                            if (!isset($scores['images'][$entity->entity_guid])) {
                                $scores['images'][$entity->entity_guid] = 0;
                            }
                            $scores['images'][$entity->entity_guid] += $score;
                        } elseif ($entity->custom_type == 'video' && $entity->entity_guid) {
                            if (!isset($scores['videos'][$entity->entity_guid])) {
                                $scores['videos'][$entity->entity_guid] = 0;
                            }
                            $guids['videos'][$entity->entity_guid] += $score;
                        } elseif(strpos($entity->perma_url, 'blog/view/') !== FALSE &&  $entity->entity_guid) {
                            if (!isset($scores['blogs'][$entity->entity_guid])) {
                                $scores['blogs'][$entity->entity_guid] = 0;
                            }
                            $guids['blogs'][$entity->entity_guid] += $score;
                        }

                        if (!isset($scores[$key][$guid])) {
                            $scores[$key][$guid] = 0;
                        }
                        $scores[$key][$guid] += $score;
                    }
                }

                arsort($scores[$key]);
                $guids = [];
                foreach($scores[$key] as $guid => $score) {
                    $guids[] = $guid;
                    echo "\n[$rating] $key: $guid ($score)";
                }

                $this->repository->add($key, $guids, $rating);
            }
        }
    }

}
