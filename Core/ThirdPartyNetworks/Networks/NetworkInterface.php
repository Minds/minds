<?php
/**
 * Third party network interface
 */

namespace Minds\Core\ThirdPartyNetworks\Networks;

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

    /**
     * Export API information for end-user displaying
     * @return array
     */
    public function export();
}
