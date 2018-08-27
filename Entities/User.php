<?php
namespace Minds\Entities;

use Minds\Core;
use Minds\Helpers;

/**
 * User Entity
 * @todo Do not inherit from ElggUser
 */
class User extends \ElggUser
{

    public $fullExport = true;
    public $exportCounts = false;

    protected function initializeAttributes() {
        $this->attributes['boost_rating'] = 1;
        $this->attributes['mature'] = 0;
        $this->attributes['mature_content'] = 0;
        $this->attributes['spam'] = 0;
        $this->attributes['deleted'] = 0;
        $this->attributes['social_profiles'] = [];
        $this->attributes['ban_monetization'] = 'no';
        $this->attributes['programs'] = [];
        $this->attributes['monetization_settings'] = [];
        $this->attributes['group_membership'] = [];
        $this->attributes['plus'] = 0; //TODO: REMOVE
        $this->attributes['plus_expires'] = 0;
        $this->attributes['verified'] = 0;
        $this->attributes['founder'] = 0;
        $this->attributes['disabled_boost'] = 0;
        $this->attributes['boost_autorotate'] = 1;
        $this->attributes['categories'] = [];
        $this->attributes['wire_rewards'] = '';
        $this->attributes['pinned_posts'] = [];
        $this->attributes['eth_wallet'] = '';
        $this->attributes['eth_incentive'] = '';
        $this->attributes['phone_number'] = null;
        $this->attributes['phone_number_hash'] = null;
        $this->attributes['icontime'] = time();
		$this->attributes['briefdescription'] = '';
		$this->attributes['rating'] = 1;
		$this->attributes['p2p_media_disabled'] = 0;
		$this->attributes['is_mature'] = 0;
		$this->attributes['mature_lock'] = 0;
		$this->attributes['last_accepted_tos'] = 0;

        parent::initializeAttributes();
    }

    /**
     * Sets the `boost rating` flag
     * @param  int $value
     * @return $this
     */
    public function setBoostRating($value)
    {
        $this->boost_rating = $value;
        return $this;
    }

    /**
     * Gets the `boost rating` flag
     * @return int
     */
    public function getBoostRating()
    {
      return $this->boost_rating;
    }

    /**
     * Gets the `mature` flag
     * @return bool|int
     */
    public function getViewMature()
    {
      return $this->attributes['mature'];
    }

    /**
     * Sets the `mature` flag
     * @param  bool|int $value
     * @return $this
     */
    public function setViewMature($value)
    {
        $this->mature = $value ? 1 : 0;
        return $this;
    }

    /**
     * Sets the `mature_content` flag
     * @param  bool|int $value
     * @return $this
     */
    public function setMatureContent($value)
    {
        $this->mature_content = $value ? 1 : 0;
        return $this;
    }

    /**
     * Gets the `mature_content` flag
     * @return bool|int
     */
    public function getMatureContent()
    {
      return $this->mature_content;
    }

    /**
     * Sets the `spam` flag
     * @param  bool|int $value
     * @return $this
     */
    public function setSpam($value)
    {
        $this->spam = $value ? 1 : 0;
        return $this;
    }

    /**
     * Gets the `spam` flag
     * @return bool|int
     */
    public function getSpam()
    {
        if (is_string($this->spam)) {
            return json_decode($this->spam);
        }

        return $this->spam;
    }

    /**
     * Sets the `deleted` flag
     * @param  bool|int $value
     * @return $this
     */
    public function setDeleted($value)
    {
        $this->deleted = $value ? 1 : 0;
        return $this;
    }

    /**
     * Gets the `deleted` flag
     * @return bool|int
     */
    public function getDeleted()
    {
        if (is_string($this->deleted)) {
            return json_decode($this->deleted);
        }

        return $this->deleted;
    }

    /**
     * Sets the `language` flag
     * @param  string $value
     * @return $this
     */
    public function setLanguage($value)
    {
        $this->language = $value;
        return $this;
    }

