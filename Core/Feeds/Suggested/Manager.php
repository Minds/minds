<?php

namespace Minds\Core\Feeds\Suggested;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities\Entity;
use Minds\Core\EntitiesBuilder;

class Manager
{
    /** @var Repository */
    protected $feedsRepository;
    /** @var Core\EntitiesBuilder */
    protected $entitiesBuilder;
    /** @var \Minds\Core\Hashtags\Entity\Repository */
    private $entityHashtagsRepository;
    /** @var array */
    private $maps;
    /** @var Core\Trending\EntityValidator */
    private $validator;

    private $from;
    private $to;


    public function __construct(
        $repo = null,
        $entityHashtagsRepository = null,
        $validator = null,
        $maps = null,
        $entitiesBuilder = null
    ) {
        $this->feedsRepository = $repo ?: Di::_()->get('Feeds\Suggested\Repository');
        $this->entityHashtagsRepository = $entityHashtagsRepository ?: Di::_()->get('Hashtags\Entity\Repository');
        $this->validator = $validator ?: new Core\Trending\EntityValidator();
        $this->maps = $maps ?: Core\Trending\Maps::$maps;
        $this->entitiesBuilder = $entitiesBuilder ?: new EntitiesBuilder;

        $this->from = strtotime('-12 hours') * 1000;
        $this->to = time() * 1000;
    }

    /**
     * @param array $opts
     * @return Entity[]
     * @throws \Exception
     */
    public function getFeed(array $opts = [])
    {
        $opts = array_merge([
            'user_guid' => null,
            'offset' => 0,
            'limit' => 12,
            'rating' => 1,
            'type' => null,
            'all' => false,
        ], $opts);

        $guids = [];
        foreach ($this->feedsRepository->getFeed($opts) as $item) {
            $guids[] = $item['guid'];
        }

        $entities = [];
        if (count($guids) > 0) {
            $entities = $this->entitiesBuilder->get(['guids' => $guids]);
        }

        return $entities;
    }

    public function run(string $type)
    {
        //\Minds\Core\Security\ACL::$ignore = true;
        $scores = [];

        $maps = null;
        switch ($type) {
            case 'all':
                $maps = $this->maps;
                break;
            case 'channels':
                $maps = ['user' => $this->maps['channels']];
                break;
            case 'newsfeed':
                $maps = ['newsfeed' => $this->maps['newsfeed']];
                break;
            case 'images':
                $maps = ['image' => $this->maps['images']];
                break;
            case 'videos':
                $maps = ['video' => $this->maps['videos']];
                break;
            case 'groups':
                $maps = ['group' => $this->maps['groups']];
                break;
            case 'blogs':
                $maps = ['blog' => $this->maps['blogs']];
                break;
            case 'default':
                throw new \Exception("Invalid type. Valid values are: 'newsfeed', 'images', 'videos', 'groups' and 'blogs'");
                break;
        }

        foreach ($maps as $key => $map) {
            if (!isset($scores[$key])) {
                $scores[$key] = [];
            }
            $ratings = [];
            foreach ($map['aggregates'] as $aggregate) {
                $class = is_string($aggregate) ? new $aggregate : $aggregate;
                $class->setLimit(10000);
                $class->setType($map['type']);
                $class->setSubtype($map['subtype']);
                $class->setFrom($this->from);
                $class->setTo($this->to);

                foreach ($class->get() as $guid => $score) {
                    echo "\n$guid ($score)";
                    //collect the entity
                    $entity = $this->entitiesBuilder->single($guid);

                    if (!$entity->guid) {
                        continue;
                    }

                    $tags = $entity->getTags();

                    if ($entity->container_guid != 0 && $entity->container_guid != $entity->owner_guid && $key == 'newsfeed') {
                        echo " skipping because group post";
                        continue; // skip groups
                    }

                    $this->saveTags($entity->guid, $tags);
                                        
                    $ratings[$entity->guid] = $entity->getRating();

                    //validate this entity is ok
                    if (!$this->validator->isValid($entity)) {
                        echo "\n[$entity->getRating()] $key: $guid ($score) invalid";
                        continue;
                    }
                    
                    //is this an activity entity?
                    //if so, let add it the guids for this key
                    if ($entity->custom_type == 'batch' && $entity->entity_guid) {
                        if (!isset($scores['image'][$entity->entity_guid])) {
                            $scores['image'][$entity->entity_guid] = 0;
                        }
                        $scores['image'][$entity->entity_guid] += $score;
                        $ratings[$entity->entity_guid] = $ratings[$guid];
                        $this->saveTags($entity->entity_guid, $tags);
                    } elseif ($entity->custom_type == 'video' && $entity->entity_guid) {
                        if (!isset($scores['video'][$entity->entity_guid])) {
                            $scores['video'][$entity->entity_guid] = 0;
                        }
                        $scores['video'][$entity->entity_guid] += $score;
                        $ratings[$entity->entity_guid] = $ratings[$guid];
                        $this->saveTags($entity->entity_guid, $tags);
                    } elseif (strpos($entity->perma_url, '/blog') !== false && $entity->entity_guid) {
                        if (!isset($scores['blog'][$entity->entity_guid])) {
                            $scores['blog'][$entity->entity_guid] = 0;
                        }
                        $scores['blog'][$entity->entity_guid] += $score;
                        $ratings[$entity->entity_guid] = $ratings[$guid];
                        $this->saveTags($entity->entity_guid, $tags);
                        echo "\n\tblog here $entity->entity_guid";
                    }

                    if (!isset($scores[$key][$guid])) {
                        $scores[$key][$guid] = 0;
                    }
                    $scores[$key][$guid] += $score;
                }
            }

            //arsort($scores[$key]);

            $sync = time();
            foreach ($scores as $_key => $_scores) {
                foreach ($_scores as $guid => $score) {
                    if (! (int) $score || !$guid) {
                        continue;
                    }
                    if (!isset($ratings[$guid])) {
                        $ratings[$guid] = 2;
                    }
                    $this->feedsRepository->add([
                        'entity_guid' => $guid,
                        'score' => (int) $score,
                        'type' => $_key,
                        'rating' => $ratings[$guid],
                        'lastSynced' => $sync,
                    ]);

                    echo "\n[{$ratings[$guid]}] $_key: $guid ($score)";
                }
            }
        }
        //\Minds\Core\Security\ACL::$ignore = false;
    }

    private function saveTags($guid, $tags)
    {
        if (!$tags) {
            echo " no tags";
            return;
        }
        $hashtagEntities = [];
        foreach ($tags as $tag) {
            if (strpos($tag, '#', 0) === 0) {
                $tag = substr($tag, 1);
            }
            echo "\n\t#$tag";
            $hashtagEntity = new Core\Hashtags\HashtagEntity();
            $hashtagEntity->setGuid($guid);
            $hashtagEntity->setHashtag(strtolower($tag));
            $hashtagEntities[] = $hashtagEntity;
        }


        //if ($key == 'newsfeed' && count($hashtagEntities) >= 5) {
        //    continue;
        //}

        foreach ($hashtagEntities as $hashtagEntity) {
            $this->entityHashtagsRepository->add([$hashtagEntity]);
        }

        echo "\nSaved tags to repo";
    }
}
