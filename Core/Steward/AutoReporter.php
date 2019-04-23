<?php
/**
 * Minds AutoReport.
 */

namespace Minds\Core\Steward;

use Minds\Core\Di\Di;
use Minds\Core\Config\Config;
use Minds\Core\Reports;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Reports\Jury\Decision;

const REPORT_THRESHOLD = 4;
class AutoReporter
{
    private $dictionary = [];
    /** @var Minds\Entites\User */
    private $stewardUser;
    /** @var Minds\Core\EntitiesBuilder */
    private $entitiesBuilder;
    /** @var Minds\Core\Config */
    private $config;
    /** @var Minds\Core\Reports\UserReports\Manager */
    private $reportManager;
    /** @var Minds\Core\Reports\Jury\Manager */
    private $juryManager;
    /** @var Minds\Core\Reports\Manager */
    private $moderationManager;

    public function __construct(
        Config $config = null,
        EntitiesBuilder $entitiesBuilder = null,
        Reports\UserReports\Manager $reportManager = null,
        Reports\Jury\Manager $juryManager = null,
        Reports\Manager $moderationManager = null
    ) {
        $this->config = $config ?: Di::_()->get('Config');
        $this->entitiesBuilder = $entitiesBuilder ?: Di::_()->get('EntitiesBuilder');
        $this->reportManager = $reportManager ?: Di::_()->get('Moderation\Reports\Manager');
        $this->juryManager = $juryManager ?: Di::_()->get('Moderation\Jury\Manager');
        $this->moderationManager = $moderationManager ?: Di::_()->get('Moderation\Manager');

        //Fun static mappings begin here. I'm so sorry, world.
        //On the plus side, our developers' greps got a lot more interesting...
        $this->dictionary['adult'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['amateur'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['anal'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['asian'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['ass'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_NUDITY, 1);
        $this->dictionary['babe'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['banislam'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_RACE, 5);
        $this->dictionary['bdsm'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 5);
        $this->dictionary['beastiality'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 5);
        $this->dictionary['beauty'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['bendover'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['bigboobs'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_NUDITY, 1);
        $this->dictionary['blowjob'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 5);
        $this->dictionary['bondage'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['bukkake'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 5);
        $this->dictionary['boobs'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_NUDITY, 1);
        $this->dictionary['breeding'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['bukkake'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 5);
        $this->dictionary['butt'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_NUDITY, 1);
        $this->dictionary['buttplug'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['camgirls'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['christchurch'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_RACE, 5);
        $this->dictionary['clit'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_NUDITY, 1);
        $this->dictionary['cock'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_NUDITY, 5);
        $this->dictionary['creampie'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['cuck'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['cuckold'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['cum'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['dead'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_VIOLENCE, 3);
        $this->dictionary['deepthroat'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['dfc'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['dick'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_NUDITY, 1);
        $this->dictionary['dom'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['ebony'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['ecchi'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['erotica'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['faggot'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PROFANITY, 10);
        $this->dictionary['fet'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['fetish'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['fetlife'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['footfetish'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['gang'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['gangbang'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['gay'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['girls'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['goy'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_RACE, 1);
        $this->dictionary['goyim'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_RACE, 1);
        $this->dictionary['hardcore'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['heels'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['hentai'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['hitler'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_RACE, 3);
        $this->dictionary['holocaust'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_RACE, 3);
        $this->dictionary['hot'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['jew'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_RACE, 1);
        $this->dictionary['kike'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_RACE, 10);
        $this->dictionary['kill'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_VIOLENCE, 3);
        $this->dictionary['killing'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_VIOLENCE, 3);
        $this->dictionary['kink'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['kinkster'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['kinky'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['latex'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['lesbian'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['lewd'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['lily'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['lingerie'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['loli'] = new Reason(REASON::REASON_NSFW, REASON::REASON_ILLEGAL_PAEDOPHILIA, 10);
        $this->dictionary['megu'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_RACE, 10);
        $this->dictionary['milf'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['mistress'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['murder'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_VIOLENCE, 1);
        $this->dictionary['muslim'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_RACE, 1);
        $this->dictionary['nazi'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_VIOLENCE, 1);
        $this->dictionary['nigger'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_RACE, 10);
        $this->dictionary['nsfw'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_OTHER, 10);
        $this->dictionary['nude'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_NUDITY, 1);
        $this->dictionary['nudist'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_NUDITY, 1);
        $this->dictionary['nudity'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_NUDITY, 1);
        $this->dictionary['nylon'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['oppai'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['pantyhose'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['penis'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_NUDITY, 3);
        $this->dictionary['porn'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 10);
        $this->dictionary['pornstar'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['pussy'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_NUDITY, 1);
        $this->dictionary['racist'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_RACE, 1);
        $this->dictionary['sex'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['sextoy'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['slut'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PROFANITY, 3);
        $this->dictionary['spanking'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['sub'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['submissive'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['thicc'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_NUDITY, 1);
        $this->dictionary['thot'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_NUDITY, 1);
        $this->dictionary['threesome'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['tits'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_NUDITY, 1);
        $this->dictionary['titties'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_NUDITY, 1);
        $this->dictionary['unicorn'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 1);
        $this->dictionary['vagina'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_NUDITY, 1);
        $this->dictionary['waifu'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['webcam'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['whore'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PROFANITY, 5);
        $this->dictionary['woa'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 3);
        $this->dictionary['xxx'] = new Reason(REASON::REASON_NSFW, REASON::REASON_NSFW_PORNOGRAPHY, 10);
    }

    /**
     * Examines the text content of an entity and reports if the post contains problematic words
     * The words are arbitrarily weighted and should be replaced by a cached lookup of statistical analysis.
     */
    public function validate($entity)
    {
        $reasons = array();
        //Build up a list of reasons to flag unique words in the post
        if (isset($entity['message'])) {
            error_log('message');
            $this->evaluateText($entity['message'], $reasons);
        }
        //Remove reasons that the user has already tagged
        if (isset($entity['nsfw'])) {
            $this->filterReasonsByNSFWTags($reasons, $entity['nsfw']);
        }
        //If we have reasons, score them and pick the top one that crosses our threshold
        if (count($reasons) > 0) {
            $scorer = new ReasonScorer($reasons);
            $scoredReason = $scorer->score();
            if ($scoredReason && $scoredReason->getWeight() > REPORT_THRESHOLD) {
                $this->report($entity, $scoredReason);
                $this->cast($entity);
            }
        }
    }

    public function cast($entity)
    {
        $report = $this->moderationManager->getReport($entity->guid);
        $stewardUser = $this->entitiesBuilder->single($this->config->get('steward_guid'));

        $decision = new Decision();
        $decision
            ->setAppeal(null)
            ->setAction('uphold')
            ->setReport($report)
            ->setTimestamp(round(microtime(true) * 1000))
            ->setJurorGuid($stewardUser->getGuid())
            ->setJurorHash($stewardUser->getPhoneNumberHash());

        $this->juryManager->cast($decision);
    }

    public function report($entity, $reason)
    {
        $stewardUser = $this->entitiesBuilder->single($this->config->get('steward_guid'));
        $report = new Reports\Report();
        $report->setEntityGuid($entity->guid)
            ->setEntityOwnerGuid($entity->getOwnerGuid());

        $autoReport = new Reports\UserReports\UserReport();
        $autoReport
            ->setReport($report)
            ->setReporterGuid($stewardUser->guid)
            ->setReasonCode((int) $reason->getReasonCode())
            ->setSubReasonCode($reason->getSubreasonCode())
            ->setTimestamp(round(microtime(true) * 1000));
        $this->reportManager->add($autoReport);
    }

    protected function evaluateText($text, &$reasons)
    {
        $words = $this->getUniqueWords($text);
        foreach ($words as $word) {
            $this->evaluateWord($word, $reasons);
        }
    }

    protected function evaluateWord($word, &$reasons)
    {
        if (isset($this->dictionary[$word])) {
            $reasons[] = $this->dictionary[$word];
        }
    }

    protected function filterReasonsByNSFWTags(&$reasons, $NSFWtags)
    {
        $reasons = array_filter($reasons, function ($reason) use ($NSFWtags) {
            if ($reason->getReasonCode() == REASON::REASON_NSFW
            && in_array($reason->getSubreasonCode(), $NSFWtags)) {
                return false;
            }

            return true;
        });
    }

    protected function getUniqueWords($text)
    {
        $text = preg_replace('/[^a-z\d ]/i', '', $text);

        return array_unique(explode(' ', strtolower($text)));
    }
}
