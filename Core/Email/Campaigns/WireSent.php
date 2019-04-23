<?php

namespace Minds\Core\Email\Campaigns;

use Minds\Core\Email\Mailer;
use Minds\Core\Email\Message;
use Minds\Core\Email\Template;
use Minds\Core\Email\Manager;
use Minds\Core\Wire\Wire;
use Minds\Traits\MagicAttributes;
use Minds\Core\Di\Di;
use Minds\Core\Util\BigNumber;

class WireSent extends EmailCampaign
{
    use MagicAttributes;
    protected $template;
    protected $mailer;
    protected $method;
    protected $subject = 'Your Wire receipt';
    /* @var Wire */
    protected $wire;

    public function __construct(Template $template = null, Mailer $mailer = null, Manager $manager = null)
    {
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
        $this->manager = $manager ?: Di::_()->get('Email\Manager');

        $this->campaign = 'when';
        $this->topic = 'wire_received';
    }

    public function build()
    {
        $tracking = [
            '__e_ct_guid' => $this->user->getGUID(),
            'campaign' => $this->campaign,
            'topic' => $this->topic,
        ];

        $timestamp = gettype($this->wire->getTimestamp()) === 'object' ? $this->wire->getTimestamp()->time() : $this->wire->getTimestamp();
        $amount = $this->wire->getMethod() === 'tokens' ? BigNumber::fromPlain($this->wire->getAmount(), 18)->toDouble() : $this->wire->getAmount();
        $contract = $this->wire->getMethod() === 'onchain' ? 'wire' : 'offchain:wire';

        $this->template->setTemplate('default.tpl');
        $this->template->setBody('./Templates/wire-sent.tpl');
        $this->template->set('user', $this->user);
        $this->template->set('username', $this->user->username);
        $this->template->set('email', $this->user->getEmail());
        $this->template->set('guid', $this->user->getGUID());
        $this->template->set('timestamp', $timestamp);
        $this->template->set('amount', $amount);
        $this->template->set('receiver', $this->wire->getReceiver());
        $this->template->set('sender', $this->wire->getSender());
        $this->template->set('contract', $contract);
        $this->template->set('campaign', $this->campaign);
        $this->template->set('topic', $this->topic);
        $this->template->set('tracking', http_build_query($tracking));

        $message = new Message();
        $message->setTo($this->user)
            ->setMessageId(implode('-',
                [$this->user->guid, sha1($this->user->getEmail()), sha1($this->campaign.$this->topic.time())]))
            ->setSubject($this->subject)
            ->setHtml($this->template);

        return $message;
    }

    public function send()
    {
        if ($this->canSend()) {
            $this->mailer->queue($this->build());
        }
    }
}
