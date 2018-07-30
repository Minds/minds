<?php

namespace Minds\Core\Security;

use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Security\TwoFactor;
use Minds\Exceptions;

class Events
{
    protected $sms;

    public function __construct()
    {
        $this->sms = Di::_()->get('SMS');
    }

    public function register()
    {
        Dispatcher::register('create', 'elgg/event/object', [$this, 'onCreateHook']);
        Dispatcher::register('create', 'elgg/event/activity', [$this, 'onCreateHook']);
        Dispatcher::register('update', 'elgg/event/object', [$this, 'onCreateHook']);
        Dispatcher::register('login', 'elgg/event/user', [$this, 'onLoginHook']);
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

    protected function prohibitedDomains()
    {
        return [
            //shorts
            //	't.co', 'goo.gl', 'ow.ly', 'bitly.com', 'bit.ly','tinyurl.com','bit.do','go2.do',
            //	'adf.ly', 'adcrun.ch', 'zpag.es','ity.im', 'q.gs', 'lnk.co', 'is.gd',
            //full
            'movieblog.tumblr.com',
            'moviehdstream.wordpress.com',
            'moviehq.tumblr.com',
            'moviehq.webs.com',
            'moviehq.wordpress.com',
            'movieo.wordpress.com',
            'movieonline.tumblr.com',
            'movieonline.webs.com',
            'movieonline.wordpress.com',
            'movieonlinehd.tumblr.com',
            'movieonlinehd.webs.com',
            'movieonlinehd.wordpress.com',
            'movies.tumblr.com',
            'moviesf.tumblr.com',
            'moviesgodetia.com',
            'movieslinks4u',
            'moviesmount.com',
            'moviesmonster.biz',
            'moviesondesktop',
            'moviesonlinefree.biz',
            'moviestream.wordpress.com',
            'movieontop.com',
            'afllivestreaming.com.au',
            'londonolympiccorner',
            'nrllivestreaming.com.au',
            '24x7livestreamtvchannels.com',
            'www.edogo.us',
            'all4health.in',
            'watches4a.co.uk',
            'es.jennyjoseph.com',
            'allsportslive24x7.blogspot.com',
            'boxing-tv-2014-live-stream.blogspot.com',
            'amarblogdalima.blogspot.com',
            'www.officialtvstream.com.es',
            'topsalor.com',
            'busybo.org',
            'www.nowvideo.sx',
            '180upload.com',
            'allmyvideos.net',
            'busybo.org',
            'hdmovieshouse.biz',
            'sportblog.info',
            'psport.space',
            'discus.space',
            'euro2016.it.ua',
            'neymar.space',
            'espnstream.space',
            '2016.vn.u',
            'blogstream.space',
            'liveextratime.xyz',
            'thebestlive.xyz',
            'streamoffside.xyz',
            'sportmaster2014.page.tl',
            'bloggersdelight.dk',
            'watchsportslive.space',
            'freeforward.xyz',
            'live4sports.xyz',
            'streamfun.xyz',
            'angelfire.com',
            'streamtime.xyz',
            'futebol2star.com',
            'live2sport.com',
            'newssports.space',
            'onlineolympics.xyz',
            'liveolympics.xyz',
            'streamontv.xyz',
            'londonschedule.com',
            'onlineolympics.space',
            'sportwinning.xyz',
            'streamworld.xyz',
            'streamtop.xyz',
            'livechampion.xyz',
            'playstreams.xyz',
            'live4sport.xyz',
            'streampage.xyz',
            'calendarsport.space',
            'fsport.space',
            'euro2016.od.ua',
            'streambig.xyz',
            'sportprediction.xyz',
            'streamwork.xyz',
            'r041.donnael.com',
            '2016.lt.ua',
            'vipleague.se',
            'liveonline.company',
            'liveolympics.space',
            'seoandvideomarketing.com.au',
            'vipbox.sx',
            'germanypolandlivestream.club',
            'sportgoal.xyz',
            'ggdbsale.com',
            'gorillasteroids.eu',
            'watchlivesports.space',
            'penaltyshootout.xyz',
            'streamgroup.xyz',
            'streamnew.xyz',
            'cottonsport.space',
            'gosport.space',
            'streambest.xyz',
            'penaltyspot.xyz',
            'streamthe.xyz',
            'liveevents.name',
            'londonblog.work',
            'testcollections.com',
            'alfagy.com',
            'teravide1974.full-design.com',
            'selfnarhasbllaq1980-blog.logdown.com',
            'neipononchoi1984.suomiblog.com',
            'gemttranlonthe1985.blogzet.com',
            'pitchero.com',
            'blogolize.com',
            'lisbopholsven1974.thezenweb.com',
            'blogocial.com',
            'tinyblogging.com',
            'share.pho.to',
            'community.vietfun.com',
            'ockuderla1985.full-design.com',
            'unmosimla1978.total-blog.com',
            'gemttranlonthe1985.blogzet.com',
            'rapptubizboe1978.blogminds.com',
            'descduclighgon1973.full-design.com',
            'ricphosati1972.full-design.com',
            'fuddbluslanmaa1975.blogdigy.com',
            'smarforcute1976.blogdigy.com',
            'xn--90aizihgi.xn--p1ai',
            'tinyurl.com',
            'bit.ly',
            '123football.space',
            'bitly.com',
            'j.mp',
            'livestreaming.one',
            'livestreaming.life',
            'forbest.pw',
            'olizev.tdska2ll.ru',
            'tdska2ll.ru',
            'tdska1ll.ru',
            'tdska3ll.ru',
            'tdska4ll.ru',
            'ihmail.ru',
            'tdska5ll.ru',
            'tdska6ll.ru',
            'll.ru',
            'shorl.com',
            'scorestream.space',
            'bestsplayer.xyz',
            'worldwideevents.space',
            'worldseries.space',
            'best247chemist.net',
            '9tn.ru',
            'futbolkin2013.ru',
            'playnowstore.com',
            'qr-url.tk',
            'watchonlinerugby.net',
            'esecuritys.com',
            'rufile.no-ip.ca',
            'imzonline.com',
            'femeedia.com',
            'mediomatic.com',
            'savemoneyeasily.com',
            'option1pro.com',
            'perron07.nl',
            'movieonrails.com',
            'topmoviestoday.com',
            'playnowstore.com',
            'g-files.biz',
            'dawnloadonline.com',
            'thedirsite.com',
            'siteslocate.com',
            'mydrugdir.com',
            'find24hs.com',
            'veeble.org',
            'movieonrails.com',
            'bestmoviehd.net',
            'putmovies.info',
            'awarefinance.com',
            'shurll.com',
            'acceptsearch.com',
            'signforcover.com',
            'raisengine.com',
            'rocketcarrental.com',
            'godsearchs.com',
            'listenhanced.com',
            'find24hs.com',
            'findinform.com',
            'sitesworlds.com',
            'rocketcarrental.com',
            'thedirsite.com',
            'getboook.com',
            'pokerarena88.com',
            'aquamelia.com',
            'beautyskintalks.com',
            'getmooovie.com',
            'getdriversss.com',
            'getsoooft.com',
            'getgamesss.com',
            'abrts.pro',
            'leadbit.biz',
            'efght.pro',
            'qyresearcheurope.com',
            'plusfreemaxfr.com',
            'getappmac.com',
            'getharlemhealthy.org',
            'goo.gl',
            'getmooovie.com',
            'marketreportscenter.com',
            'getsooft.com',
            'myowndom.ru',
            'print-mgn.ru',
            'wiki-data.ru',
            'velobog.ru',
            'mobisony.ru',
            'dzeroki.ru',
            'slimkor.ru',
            'kak-brosit-kyrit.ru',
            'jinyurl.com',
            'urlin.us',
            'capillus.com',
            'siteprofissional.com',
            'mitersawjudge.com',
            'mohajreen-jeeda.com',
            'jobberies.com',
            'bestfilms.site',
            'baystudios.ch',
            'elvenarhack.bid',
            'essencephskincare.com',
            'blog2learn.com',
            'superrugbyonline.net',
            'superrugby18.livejournal.com',
            'expertairco.com',
            'draesthetica.co.uk',
            'sphere.social',
            'saveabookmarks.xyz',
            '/t.co',
            'samuelsconstruction.build',
            'pmwares.com',
            'watchesofwales.co.uk',
            //'.ru',
            'zotero.org',
            'speakerdeck.com',
            'freesiteslike.com',
            'pusha.se',
            'vrootdownload.org',
            'rubberwebshop.nl',
            'restaurerlecorps.info',
            'discretthemes.info',
            'bride-forever.com',
            'simplesmetamorphoses.info',
            'mp3gain.com',
            'mp4gain.com',
            'ttlink.com',
            'onepost.cf',
            'getmefunds.com',
            'vikinail.pl',
            'typesofbeauty.info',
            'joie6portia93.bloglove.cc',
            'htgtea.com',
            'tblogz.com',
            'liveinternet.ru',
            '.diowebhost.com',
            '/yoursite.com',
            'reworkedgames.eu',
            'mp3gain.sourceforge.net',
            'pages10.com',
            ];
    }

    public function onCreateHook($hook, $type, $params, $return = null)
    {
        $object = $params;
        if ($this->strposa($object->description, $this->prohibitedDomains()) || 
            $this->strposa($object->briefdescription, $this->prohibitedDomains()) ||
            $this->strposa($object->message, $this->prohibitedDomains()) ||
            $this->strposa($object->title, $this->prohibitedDomains())
        ) {
            throw new \Exception('Sorry, your post contains a reference to a domain name linked to spam. You can not use short urls (eg. bit.ly). Please remove it and try again');
            if (PHP_SAPI != 'cli') {
                forward(REFERRER);
            }
            return false;
        }

        if ($type == 'group' && $this->strposa($object->getBriefDescription(), $this->prohibitedDomains())) {
            return false;
        }

        return true;
    }

    /**
     * Twofactor authentication login hook
     */
    public function onLoginHook($event, $type, $user)
    {
        global $TWOFACTOR_SUCCESS;

        if ($TWOFACTOR_SUCCESS == true) {
            return true;
        }

        if ($user->twofactor && !\elgg_is_logged_in()) {
            //send the user a twofactor auth code

            $twofactor = new TwoFactor();
            $secret = $twofactor->createSecret(); //we have a new secret for each request

            $this->sms->send($user->telno, $twofactor->getCode($secret));

            // create a lookup of a random key. The user can then use this key along side their twofactor code
            // to login. This temporary code should be removed within 2 minutes.
            $bytes = openssl_random_pseudo_bytes(128);
            $key = hash('sha512', $user->username . $user->salt . $bytes);

            $lookup = new \Minds\Core\Data\lookup('twofactor');
            $lookup->set($key, array('_guid' => $user->guid, 'ts' => time(), 'secret' => $secret));

            //forward to the twofactor page
            throw new Exceptions\TwoFactorRequired($key);

            return false;
        }
    }

}
