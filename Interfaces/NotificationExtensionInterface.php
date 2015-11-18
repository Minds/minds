<?php
namespace Minds\Interfaces;

/**
 * Interface for Notification Extensions
 */
interface NotificationExtensionInterface
{
    public function queue(array $notification = []);

    public function send(array $notification = []);

    public function run();
}
