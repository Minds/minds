<?php

namespace Minds\Core\Onboarding;

use Minds\Core\Config;
use Minds\Core\Di\Di;
use Minds\Entities\User;

class Manager
{
    /** @var array */
    const CREATOR_FREQUENCIES = [
        'rarely',
        'sometimes',
        'frequently',
    ];

    /** @var Delegates\OnboardingDelegate[] */
    protected $items;

    /** @var Config */
    protected $config;

    /** @var User */
    protected $user;

    /**
     * Manager constructor.
     *
     * @param null $items
     */
    public function __construct($items = null, $config = null)
    {
        $this->items = $items ?: [
            // 'creator_frequency' => new Delegates\CreatorFrequencyDelegate(),
            'suggested_hashtags' => new Delegates\SuggestedHashtagsDelegate(),
            'suggested_channels' => new Delegates\SuggestedChannelsDelegate(),
            // 'suggested_groups' => new Delegates\SuggestedGroupsDelegate(),
            'avatar' => new Delegates\AvatarDelegate(),
            'display_name' => new Delegates\DisplayNameDelegate(),
            'briefdescription' => new Delegates\BriefdescriptionDelegate(),
            'tokens_verification' => new Delegates\TokensVerificationDelegate(),
        ];
        $this->config = $config ?: Di::_()->get('Config');
    }

    /**
     * @param User $user
     *
     * @return Manager
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return bool
     *
     * @throws \Exception
     */
    public function wasOnboardingShown()
    {
        if (!$this->user) {
            throw new \Exception('User not set');
        }

        $timestamp = $this->config->get('onboarding_modal_timestamp') ?: 0;

        return $this->user->getTimeCreated() <= $timestamp || $this->user->wasOnboardingShown();
    }

    /**
     * @param bool $onboardingShown
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function setOnboardingShown($onboardingShown)
    {
        if (!$this->user) {
            throw new \Exception('User not set');
        }

        $saved = $this->user
            ->setOnboardingShown($onboardingShown)
            ->save();

        return (bool) $saved;
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function getCreatorFrequency()
    {
        if (!$this->user) {
            throw new \Exception('User not set');
        }

        return $this->user->getCreatorFrequency();
    }

    /**
     * @param string $creatorFrequency
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function setCreatorFrequency($creatorFrequency)
    {
        if (!$this->user) {
            throw new \Exception('User not set');
        }

        if (!in_array($creatorFrequency, static::CREATOR_FREQUENCIES)) {
            throw new \Exception('Invalid creator frequency');
        }

        $saved = $this->user
            ->setCreatorFrequency($creatorFrequency)
            ->save();

        return (bool) $saved;
    }

    /**
     * @return string[]
     */
    public function getAllItems()
    {
        return array_keys($this->items);
    }

    /**
     * @return string[]
     *
     * @throws \Exception
     */
    public function getCompletedItems()
    {
        if (!$this->user) {
            throw new \Exception('User not set');
        }

        $completedItems = [];

        foreach ($this->items as $item => $delegate) {
            /** @var Delegates\OnboardingDelegate $delegate */
            if ($delegate->isCompleted($this->user)) {
                $completedItems[] = $item;
            }
        }

        return $completedItems;
    }

    /**
     * Compares a user's list of completed items against the number of registered onboarding steps.
     *
     * @return bool
     */
    public function isComplete()
    {
        return count($this->getAllItems()) === count($this->getCompletedItems());
    }
}
