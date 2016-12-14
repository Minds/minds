<?php
/**
 * A minds security plugin
 *
 * - Prevents spam
 * - Enabled twofactor authentication
 */
namespace minds\plugin\guard;

use Minds\Core\Di\Di;
use Minds\Components;
use Minds\Core;
use Minds\Api;

class start extends Components\Plugin
{
    public function __construct($plugin)
    {
        parent::__construct($plugin);

        $this->init();
    }

    public function init()
    {
        Api\Routes::add('v1/authenticate/two-factor', "\\minds\\plugin\\guard\\api\\v1\\twoFactor");

        \elgg_register_event_handler('create', 'object', array($this, 'createHook'));
        \elgg_register_event_handler('update', 'object', array($this, 'createHook'));

        \elgg_register_event_handler('login', 'user', array($this,'loginHook'));

        $routes = core\Router::registerRoutes($this->registerRoutes());
    }

    /**
     * Handler the pages
     *
     * @param array $pages - the page slugs
     * @return bool
     */
    public function registerRoutes()
    {
        $path = "minds\\plugin\\guard";
        return array(
            '/settings/twofactor' => "$path\\pages\\twofactor",
            '/login/twofactor' => "$path\\pages\\twofactor\authorise"
        );
    }

    protected function prohbitedDomains()
    {
        return [
            //shorts
            //	't.co', 'goo.gl', 'ow.ly', 'bitly.com', 'bit.ly','tinyurl.com','bit.do','go2.do',
            //	'adf.ly', 'adcrun.ch', 'zpag.es','ity.im', 'q.gs', 'lnk.co', 'is.gd',
            //full
            'movieblog.tumblr.com', 'moviehdstream.wordpress.com', 'moviehq.tumblr.com', 'moviehq.webs.com',
            'moviehq.wordpress.com', 'movieo.wordpress.com', 'movieonline.tumblr.com', 'movieonline.webs.com',
            'movieonline.wordpress.com', 'movieonlinehd.tumblr.com', 'movieonlinehd.webs.com', 'movieonlinehd.wordpress.com',
            'movies.tumblr.com', 'moviesf.tumblr.com', 'moviesgodetia.com', 'movieslinks4u', 'moviesmount.com',
            'moviesmonster.biz', 'moviesondesktop', 'moviesonlinefree.biz', 'moviestream.wordpress.com',
            'movieontop.com', 'afllivestreaming.com.au', 'londonolympiccorner', 'nrllivestreaming.com.au',
            '24x7livestreamtvchannels.com', 'www.edogo.us', 'all4health.in', 'watches4a.co.uk', 'es.jennyjoseph.com',
            'allsportslive24x7.blogspot.com', 'boxing-tv-2014-live-stream.blogspot.com', 'amarblogdalima.blogspot.com',
            'www.officialtvstream.com.es', 'topsalor.com', 'busybo.org', 'www.nowvideo.sx', '180upload.com', 'allmyvideos.net',
            'busybo.org', 'hdmovieshouse.biz', 'sportblog.info', 'psport.space', 'discus.space', 'euro2016.it.ua', 'neymar.space',
            'espnstream.space', '2016.vn.u', 'blogstream.space', 'liveextratime.xyz', 'thebestlive.xyz', 'streamoffside.xyz', 'sportmaster2014.page.tl',
            'bloggersdelight.dk', 'watchsportslive.space', 'freeforward.xyz', 'live4sports.xyz', 'streamfun.xyz', 'angelfire.com', 'streamtime.xyz',
            'futebol2star.com', 'live2sport.com', 'newssports.space', 'onlineolympics.xyz', 'liveolympics.xyz', 'streamontv.xyz', 'londonschedule.com',
            'onlineolympics.space', 'sportwinning.xyz', 'streamworld.xyz', 'streamtop.xyz', 'livechampion.xyz', 'playstreams.xyz', 'live4sport.xyz',
            'streampage.xyz', 'calendarsport.space', 'fsport.space', 'euro2016.od.ua', 'streambig.xyz', 'sportprediction.xyz', 'streamwork.xyz',
            'r041.donnael.com', '2016.lt.ua', 'vipleague.se', 'liveonline.company', 'liveolympics.space', 'seoandvideomarketing.com.au', 'vipbox.sx',
            'germanypolandlivestream.club', 'sportgoal.xyz', 'ggdbsale.com', 'gorillasteroids.eu', 'watchlivesports.space', 'penaltyshootout.xyz',
            'streamgroup.xyz', 'streamnew.xyz', 'cottonsport.space', 'gosport.space', 'streambest.xyz', 'penaltyspot.xyz', 'streamthe.xyz',
            'liveevents.name', 'londonblog.work', 'testcollections.com', 'alfagy.com', 'teravide1974.full-design.com', 'selfnarhasbllaq1980-blog.logdown.com',
            'neipononchoi1984.suomiblog.com', 'gemttranlonthe1985.blogzet.com', 'pitchero.com', 'blogolize.com', 'lisbopholsven1974.thezenweb.com',
            'blogocial.com', 'tinyblogging.com', 'share.pho.to', 'community.vietfun.com', 'ockuderla1985.full-design.com', 'unmosimla1978.total-blog.com',
            'gemttranlonthe1985.blogzet.com', 'rapptubizboe1978.blogminds.com', 'descduclighgon1973.full-design.com', 'ricphosati1972.full-design.com',
            'fuddbluslanmaa1975.blogdigy.com', 'smarforcute1976.blogdigy.com'
        ];
    }