    /**
     * Gets the `language` flag
     * @return string
     */
    public function getLanguage()
    {
      return $this->language;
    }

    /**
     * Sets and encrypts a users email address
     * @param  string $email
     * @return $this
     */
    public function setEmail($email)
    {
        global $CONFIG; //@todo use object config instead
        if (base64_decode($email, true)) {
            return $this;
        }
        $this->email = base64_encode(Helpers\OpenSSL::encrypt($email, file_get_contents($CONFIG->encryptionKeys['email']['public'])));
        return $this;
    }

    /**
     * Returns and decrypts an email address
     * @return $this
     */
    public function getEmail()
    {
        global $CONFIG; //@todo use object config instead
        if ($this->email && !base64_decode($this->email, true)) {
            return $this->email;
        }
        return Helpers\OpenSSL::decrypt(base64_decode($this->email), file_get_contents($CONFIG->encryptionKeys['email']['private']));
    }

    /**
     * Sets and encrypts a users phone number
     * @param  string $phone
     * @return $this
     */
    public function setPhoneNumber($phone)
    {
        global $CONFIG; //@todo use object config instead
        $this->phone_number = base64_encode(Helpers\OpenSSL::encrypt($phone, file_get_contents($CONFIG->encryptionKeys['phone-number']['public'])));
        return $this;
    }

    /**
     * Returns and decrypts an phone number
     * @return $this
     */
    public function getPhoneNumber()
    {
        global $CONFIG; //@todo use object config instead
        if ($this->phone_number && !base64_decode($this->phone_number, true)) {
            return $this->phone_number;
        }
        return Helpers\OpenSSL::decrypt(base64_decode($this->phone_number), file_get_contents($CONFIG->encryptionKeys['phone-number']['private']));
    }

    public function setPhoneNumberHash($hash)
    {
        $this->phone_number_hash = $hash;
        return $this;
    }

    public function getPhoneNumberHash()
    {
        return $this->phone_number_hash;
    }

    /**
     * Sets (overrides) social profiles information
     * @return $this
     */
    public function setSocialProfiles(array $social_profiles)
    {
        $this->social_profiles = $social_profiles;
        return $this;
    }

    /**
     * Sets (or clears) a single social profile
     * @return $this
     */
    public function setSocialProfile($key, $value = null)
    {
        if ($value === null || $value === '') {
            if (isset($this->social_profiles[$key])) {
                unset($this->social_profiles[$key]);
            }
        } else {
            $this->social_profiles[$key] = $value;
        }

        return $this;
    }

    /**
     * Returns all set social profiles
     * @return array
     */
    public function getSocialProfiles()
    {
        return $this->social_profiles ?: [];
    }

    /**
     * Sets (overrides) wire rewards information
     * @return $this
     */
    public function setWireRewards(array $wire_rewards)
    {
        $this->wire_rewards = $wire_rewards ?: '';
        return $this;
    }

    /**
     * Returns all set wire rewards
     * @return array
     */
    public function getWireRewards()
    {
        return $this->wire_rewards ?: '';
    }

    /**
     * @param string $guid
     */
    public function addPinned($guid)
    {
        $pinned = $this->getPinnedPosts();
        if (!$pinned) {
            $pinned = [];
        } else if (count($pinned) > 2) {
            array_shift($pinned);
        }

        if (array_search($guid, $pinned) === false) {
            $pinned[] = (string)$guid;
            $this->setPinnedPosts($pinned);
        }
    }

    /**
     * @param string $guid
     * @return bool
     */
    public function removePinned($guid)
    {
        $pinned = $this->getPinnedPosts();
        if ($pinned && count($pinned) > 0) {
            $index = array_search((string)$guid, $pinned);
            if (is_numeric($index)) {
                array_splice($pinned, $index, 1);
                $this->pinned_posts = $pinned;
            }
        }
        return false;
    }

    /**
     * Sets the channel's pinned posts
     * @param array $pinned
     * @return $this
     */
    public function setPinnedPosts($pinned) {
        if (count($pinned) > 3) {
            $pinned = array_slice($pinned, 0, 3);
        }
        $this->pinned_posts = $pinned;
        return $this;
    }

