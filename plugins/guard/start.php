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
            'fuddbluslanmaa1975.blogdigy.com', 'smarforcute1976.blogdigy.com',"000cas.info","1011foster.com","111cas.info","137.74.137.255;1","18dating.pw","1vdvul71.biz","2msl.com","2u-u3t1-o11u.biz","2vuc.com","2wnnk81m1u.com","51.254.81.1;1","54r3.com","5asv4mhzv1.dasyclub.top;1","5lftilzg.biz","68hkwwad3hl.net","6august2016ppe.ro.im;1","6qde7kx-khy.biz","777-a-money.us","7august2016ppe.ro.im;1","90u9.com","9jpk.com","a3ws-z6srw-9q3-br2.biz","a9dt.com","adadvertorials.com;1","adtggfge.ru","advertsyan.com;1","advicewomenheealthy.com","adyielded.com;1","afbbgfjq.info","airmaxs2016.org","aktaseodomains.org","alldomainsmegs.org","alldompros.org","alldomshoster.org","allreghosters.org","alseeing.com","altnewspress.com","amontillergy.com","andreaplusbrenda.us","anettatamma.ru","aniesdomainseos.org","anieshostsseo.org","aniestechespro.org","annatesshockingness.pw","annatesunparrying.pw","annountify.com","anstioder.com","antelopehillsbeauty.com","anunciatudo.net","anydomainsalls.org","anyservdoms.org","apexgc@barid.com;419","applerapidnewscentral.com","arabillionague.com","assoilmerchantableness.pw","assoilnondistinguished.pw","ataraxicnonmetamorphous.pw","ataraxicunfavoring.pw","autoandina.info","autosystem.org;1","auxeticpillwort.pw","bancaelectronica-abanca.com","bathicalson.com","beautyskinnow.com","beckmann-gmbh.info","becogniformed.com","becominent.org","becontropon.com","bernoulliriposting.pw","besprinkledisunited.pw","bestamilarism.com","bestherbdeal.ru","bestnewphonesetup.com","bestproshotcams.com","bestrealweb.su","bestsurvivorstools.com","besttabletmall.ru","betterratemortage.com","bettyplusmartha.us","bewyg9-5r-l6.biz","bfatyw2p.biz;1","bibbiehenka.ru;1","bietthubiennova.info","birectangularentomolegist.pw","bj7a.com","bluerapidnewscentral.com","bobbywilli.com","boletim.srv.br","borary.com","brainspillsmart.com","brandysherri.in","bstonlineclass.net","bugshunt.pw","burbitted.com","c27ev18mjh.com","cagroosh-g1beauns.net","caixadecompras.com.br;1","calcutting.org","camilececil.in","canadiasite24.info","cancelati0nscheduledd.com","cancenin.com","cappsclet.info","carbamatepediculicide.pw","carbamatesquinnying.pw","carding.info","careernewresourceupdates.com","caremedicaltoday.com","cariottaumeko.ru","catherineplusamber.us","catulluswedging.pw","caughted.org","cautional.org","cbglobal.net","centralwebnews.info","certlogicpro.com","chance-a-club.us","charountinue.com","charternewestonlineinfo.com","chiba-fnavi.com","chievernment.com","chips-a-happy.us","cholics.com","churttaos.info","cinehallgos.info","ciscotlets.com","city-plumbers.ru","cividen.com","claretacrin.in","cleakoye.info","cleantoe.pw","clickandbuy.pw","climatehondsk.info","cnl5-g4lt-ky.biz","collectif-blo.com","comendapremioqualidade.com","commodespean.com","connectnewphonesetup.com","cooduals.com","cookcoolgadgets.com","coolshinehasn.info","coreprogramss.com","costice.org","cotenanet.com.br;1","crisional.com","crockeco.com;1","danielleplusbetty.us","ddadda004.in","deadlineenrollmentnewinfo.com","deanareeba.in","debitrelif.pw","debrapluspatricia.us","defertian.com","deliafranny.in","dentistproducts.info","dependinant.info","desecreek.com","desupremelimited.com;419","dianepluskelly.us","digibighing.info","digisoft.com.ar","dimabiodiet.com","diminishnowfatto.com","diosmeanic.com","discietit.com","divulseinscribing.pw","dogaetbalik.com","dom-dlya-roditeley.ru","domainserviceorga.org","domaintechesanies.org","domdomaintranspro.org","dommediareglet.org","domservhostly.org","domsregorga.org","domsseohosters.org","domsservmeg.org","donghensk.info","dordogneweddings.com","dorotheedemetra.com","dotpiu.us","dreamrq.com","driveinthedjhy.info","drkarynwhite.com","drugss.net","dt5tcqkw.biz","dubairegal.com;1","ebody.info","eddedd004.in","edwardpics.com","eeteet002.in","egeszseggarancia.com;1","ehpovisage.ru","ejjejj002.in","elmelomano.com","emaildynamic.info;1","emaileron.com","emilyplussara.us","enerhdproshots.com","enjoyfullion.com","enricaallison.ru","enrollmentlatestupdates.com","enteritas.com","equilibraham.org","errerr002.in","evelynplusjoan.us","evision.pw","ezofbattery1.info","ezyautocare.pw","fallabout.com","fastwebleads.com;1","fatestwaytoslim.com","favouritismoutsnore.pw","favouritismriposting.pw","fce-sd.com","fdv1.com","feefee001.in","feelingfly.pw","feldmann-gmbh.info","fidelityratebook.com","figip.us","fijep.us","firow.us","firstaidelement.ru","fitingoilatglance.com","fknm-d4ohz7.biz","flu-qj2z-5de.biz","fondepenguage.com","forcentialin.com","forestromaie.pw;1","forgetappdon.info;1","formanymen24.co.com","formula-1c.ru","fortune-a-win.us","fotooboi-su.ru;1","fourlcreative.com","francynerobinetta.in","fruitlants.org","fujitectentoshibajp.com","funiftheday.info","funnynewswire.com","fwimejiw.com","fx-managers.info","gadeu.us","gadgettoslim.com","gafhgnice.info","gahjuoi.us","gain-and-poker.us","gaincasinoplay.us","gapruc.us","garsad.us","ghvo-ifx-nov.biz","gibrammath.com","gillilisheag.ru","globalhotdeal.ru","gogod.info","goodlookingsmile.com","goodscoretocredit.com","grand-game.net","grandbusiness.net","grawmatr.info","growinhauht.info","gukpec.us","gychuanhao.com","hairofnalts.com","hanantial.com","handelssystem24.com","happytobehero.info","hbrvar.com","healthlatestspecialinfo.com","healthnewestonlineinfo.com","healthnewupdatedinfo.com","healthspecialnewinfo.com","healthynewupdatedspecials.com","helldhjsing.info","helloworld99.com","helpwithmentalissues.com","herbalherbsshop.in","hezuw.us","hf7hbi-fdd6.biz","hh1o.com","hiamwath.info","hildegaardmarilee.ru","hillofthrhi.info","hippo-enterprise.com","hitinmall.pro;1","hoerkras.info","hogemail.com","holdestructural.org","hongkongsuits.org","horoo.us","horpak-today.com","hosmoz.us","hosteraniesregs.org","hosterhostsreg.org","hostersanyhost.org","hostersdomainstrans.org","hosterseoorgas.org","hosterunireg.org","hostproregs.org","hostsdomsmedia.org","hostshosterpros.org","hotandroidapp.com;1","hotpillmart.ru","hotsecurewebmart.in","hottrustedgroup.ru","howerender.com","hxm4f3.soshiny.vip;1","impresquake.com","inclumscing.com","indianvipescort.com","indigorapidnewscentral.com","infodiff.eu;1","inhalantgrisaille.pw","innerthdgsh.info","innovative-website-design.com","instaclash.com;1","instanthdlens.com","internetproductnews.com","involn.com","iodationinscribing.pw","ionfxpjn.ru","iqqjwfoz.ru","iqreleaseedtrump.com","irbrblml.ru","irisdelicias.ru","izquierdoyasociados.cl;1","j1d-v03-m9k0.biz","j5zk-7mt3-ce.biz","jepweagbb.biz","jepwlfkqo.biz","jetcharternewupdates.com","jfgcjnpku.biz","jfntvipbz.biz","jfpvsgwxb.biz","jfwlvnjuv.biz","jfwttzoql.biz","jgjtojivy.biz","jiragunyah.com","jishuf.us","jjfuncenter.com","jodeebrittaney.com","jou6k-sva-xp.biz","kansewpoun.info","kathyplusbarbara.us","kathypluskaren.us","kdgusnnding.info;1","kerajinan-batik.co.id","kibokogroup.net","kickdomain.org","kigivo.com;1","kimberlypluscarolyn.us","kinghkjing.info","kinhkhanhlinh.com","kkzvpjryk.biz","klzqodbvt.biz","kmetvrhot.biz","kmjhpwvez.biz","kmtfmrlsi.biz","kndxqxnfa.biz","knicchid.info","knrkicgzs.biz","kondahtings.info","ksjdhfgbncnba.info","ksjuyetrbon.info","ksnr-1p8f-st.biz","kuruma-discount.com","labck.com;1","lafoiredesbonnesaffaires.fr","lamaisondesoffres.fr","landing-flyho.ru","lauraplusjudy.us","laurenpluskaren.us","layerdirector.info","ldoblewphe.info","legmartprogo.com;1","lendrio.com;1","lensupgraderhd.com","lhkrbwps.ru","lifenewestonlineupdates.com","lifenewestupdatedinfo.com","lifespecialnewestinfo.com","linuxvaultsandcourier.com","liplaindez.info","lipodowner.com","lirez.us","liveoffer88good.com","livingtowardslove.com","localnsadater.com","locstermaxy.info","looloo001.in","lossrecovree.pw","lowingquality.info","luckydrugreward.ru","luckypharmdeal.ru","lukia.us","m8bbu-gdvsp.biz","mabbon-services.com","machientainted.com","maennertabletten-bestellen.com.de","mainesolarcompany.com","marcus-seng.com","marieplusjoan.us","markisteine.com","martsings.com","mattergood.net","mcmarqueting.com","mebel-core.ru;1","mediadomainshostr.org","mediamegaseolab.org","megatranshostr.org","melissaannamarie.com","menshelth.pw","mensrockhard.com","merchantdemons.com","merchentdealers.com","messgespool20.com","mexmails.com","mgmtqlt.com","militat.org","milloenvios.com;1","minidacha.com","mixtradeserver.biz;1","montmaxter.info","morehdplease.com","mortagesupresor.com","mosquitosdoom.com","msnbnn.pw","murielertha.com","music-petrkazakov.ru","musjoe.us","myglobalpurchase.ru","myjinghan.net","mymedicalstore.ru","myremedialmall.com","nameditish.com","nasjas003.in","nasuo.us","natural-flowerp.ru","naturalflowerpwix.ru;1","navuse.com","neckho.com;1","nespressokapsler.com","newcruisespecialinfo.com","newdeadlineupdatedinfo.com","newenrollmentdetails.com","newestearningupdates.com","newestenrollmentspecials.com","newestgiftings.com","newhealthyupdates.com","newhelpprograminfo.com","newhotpurchase.ru","newpackagetravelupdates.com","newplacestomeet.com","newprogramspecialinfo.com","newsecretupdatedinfo.com","newspermcirculacion.cl;1","newtravelspecialupdates.com","ngolobalakz4.com","niceinhidhy.info","nicholezilvia.in","nonedementianow.com","nonememoryloss.com","nopew.us","northernminkoil.com","northsightenergy.com","nsdaflush.info","nturnnoys.info","nucau.us","o58j.com","ochakovo-zavod.ru","octaviadelavega.com","officialnflapparel.us","oilslimmer.com","olea-vacances.com","oliviaharriette.com","oneguybrewing.com","onlineenrollmentupdates.com","onlineregistrations.org","orgadomorglet.org","orthreavouac.info","ossbjj.com","othergdhking.info;1","outmidialink.info","overdominatedblockbusting.pw","p6wm5-48-q4g.biz","packageforwindowsrem.com","packagesatsight.com","pamelaplusjoyce.us","panamaline.net","pandorakelci.com","parterredphineus.pw","pasmas002.in","pasmas004.in","patiesavage.com","perfectshing.info;1","persiterric.com","peteirphume.com","pgwcvw0-ngf.com;1","philbinocyclone.org","philosophy-sdass.com","phonenewestsetupupdates.com","phoneoptionnewsetup.com","phonesetupnewestupdates.com","phoneymelodie.com","pikafoods.com","pills4life.co.com","pills4mens.pw","piwloj.us","piyiq.co.id","plussizereducer.com","pmx-5m4l-dv-qocvtd2t.biz","pointsapprovedshop.com","politicalbattliigniiq.com","poophuns.com","portperryconcerts.com","powul.us","premany.com","presour.com","presymptomingrown.pw","pretated.com","preturese.com","prodomainsorga.org","professionzeal.net","profit-and-bonus.us","programsdesigntohelp.com","prohdshotcams.com","prosmegtrans.org","prostechesall.org","punistrial.org","qaslas004.in","qgh2.com","qom-football.com","quirelate.com","qxn6.com","r-resources.com","ragilition.org","raininjduhfh.info","rebekahkare.com","recognitionsocia.org","redpenblueberry.com","redy2by.info","reekam.com;1","refectere.com","regsall.org","regsservicesorga.org","relationances.com","relationifymedia.net","remyblondelle.in","renewalnowwin.com","restartmemoryfresh.com","restoreallmems.com","reviewfortotalbody.com","rewardnewbonusupdates.com","rewardonlinebonus.com","ricic.us","ringinalistilit.com","riwjio.us","rodivickie.in","roundofground.info","roushi-trouble.com","rubin-71.ru","russian-portals-network.ru","russian-property-buyers.ru","ruthpluskayla.us","sacodecompras.com.br;1","safetabsquality.ru","salazzola.info","sametimeloan.com;419","samkinghuls.info","sandraplusjean.us","santohskinf.info;1","sarahplusdiana.us","satinrougepress.com","sayjeff.com","schototly.com","schurzic.info","scotlantimor.org","screenrulermac.com","securepillmarket.ru","sejir.us","seomegdomain.org","seoregdompro.org","seosmegadom.org","seosprostrans.org","seotransalls.org","separace.com","servicesseopro.org","servmegdom.org","servsdomhoster.org","setoseharingey.pw","setupnewestphoneoptions.com","sezonnaj-rasprodaza-nasosov.ru","sfo598kk.biz","sgressif.info","she-lo.com","shecolumbia.com","shingihdgoal.info","shinkinghf.info","shipyardere.com","shoehornmonotypic.pw","shokofoto.com","shoodgacress.info","shopnetbusiness.com","show-me.pw","simbolosdemusica.com","singofhillshu.info","sitologynonshipping.pw","sjkdingskd.info","skinadvertoless.com","slimbelly.pw","slownessbasenji.pw","slownessdravite.pw","smartpilldeal.ru","smilehdein.info","smileinghtdjhs.info","snappycatclock.com","sneadgall.info","socceroverunder.com","solenald.com","solsquab.info","soundbuss.us","soutputnamic.com","spirecons.com","starptc.com","stc-holidays.com","steenibksam.info","streamingupdatednewinfo.com","strictgossips.com","strictlyclosers.com","submering.com","suggestional.org","suninghdt.info","superdescontodanet.com.br;1","superflattsberlly.com","superpricesnow.com","supersmartiipants.com","supplictions.com","sweetrapidnewscentral.com","tajim.us","takiyagaram.com;1","techesprosreg.org","techesservhosters.org","techestransall.org","technalink.info","techregpros.org","tectrearient.com","tedcoworldwide.com","telah.us","teodoradarya.ru","teresaplusjacqueline.us","teva-college.com","theluminate.net","themedicationinc.ru","therealstraw.com","theshockingnewsecrets.com","thetraffic.pw","thiskatory.com","thmavils.info","througalaero.com","tikpec.us","tkspro.net","tobeymurial.com","tojren.us","topnutraprod.com","toreplacenow.com","traking.pw","transallsmeg.org","transhosterdomlet.org","trature.com","travelnewestpackagespecials.com","travelpackagenespecials.com","travelspecialnewestupdates.com","trinyelss.us","trustedsolution1.com","tubupdatenewoptions.com","tujuo.us","tzymcqua.info","ultimate-pug.net","ultraxmart.com","unbonnetburningly.pw","uneenviedefolie.com","unorsmooshotshd.com","updatedeadlineinformation.com","updatedenrollmentinfo.com","uvillc.com","v47zqsm-tlx.com","vacationonlinenewinfo.com","vacationspecialnewestinfo.com","vbkoxefmqi.ru","vegas-and-happy.us","veneratornonmetamorphous.pw","ventasalpura.com","vertebrallyoverfraught.pw","vibehere.com","vipwin.info","viqsiniw.ru","virondong.com","vn3h.com","vps11w003.com","vrgmailg.info","vrgmailh.info","vrgmailk.info","wanspink.com","wathdguinfksl.info","web-media-services.com","web-reinvention.com","web.servername.info:88;1","webanditmenters.com","webemarketings.com","weddmathom.com","weguk.us","wholesalecheapjerseys.us.com","wigea.us","win-and-winner.us","wlsjravli.ru","woodinthegill.info","www-moskovv-probus-con.ru","www-serd-carossii.ru;1","wzwebsoleo.biz","x9my.com","xahjijhghj.biz","xazovjyrgy.biz","xbpurbdzaa.biz","xcmflhbsxu.biz","xcmlcskcaf.biz","xcmpktpbom.biz","xdjmnnbcfe.biz","xdoysvzspe.biz","xdvczhzkdd.biz","xedgommodc.biz","xewleejlkj.biz","xfdeuxbqke.biz","xgphnojjqn.biz","xgyhtwoctz.biz","xmkjjvlo.info","xn--90alid7adne1a.xn--p1ai","xn--c1avizzp.xn--p1ai","xn--d1akg0dwa.xn--p1ai","xn--h1ai1aih.xn--p1ai","xn--h1aqbrw.xn--p1ai","xofferforyou.com","y0o5.com","yano-s.com","yathralanka.com","yourbonusonlinerewards.com","yourbrightsmileinfo.com","yourdeadlinenewestupdates.com","yourdeadlinenewinfo.com","yourdeadlinenewupdates.com","yourenrollmentnewupdates.com","yourenrollmentupdatedinfo.com","yourhealthupdateddeadline.com","yourlifenewonlinespecials.com","yourlifenewspecials.com","yournewestdentalcare.com","yournewestsecretinfo.com","yournewsecretinformation.com","yournewsurvivaltool.com","yourprogramlatestupdates.com","yourprogramnewupdates.com","yourrewardnewpoints.com","yourstreamingnewupdates.com","yourtravelupdatedspecials.com","yourvacationnewspecials.com","yourvacationpackageinfo.com","yourvacationpackagespecials.com","z953y-liriy.biz","zazwos.us","zdo30a9m9y.com","zl5u.com","zo2i.com","zzjiaoban.com"
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
