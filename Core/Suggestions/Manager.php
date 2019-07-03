<?php

namespace Minds\Core\Suggestions;

use Minds\Core\EntitiesBuilder;
use Minds\Common\Repository\Response;
use Minds\Core\Di\Di;

class Manager
{
    /** @var $repository */
    private $repository;

    /** @var EntitiesBuilder $entitiesBuilder */
    private $entitiesBuilder;

    /** @var User $user */
    private $user;

    /** @var string $type */
    private $type;

    public function __construct(
        $repository = null,
        $entitiesBuilder = null,
        $subscriptionsManager = null
    ) {
        $this->repository = $repository ?: new Repository();
        $this->entitiesBuilder = $entitiesBuilder ?: new EntitiesBuilder();
        $this->subscriptionsManager = $subscriptionsManager ?: Di::_()->get('Subscriptions\Manager');
    }

    /**
     * Set the user to return data for.
     *
     * @param User $user
     *
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Set the type to return data for.
     *
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Return a list of users.
     *
     * @param array $opts
     *
     * @return Response
     */
    public function getList($opts = [])
    {
        $opts = array_merge([
            'limit' => 12,
            'paging-token' => '',
        ], $opts);

        $opts['user_guid'] = $this->user->getGuid();

        $response = $this->repository->getList($opts);

        if (!count($response)) {
            $response = $this->getFallbackSuggested($opts);
        }

        // Hydrate the entities
        // TODO: make this a bulk request vs sequential
        foreach ($response as $suggestion) {
            $entity = $suggestion->getEntity() ?: $this->entitiesBuilder->single($suggestion->getEntityGuid());
            $suggestion->setEntity($entity);
        }

        return $response;
    }

    private function getFallbackSuggested($opts = [])
    {
        $this->subscriptionsManager->setSubscriber($this->user);
        if ($this->subscriptionsManager->getSubscriptionsCount() > 1) {
            return new Response();
        }

        $opts = array_merge([
            'user_guid' => $this->user->getGuid(),
            'type' => 'user',
        ], $opts);

        $response = new Response();

        $guids = [
            626772382194872329,
            100000000000065670,
            100000000000081444,
            732703596054847489,
            884147802853089287,
            100000000000000341,
            823662468030013460,
            942538426693984265,
            607668752611287060,
            602551056588615697,
        ];

        foreach ($guids as $i => $guid) {
            if ($i >= $opts['limit']) {
                continue;
            }
            $suggestion = new Suggestion();
            $suggestion->setEntityGuid($guid);
            $response[] = $suggestion;
        }

        return $response;
    }
}
