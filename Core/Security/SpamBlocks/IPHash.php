<?php
/**
 * SpamBlocks Manager
 */
namespace Minds\Core\Security\SpamBlocks;

class IPHash
{

    /** @var Manager $manager */
    private $manager;

    public function __construct($manager = null)
    {
        $this->manager = $manager ?: new Manager;
    }

    /**
     * Return if an IP is valid
     */
    public function isValid($ip)
    {
        $hash = hash('sha256', $ip);
        $spamBlock = new SpamBlock();
        $spamBlock->setKey('ip_hash')
            ->setValue($hash);
        return !$this->manager->isSpam($spamBlock);
    }

}
