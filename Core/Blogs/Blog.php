<?php

/**
 * Minds Blog Entity
 *
 * @author emi
 */

namespace Minds\Core\Blogs;

use Minds\Core\Config;
use Minds\Core\Di\Di;
use Minds\Core\Events\EventsDispatcher;
use Minds\Core\Guid;
use Minds\Core\Security\ACL;
use Minds\Core\Security\XSS;
use Minds\Entities\RepositoryEntity;
use Minds\Entities\User;
use Minds\Helpers\Flags;
use Minds\Helpers\Text;
use Minds\Traits\MagicAttributes;

/**
 * Blog Entity
 * @package Minds\Core\Blogs
 * @method string getType()
 * @method string getSubtype()
 * @method Blog setGuid(int $value)
 * @method Blog setOwnerGuid(int $value)
 * @method int getOwnerGuid()
 * @method Blog setContainerGuid(int $value)
 * @method int getContainerGuid()
 * @method Blog setAccessId(int $value)
 * @method int getAccessId()
 * @method Blog setTitle(string $value)
 * @method string getTitle()
 * @method Blog setBody(string $value)
 * @method string getBody()
 * @method Blog setExcerpt(string $value)
 * @method Blog setPermaUrl(string $value)
 * @method string getPermaUrl()
 * @method Blog setHasHeaderBg(bool $value)
 * @method bool hasHeaderBg()
 * @method Blog setHeaderTop(int $value)
 * @method int getHeaderTop()
 * @method Blog setTimeCreated(int $value)
 * @method int getTimeCreated()
 * @method Blog setTimeUpdated(int $value)
 * @method int getTimeUpdated()
 * @method Blog setLastUpdated(int $value)
 * @method int getLastUpdated()
 * @method Blog setStatus(string $value)
 * @method string getStatus()
 * @method Blog setPublished(bool $value)
 * @method bool isPublished()
 * @method Blog setMonetized(bool $value)
 * @method bool isMonetized()
 * @method Blog setLicense(string $value)
 * @method string getLicense()
 * @method Blog setTimePublished(int $value)
 * @method int getTimePublished()
 * @method Blog setCategories(array $value)
 * @method array getCategories()
 * @method Blog setRating(int $value)
 * @method int getRating()
 * @method Blog setDraftAccessId(int $value)
 * @method int getDraftAccessId()
 * @method Blog setLastSave(int $value)
 * @method int getLastSave()
 * @method Blog setWireThreshold(mixed $value)
 * @method mixed getWireThreshold()
 * @method Blog setPaywall(mixed $value)
 * @method mixed isPaywall()
 * @method Blog setMature(bool $value)
 * @method bool isMature()
 * @method Blog setSpam(bool $value)
 * @method bool isSpam()
 * @method Blog setDeleted(bool $value)
 * @method bool isDeleted()
 * @method Blog setBoostRejectionReason(int $value)
 * @method int getBoostRejectionReason()
 * @method Blog setVotesUp(array $value)
 * @method array getVotesUp()
 * @method Blog setVotesDown(array $value)
 * @method array getVotesDown()
 * @method Blog setInteractions(int $value)
 * @method int getInteractions()
 * @method Blog setEphemeral(bool $value)
 * @method bool isEphemeral()
 * @method Blog setHidden(bool $value)
 * @method bool isHidden()
 */
class Blog extends RepositoryEntity
{
    use MagicAttributes;

    /** @var string */
    protected $type = 'object';

    /** @var string */
    protected $subtype = 'blog';

    /** @var int */
    protected $guid;

    /** @var int */
    protected $ownerGuid;

    /** @var int */
    protected $containerGuid;

    /** @var int */
    protected $accessId = 2;

    /** @var string */
    protected $title = '';

    /** @var string */
    protected $body = '';

    /** @var string */
    protected $excerpt = '';

    /** @var string */
    protected $slug = '';

    /** @var bool */
    protected $hasHeaderBg;

    /** @var int */
    protected $headerTop;

    /** @var int */
    protected $timeCreated;

    /** @var int */
    protected $timeUpdated;

    /** @var int */
    protected $lastUpdated;

