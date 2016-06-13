<?php

namespace Minds\Core\Email;

class SpamFilter
{

    protected $domains = [];

    public function __construct()
    {
        $this->getList();
    }

    /**
     * Compiles the list of blacklisted domains
     * @return void
     */
    protected function getList()
    {
        if(!$this->domains){
            $domains = file(dirname(__FILE__) . '/blacklist.txt');
            foreach($domains as $domain){
                $this->domains[trim($domain)] = true;
            }
        }
    }

    /**
     * Check if an email is from a spam domain
     * @param string $email
     * @return bool
     */
    public function isSpam($email)
    {
        list(, $domain) = explode('@', $email);
        if(isset($this->domains[$domain])){
            return true;
        }
        return false;
    }

}
