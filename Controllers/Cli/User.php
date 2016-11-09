<?php

namespace Minds\Controllers\Cli;

use Minds\Core;
use Minds\Cli;
use Minds\Interfaces;
use Minds\Exceptions;
use Minds\Entities;

class User extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function __construct()
    {
    }

    public function help($command = null)
    {
        $this->out('TBD');
    }
    
    public function exec()
    {
        $this->out('Missing subcommand');
    }

    public function set_feature_flags()
    {
        if (!$this->args) {
            throw new Exceptions\CliException('Missing users');
        }

        $username = array_shift($this->args);
        $features = $this->args;

        $user = new Entities\User($username);

        if (!$user || !$user->guid) {
            throw new Exceptions\CliException('User not found');
        }

        // TODO: Logout all sessions

        $user->setFeatureFlags($features);
        $user->save();

        if (!$features) {
            $this->out("Removed all feature flags for {$user->username}");
        } else {
            $this->out("Set feature flags for {$user->username}: " . implode(', ', $features));
        }
    }
}