    /** @var string */
    protected $status;

    /** @var bool */
    protected $published; // Should be NULL by default (legacy)

    /** @var bool */
    protected $monetized = false;

    /** @var string */
    protected $license = '';

    /** @var int */
    protected $timePublished;

    /** @var array */
    protected $categories = [];

    /** @var array */
    protected $customMeta = [];

    /** @var int */
    protected $rating = 2;

    /** @var int */
    protected $draftAccessId = 0;

    /** @var int */
    protected $lastSave;

    /** @var mixed */
    protected $wireThreshold;

    /** @var mixed */
    protected $paywall;

    /** @var bool */
    protected $mature = false;

    /** @var bool */
    protected $spam = false;

    /** @var bool */
    protected $deleted = false;

    /** @var int */
    protected $boostRejectionReason = -1;

    /** @var array */
    protected $ownerObj;

    /** @var array */
    protected $votesUp;

    /** @var array */
    protected $votesDown;

    /** @var bool */
    protected $hidden = true;

    /** @var int */
    protected $interactions;

    /** @var bool */
    protected $ephemeral = true;

    /** @var EventsDispatcher */
    protected $_eventsDispatcher;

    /** @var Config */
    protected $_config;

    /** @var Header */
    protected $_header;

    /** @var ACL */
    protected $_acl;

    /**
     * Blog constructor.
     * @param null $eventsDispatcher
     * @param null $config
     * @param null $header
     * @param null $acl
     */
    public function __construct(
        $eventsDispatcher = null,
        $config = null,
        $header = null,
        $acl = null
    )
    {
        $this->_eventsDispatcher = $eventsDispatcher ?: Di::_()->get('EventsDispatcher');
        $this->_config = $config ?: Di::_()->get('Config');
        $this->_header = $header ?: new Header();
        $this->_acl = $acl ?: ACL::_();
    }

    /**
     * @return int
     */
    public function getGuid()
    {
        if (!$this->guid) {
            $this->setGuid(Guid::build());
        }

        return $this->guid;
    }

    /**
     * @param array|string $value
     * @return $this
     */
    public function setOwnerObj($value)
    {
        if (is_string($value) && $value) {
            $value = json_decode($value, true);
        } else if ($value instanceof User) {
            $value = $value->export();
        }

        $this->ownerObj = $value;
        $this->markAsDirty('ownerObj');

        if ($value && !$this->ownerGuid) {
            $this->ownerGuid = $value['guid'];
            $this->markAsDirty('ownerGuid');
        }

        return $this;
    }

    /**
     * Gets (hydrates if necessary) the owner object
     * @return array
     * @throws \Exception
     */
    public function getOwnerObj()
    {
        if (!$this->ownerObj && $this->ownerGuid) {
            $user = new User($this->ownerGuid);
            $this->setOwnerObj($user->export());
        }

        return $this->ownerObj;
    }

    /**
     * Gets the blog URL
     * @param bool $routeOnly
     * @return string
     * @throws \Exception
     */
    public function getUrl($routeOnly = false)
    {
        $prefix = $routeOnly ? '' : $this->_config->get('site_url');
        $guid = $this->getGuid();
        $slug = $this->getSlug();
        $owner = $this->getOwnerObj();

        if ($slug && $owner && $owner['username']) {
            return "{$prefix}{$owner['username']}/blog/{$slug}-{$guid}";
        }

        return "{$prefix}blog/view/{$guid}";
    }

    /**
     * Gets the blog image URL
     * @param null $size
     * @return string
     */
    public function getIconUrl($size = null)
    {
        return $this->_header->resolve($this, $size);
    }

    /**
     * Gets the slug
     * @return string
     */
    public function getSlug() {
        return $this->slug ?: '';
    }

    /**
     * Sets the new slug
     * @param $text
     * @return $this
     */
    public function setSlug($text) {
        $oldSlug = $this->getSlug();

        $this->slug = Text::slug($text, 60);

        if ($this->slug !== $oldSlug) {
            $this->markAsDirty('slug');
        }

        return $this;
    }

