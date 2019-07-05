<?php

namespace Minds\Controllers\Cli;

use Minds\Cli;
use Minds\Core\Channels\Ban as ChannelsBanManager;
use Minds\Core\Channels\Manager as ChannelsManager;
use Minds\Core\Channels\Snapshots\Manager;
use Minds\Core\Channels\Snapshots\Snapshot as SnapshotEntity;
use Minds\Core\Di\Di;
use Minds\Core\Security\ACL;
use Minds\Entities\User;
use Minds\Exceptions\CliException;
use Minds\Interfaces;

class Snapshot extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function __construct()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    public function help($command = null)
    {
        $this->out([
            'Syntax usage:',
            '- cli snapshot [--and-ban] <user>',
            '- cli snapshot restore <user GUID>',
            '- cli snapshot dump <user GUID>',
        ]);
    }

    public function exec()
    {
        $shouldBan = $this->getOpt('and-ban');

        if ($shouldBan) {
            $this->gatekeeper('This is a destructive operation.');
        }

        ACL::$ignore = true;

        $userIdentifier = $this->args[0] ?? null;

        if (!$userIdentifier) {
            return $this->help();
        }

        $user = new User($userIdentifier);

        if (!$user || !$user->guid) {
            throw new CliException('User not found');
        }

        $this->out("Creating snapshot for user [{$user->guid}]...");

        //

        /** @var ChannelsManager $channelsManager */
        $channelsManager = Di::_()->get('Channels\Manager');
        $channelsManager->setUser($user);

        /** @var ChannelsBanManager $banManager */
        $banManager = Di::_()->get('Channels\Ban');
        $banManager->setUser($user);

        //

        $snapshotCreated = $channelsManager->snapshot();

        if (!$snapshotCreated) {
            throw new CliException('Error creating user snapshot.');
        }

        if ($shouldBan) {
            $this->out('Created user snapshot! Deleting...');

            $banned = $banManager->ban('Admin issued');

            if (!$banned) {
                throw new CliException('Error banning user.');
            }

            $cleanup = $banManager->banCleanup();

            if (!$cleanup) {
                throw new CliException('Error cleaning banned user\'s artifacts.');
            }

            $this->out('Banned!');
        } else {
            $this->out('Created user snapshot!');
        }

        return true;
    }

    public function restore()
    {
        $this->gatekeeper('This is a destructive operation.');

        ACL::$ignore = true;

        $userGuid = $this->args[0] ?? null;

        if (!$userGuid) {
            return $this->help();
        }

        /** @var ChannelsManager $channelsManager */
        $channelsManager = Di::_()->get('Channels\Manager');
        $snapshotRestored = $channelsManager->restore($userGuid);

        if ($snapshotRestored) {
            $this->out('Restored user snapshot!');
        } else {
            throw new CliException('Error restoring user snapshot.');
        }

        return true;
    }

    public function dump()
    {
        $userGuid = $this->args[0] ?? null;

        if (!$userGuid) {
            return $this->help();
        }

        $manager = new Manager();
        $manager->setUserGuid($userGuid);

        /** @var SnapshotEntity $snapshot */
        foreach ($manager->getAll() as $snapshot) {
            $this->out([
                'Type: ' . $snapshot->getType(),
                'Key : ' . implode(' -> ', explode("\t", $snapshot->getKey())),
                'JSON: ' . $snapshot->getJsonData(true),
            ]);

            $this->out(['', '---', '']);
        }
    }
}
