<?php
/**
 * Third party network interface
 */

namespace Minds\Core\ThirdPartyNetworks;

interface NetworkInterface
{

    /**
     * Set and save the api credentials
     * @param array $credentials
     * @return $this
     */
    public function setApiCredentials($credentials = []);

    /**
     * Return api credentials
     * @return array
     */
    public function getApiCredentials();

    /**
     * Create a post
     * @param object $entity
     * @return $this
     */
    public function post($entity);

    /**
     * Schedule a post
     * @param int $timestamp
     * @return $this
     */
    public function schedule($timestamp);
}