    protected function strposa($haystack, $needles, $offset = 0)
    {
        if (!is_array($needles)) {
            $needles = array($needles);
        }
        foreach ($needles as $query) {
            if (strpos($haystack, $query, $offset) !== false) {
                return true;
            } // stop on first true result
        }
        return false;
    }

    public function createHook($hook, $type, $params, $return = null)
    {
        $object = $params;
        if ($this->strposa($object->description, $this->prohbitedDomains())) {
            \register_error('Sorry, your post contains a reference to a domain name linked to spam. You can not use short urls (eg. bit.ly). Please remove it and try again');
            if (PHP_SAPI != 'cli') {
                forward(REFERRER);
            }
            return false;
        }

        return true;
    }

    /**
     * Twofactor authentication login hook
     */
    public function loginHook($event, $type, $user)
    {
        global $TWOFACTOR_SUCCESS;

        if ($TWOFACTOR_SUCCESS == true) {
            return true;
        }

        if ($user->twofactor && !\elgg_is_logged_in()) {
            //send the user a twofactor auth code

            $twofactor = new lib\twofactor();
            $secret = $twofactor->createSecret(); //we have a new secret for each request

            $this->sendSMS($user->telno, $twofactor->getCode($secret));

            // create a lookup of a random key. The user can then use this key along side their twofactor code
            // to login. This temporary code should be removed within 2 minutes.
            $key = md5($user->username . $user->salt. time() . rand(0, 63));

            $lookup = new \Minds\Core\Data\lookup('twofactor');
            $lookup->set($key, array('_guid'=>$user->guid, 'ts'=>time(), 'secret'=>$secret));

            //forward to the twofactor page
            throw new Exceptions\TwoFactorRequired($key);

            return false;
        }
    }

    /**
     * Send an sms
     */
    public function sendSMS($number, $message)
    {
        $result = null;

        $config = Di::_()->get('Config')->get('twilio');

        try {
            $AccountSid = $config['account_sid'];
            $AuthToken = $config['auth_token'];
            $client = new \Services_Twilio($AccountSid, $AuthToken);
            $result = $client->account->messages->create(array(
                'To' => $number,
                'From' => $config['from'],
                'Body' => $message,
            ));
        } catch (\Exception $e) {
            error_log("[guard] Twilio error: {$e->getMessage()}");
        }

        return $result ? $result->sid : false;
    }
}