    /**
     * Gets the channel's pinned posts
     * @return array
     */
    public function getPinnedPosts() {
        if(is_string($this->pinned_posts)) {
            return json_decode($this->pinned_posts);
        }
        return $this->pinned_posts;
    }

    /**
     * Sets (overrides) experimental feature flags
     * @return $this
     */
    public function setFeatureFlags(array $feature_flags)
    {
        $this->feature_flags = $feature_flags;
        return $this;
    }

    /**
     * Returns all set feature flags
     * @return array
     */
    public function getFeatureFlags()
    {
        return $this->feature_flags ?: [];
    }

    /**
     * Sets (overrides) programs participations
     * @return array
     */
    public function setPrograms(array $programs)
    {
        $this->programs = $programs;
        return $this;
    }

    /**
     * Returns all set programs participations
     * @return array
     */
    public function getPrograms()
    {
        if (is_string($this->programs)) {
            return json_decode($this->programs, true) ?: [];
        }

        return $this->programs ?: [];
    }

    /**
     * Sets (overrides) monetization settings
     * @return array
     */
    public function setMonetizationSettings(array $monetization_settings)
    {
        $this->monetization_settings = $monetization_settings;
        return $this;
    }

    /**
     * Returns all set monetization settings
     * @return array
     */
    public function getMonetizationSettings()
    {
        if (is_string($this->monetization_settings)) {
            return json_decode($this->monetization_settings, true) ?: [];
        }

        return $this->monetization_settings ?: [];
    }

    /**
     * Sets (overrides) group membership
     * @return array
     */
    public function setGroupMembership(array $group_membership)
    {
        $this->group_membership = $group_membership;
        return $this;
    }

    /**
     * Returns all set group membership
     * @return array
     */
    public function getGroupMembership()
    {
        if (is_string($this->group_membership)) {
            return json_decode($this->group_membership, true) ?: [];
        }

        return $this->group_membership ?: [];
    }

    /**
     * Sets the `boost autorotate` flag
     * @param  bool $value
     * @return $this
     */
    public function setBoostAutorotate($value)
    {
        $this->boost_autorotate = (bool) $value;
        return $this;
    }

    /**
     * Gets the `boost autorotate` flag
     * @return bool
     */
    public function getBoostAutorotate()
    {
        return (bool) $this->boost_autorotate;
    }

    /**
     * Subscribes user to another user
     * @param  mixed  $guid
     * @param  array  $data - metadata
     * @return mixed
     */
    public function subscribe($guid, $data = array())
    {
        return \Minds\Helpers\Subscriptions::subscribe($this->guid, $guid, $data);
    }

    /**
     * Unsubscribes from another user
     * @param  mixed $guid
     * @return mixed
     */
    public function unSubscribe($guid)
    {
        return \Minds\Helpers\Subscriptions::unSubscribe($this->guid, $guid, $data);
    }

    /**
     * Checks if subscribed to another user.
     * @param  mixed $guid   - the user to check subscription to
     * @return boolean
     */
    public function isSubscriber($guid)
    {
        $cacher = Core\Data\cache\factory::build();

        if ($cacher->get("$this->guid:isSubscriber:$guid")) {
            return true;
        }
        if ($cacher->get("$this->guid:isSubscriber:$guid") === 0) {
            return false;
        }

        $return = 0;
        $db = new Core\Data\Call('friendsof');
        $row = $db->getRow($this->guid, array('limit'=> 1, 'offset'=>$guid));
        if ($row && key($row) == $guid) {
            $return = true;
        }

        $cacher->set("$this->guid:isSubscriber:$guid", $return);

        return $return;
    }

