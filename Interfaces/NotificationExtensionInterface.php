<?php
namespace Minds\Interfaces;

/**
 * Interface for Notification Extensions
 */
interface NotificationExtensionInterface
{
    /**
     * Sends data to the queue
     * @param  array  $notification
     * @return mixed
     */
    public function queue(array $notification = []);

    /**
     * Efectively sends a notification to an entity
     * @param  array  $notification
     * @return boolean
     */
    public function send(array $notification = []);

    /**
     * Runs the queue
     * @return mixed
     */
    public function run();
}
