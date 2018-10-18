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

        $guids = array_map(function ($item) {
            return $item['guid'];
        }, $this->feedsRepository->getFeed($opts));

        $entities = [];
        if (count($guids) > 0) {
            $entities = $this->entitiesBuilder->get(['guids' => $guids]);
        }

        return $entities;
    }

    public function run(string $type)
    {
        \Minds\Core\Security\ACL::$ignore = true;
        $scores = [];

        $maps = null;
        switch ($type) {
            case 'all':
                $maps = $this->maps;
                break;
            case 'newsfeed':
                $maps = ['newsfeed' => $this->maps['newsfeed']];
                break;
            case 'images':
                $maps = ['images' => $this->maps['images']];
                break;
            case 'videos':
                $maps = ['videos' => $this->maps['videos']];
                break;
            case 'groups':
                $maps = ['groups' => $this->maps['groups']];
                break;
            case 'blogs':
                $maps = ['blogs' => $this->maps['blogs']];
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

                    // add hashtags to db
                    $matches = [];
                    preg_match_all("/(#\w+)/", $entity->message, $matches);

                    $tags = $matches[1];

                    if (!$tags) {
                        continue;
                    }

                    $hashtagEntities = [];
                    foreach ($tags as $tag) {
                        $hashtagEntity = new Core\Hashtags\HashtagEntity();
                        $hashtagEntity->setGuid($entity->guid);
                        $hashtagEntity->setHashtag(strtolower(substr($tag, 1)));
                        $hashtagEntities[] = $hashtagEntity;
                    }

                    if (count($hashtagEntities) >= 5) {
                        continue;
                    }

                    foreach ($hashtagEntities as $hashtagEntity) {
                        $this->entityHashtagsRepository->add([$hashtagEntity]);
                    }

                    echo "\nSaved to repo";

                    $ratings[$entity->guid] = $entity->getRating();

                    //validate this entity is ok
                    if (!$this->validator->isValid($entity)) {
                        echo "\n[$entity->getRating()] $key: $guid ($score) invalid";
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
                    } elseif (strpos($entity->perma_url, 'blog/view/') !== false && $entity->entity_guid) {
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

            //arsort($scores[$key]);

            $sync = time();
            foreach ($scores[$key] as $guid => $score) {
                if (!$score) {
                    continue;
                }
                $this->feedsRepository->add([
                    'entity_guid' => $guid,
                    'score' => $score,
                    'type' => $key,
                    'rating' => $ratings[$guid],
                    'lastSynced' => $sync,
                ]);

                echo "\n[{$ratings[$guid]}] $key: $guid ($score)";
            }
        }
        \Minds\Core\Security\ACL::$ignore = false;
    }
}