    /**
     * Checks if subscribed to another user in a
     * reversed way than isSubscribed()
     * @param  mixed $guid   - the user to check subscription to
     * @return boolean
     */
    public function isSubscribed($guid)
    {
        $cacher = Core\Data\cache\factory::build();

        if ($cacher->get("$this->guid:isSubscribed:$guid")) {
            return true;
        }
        if ($cacher->get("$this->guid:isSubscribed:$guid") === 0) {
            return false;
        }

        $return = 0;
        $db = new Core\Data\Call('friends');
        $row = $db->getRow($this->guid, array('limit'=> 1, 'offset'=>$guid));
        if ($row && key($row) == $guid) {
            $return = true;
        }

        $cacher->set("$this->guid:isSubscribed:$guid", $return);

        return $return ;
    }

    public function getSubscribersCount()
    {
        if ($this->host) {
            return 0;
        }

        $cacher = Core\Data\cache\factory::build();
        if ($cache = $cacher->get("$this->guid:friendsofcount")) {
            return $cache;
        }

        $db = new Core\Data\Call('friendsof');
        $return = (int) $db->countRow($this->guid);
        $cacher->set("$this->guid:friendsofcount", $return, 360);
        return (int) $return;
    }

    /**
     * Gets the number of subscriptions
     * @return int
     */
    public function getSubscriptonsCount()
    {
        if ($this->host) {
            return 0;
        }

        $cacher = Core\Data\cache\factory::build();
        if ($cache = $cacher->get("$this->guid:friendscount")) {
            return $cache;
        }

        $db = new Core\Data\Call('friends');
        $return = (int) $db->countRow($this->guid);
        $cacher->set("$this->guid:friendscount", $return, 360);
        return (int) $return;
    }

    public function getMerchant(){
        if ($this->merchant && !is_array($this->merchant)) {
            return json_decode($this->merchant, true);
        }
        return $this->merchant;
    }

    public function setMerchant($merchant){
        $this->merchant = $merchant;
        return $this;
    }


    public function isP2PMediaDisabled()
    {
        return (bool) $this->attributes['p2p_media_disabled'];
    }

    public function toggleP2PMediaDisabled($value)
    {
        $this->attributes['p2p_media_disabled'] = (bool) $value;
        return $this;
    }

    /**
     * Exports to an array
     * @return array
     */
    public function export()
    {
        $export = parent::export();
        $export['guid'] = (string) $this->guid;
        $export['name'] = htmlspecialchars_decode($this->name);

        if ($this->fullExport) {
            if (Core\Session::isLoggedIn()) {
                $export['subscribed'] = elgg_get_logged_in_user_entity()->isSubscribed($this->guid);
                $export['subscriber'] = elgg_get_logged_in_user_entity()->isSubscriber($this->guid);
            }
        }
        if ($this->exportCounts) {
            if ($this->username != "minds") {
                $export['subscribers_count'] = $this->getSubscribersCount();
            }
            $export['subscriptions_count'] = $this->getSubscriptionsCount();
            $export['impressions'] = $this->getImpressions();
        }
        $export['boost_rating'] = $this->getBoostRating();
        if ($this->fb && is_string($this->fb)) {
            $export['fb'] = json_decode($this->fb, true);
        }

        $export['merchant'] = $this->getMerchant() ?: false;
        $export['programs'] = $this->getPrograms();
        $export['plus'] = (bool) $this->isPlus();
        $export['verified'] = (bool) $this->verified;
        $export['founder'] = (bool) $this->founder;
        $export['disabled_boost'] = (bool) $this->disabled_boost;
        $export['boost_autorotate'] = (bool) $this->getBoostAutorotate();
        $export['categories'] = $this->getCategories();
        $export['pinned_posts'] = $this->getPinnedPosts();
        $export['rewards'] = (bool) $this->getPhoneNumberHash();
        $export['p2p_media_disabled'] = $this->isP2PMediaDisabled();
        $export['is_mature'] = $this->isMature();
        $export['mature_lock'] = $this->getMatureLock();
        $export['mature'] = (int) $this->getViewMature();
        $export['last_accepted_tos'] = (int) $this->getLastAcceptedTOS();

        if (is_string($export['social_profiles'])) {
            $export['social_profiles'] = json_decode($export['social_profiles']);
        }

        if (is_string($export['wire_rewards'])) {
            $export['wire_rewards'] = json_decode($export['wire_rewards']);
        }

        if (is_string($export['feature_flags'])) {
            $export['feature_flags'] = json_decode($export['feature_flags']);
        }

        if ($this->isContext('search')) {
            $export['group_membership'] = $this->getGroupMembership();
        }

        if (Helpers\Flags::shouldDiscloseStatus($this)) {
            $export['spam'] = $this->getSpam();
            $export['deleted'] = $this->getDeleted();
        }

        $export['eth_wallet'] = $this->getEthWallet() ?: '';
        $export['rating'] = $this->getRating();

        return $export;
    }