    /**
     * Sets the custom meta array
     * @param array $customMeta
     * @return $this
     */
    public function setCustomMeta(array $customMeta = [])
    {
        $this->customMeta = filter_var_array($customMeta, [
            'title' => FILTER_SANITIZE_SPECIAL_CHARS,
            'description' => FILTER_SANITIZE_SPECIAL_CHARS,
            'author' => FILTER_SANITIZE_SPECIAL_CHARS
        ]);

        return $this;
    }

    /**
     * Gets the custom meta array
     * @return array
     */
    public function getCustomMeta()
    {
        $customMeta = $this->customMeta ?: [];

        if (is_string($this->customMeta)) {
            $customMeta = json_decode($this->customMeta, true);
        }

        return array_merge([
            'title' => '',
            'description' => '',
            'author' => '',
        ], $customMeta);
    }

    /**
     * Gets (or generates) the excerpt
     * @return string
     */
    public function getExcerpt()
    {
        if ($this->excerpt) {
            return strip_tags($this->excerpt);
        }

        $this->setExcerpt(str_replace("&nbsp;","", $this->getBody()));
        return strip_tags($this->excerpt);
    }

    /**
     * Returns if the entity can be edited by the current user
     * @param User|null $user
     * @return bool
     */
    public function canEdit(User $user = null)
    {
        return $this->_acl->write($this, $user);
    }

    /**
     * Defines the exportable members
     * @return array
     */
    public function getExportable()
    {
        return [
            'type',
            'subtype',
            'guid',
            'ownerGuid',
            'containerGuid',
            'accessId',
            'title',
            'body',
            'excerpt',
            'slug',
            'permaUrl',
            'hasHeaderBg',
            'headerTop',
            'timeCreated',
            'timeUpdated',
            'lastUpdated',
            'status',
            'published',
            'monetized',
            'license',
            'timePublished',
            'categories',
            'customMeta',
            'rating',
            'draftAccessId',
            'lastSave',
            'wireThreshold',
            'paywall',
            'mature',
            'spam',
            'deleted',
            'boostRejectionReason',
            'ownerObj',
            function ($export) {
                return $this->_extendExport($export);
            }
        ];
    }

    /**
     * @param array $export
     * @return array
     * @throws \Exception
     */
    protected function _extendExport(array $export)
    {
        $output = [];

        // Sanitize body
        $output['body'] = (new XSS())->clean($export['body']);

        // Legacy
        $output['ownerObj'] = $this->getOwnerObj();
        $output['description'] = $output['body'];
        $output['excerpt'] = $this->getExcerpt();
        $output['category'] = $this->getCategories() ? $this->getCategories()[0] : '';
        $output['header_bg'] = $export['has_header_bg'];

        if (!$this->isEphemeral()) {
            $output['thumbs:up:user_guids'] = $this->getVotesUp() ?: [];
            $output['thumbs:down:user_guids'] = $this->getVotesDown() ?: [];

            // Vote count and Reminds (legacy)
            $output = array_merge($output, (new Legacy\Entity())->exportCounters($this));
        }

        // Type casting (legacy)
        $output['monetized'] = (bool) $export['monetized'];
        $output['paywall'] = (bool) $export['paywall'];
        $output['mature'] = (bool) $export['mature'];
        $output['spam'] = (bool) $export['spam'];
        $output['deleted'] = (bool) $export['deleted'];
        $output['rating'] = (int) $export['rating'];
        $output['boost_rejection_reason'] = (int) $export['boost_rejection_reason'];

        // The curious case of the published flag
        if ($export['published'] !== "" && $export['published'] !== null) {
            $output['published'] = (bool) $export['published'];
        } else {
            $output['published'] = true;
        }

        // Route and thumbnail
        $output['route'] = $this->getUrl(true);
        $output['thumbnail_src'] = $this->getIconUrl();

        // Unset flags if shouldn't disclose
        if (!Flags::shouldDiscloseStatus($this)) {
            unset($output['spam']);
            unset($output['deleted']);
        }

        $output = array_merge(
            $output,
            $this->_eventsDispatcher->trigger('export:extender', 'blog', [ 'entity' => $this ], [])
        );

        return $output;
    }
}
