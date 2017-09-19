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

    protected function initializeAttributes() {
        $this->attributes['boost_rating'] = 2;
        $this->attributes['mature'] = 0;
        $this->attributes['mature_content'] = 0;
        $this->attributes['social_profiles'] = [];
        $this->attributes['ban_monetization'] = 'no';
        $this->attributes['programs'] = [];
        $this->attributes['monetization_settings'] = [];
        $this->attributes['group_membership'] = [];
        $this->attributes['plus'] = 0;
        $this->attributes['verified'] = 0;
        $this->attributes['disabled_boost'] = 0;
        $this->attributes['categories'] = [];
        $this->attributes['wire_rewards'] = '';

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
     * Sets the `mature` flag
     * @param  bool|int $value
     * @return $this
     */
    public function setMature($value)
    {
        $this->mature = $value ? 1 : 0;
        return $this;
    }

    /**
     * Gets the `mature` flag
     * @return bool|int
     */
    public function getMature()
    {
      return $this->mature;
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

    /**
     * Set the secret key for clusters to use
     * @todo - should we use oauth2 instead. should this be stored in its own row rather than in the user object?
     * @param string $host
     */
    public function setSecretKey($host)
    {
        $key = "secret:" . serialize($host);
        $this->$key = core\clusters::generateSecret();
        $this->save();
    }

    /**
     * Exports to an array
     * @return array
     */
    public function export()
    {
        $export = parent::export();
        $export['guid'] = (string) $this->guid;

        if ($this->fullExport) {
            if (Core\Session::isLoggedIn()) {
                $export['subscribed'] = elgg_get_logged_in_user_entity()->isSubscribed($this->guid);
                $export['subscriber'] = elgg_get_logged_in_user_entity()->isSubscriber($this->guid);
            }
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
        $export['plus'] = $this->getPlus();
        $export['verified'] = (bool) $this->verified;
        $export['disabled_boost'] = (bool) $this->disabled_boost;
        $export['categories'] = $this->getCategories();

        if (isset($export['mature'])) {
            $export['mature'] = (int) $export['mature'];
        }

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
        return (bool) $this->plus;
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
            'disabled_boost',
            'categories',
            'wire_rewards',
        ));
    }
}