    /**
     * Get the number of impressions for the user
     * @return int
     */
    public function getImpressions()
    {
        $app = Core\Analytics\App::_()
                ->setMetric('impression')
                ->setKey($this->guid);
        return $app->total();
    }

    /**
     * Get the plus variable
     * @return int
     */
    public function getPlus()
    {
        return $this->isPlus();
    }

    /**
     * Is the user a plus user
     * @return int
     */
    public function isPlus()
    {
        return (bool) ((int) $this->plus_expires > time());
    }

    /**
     * Set plus expires
     * @var int $expires
     */
    public function setPlusExpires($expires)
    {
        $this->plus_expires = $expires;
        return $this;
    }

    /**
     * Gets the categories to which the user is subscribed
     * @return array
     */
    public function getCategories()
    {
        if (is_string($this->categories)) {
            return json_decode($this->categories);
        }

        return $this->categories ?: [];
    }

    /**
     * Sets the categories to which the user is subscribed
     * @param $value
     */
    public function setCategories($value)
    {
        $this->categories = $value;
    }

    /**
     * @return string
     */
    public function getEthWallet()
    {
        return $this->eth_wallet ?: '';
    }

    /**
     * @param string $eth_wallet
     * @return $this
     */
    public function setEthWallet($eth_wallet)
    {
        $this->eth_wallet = $eth_wallet ?: '';

        return $this;
    }

    /**
     * @param string $eth_incentive
     * @return User
     */
    public function setEthIncentive($eth_incentive = '')
    {
        $this->eth_incentive = $eth_incentive;
        return $this;
    }

    /**
     * @return string
     */
    public function getEthIncentive()
    {
        return $this->eth_incentive;
    }

    /**
     * Gets the user's icon URL
     * @param  string $size
     * @return string
     */
    public function getIconURL($size = 'medium')
    {
        $join_date = $this->getTimeCreated();
        return elgg_get_site_url() . "icon/$this->guid/$size/$join_date/$this->icontime/" . Core\Config::_()->lastcache;
    }

    /**
     * @return bool
     */
    public function isMature()
    {
        return (bool) $this->is_mature;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setMature($value)
    {
        $this->is_mature = (bool) $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getMatureLock()
    {
        return (bool) $this->mature_lock;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setMatureLock($value)
    {
        $this->mature_lock = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getLastAcceptedTOS()
    {
        return $this->last_accepted_tos ?: 0;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setLastAcceptedTOS($value)
    {
        $this->last_accepted_tos = $value;
        return $this;
    }

    /**
     * Returns an array of which Entity attributes are exportable
     * @return array
     */
    public function getExportableValues()
    {
        return array_merge(parent::getExportableValues(), array(
            'website',
            'briefdescription',
            'dob',
            'gender',
            'city',
            'merchant',
            'boostProPlus',
            'fb',
            'mature',
            'monetized',
            'signup_method',
            'social_profiles',
            'language',
            'feature_flags',
            'programs',
            'plus',
            'verified',
            'founder',
            'disabled_boost',
            'boost_autorotate',
            'categories',
            'wire_rewards',
            'pinned_posts',
            'is_mature',
            'mature_lock',
            'last_accepted_tos',
        ));
    }
}
