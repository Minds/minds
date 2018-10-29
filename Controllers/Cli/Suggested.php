<?php
namespace Minds\Controllers\Cli;

use Minds\Core\Minds;
use Minds\Cli;
use Minds\Core\Feeds\Suggested\Manager;
use Minds\Interfaces;

class Suggested extends Cli\Controller implements Interfaces\CliControllerInterface
{
    /** @var Manager */
    private $manager;

    public function __construct()
    {
        $minds = new Minds();
        $minds->start();
        $this->manager = new Manager();
    }

    public function help($command = null)
    {
        $this->out('Syntax usage: cli trending <type>');
    }

    public function exec()
    {
        $this->out('Syntax usage: cli trending <type>');
    }

    public function sync_all()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->out('Collecting trending items');

        $this->manager->run('all');

        $this->out('Completed syncing all');
    }

    public function sync_newsfeed()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->out('Syncing newsfeed');

        $this->manager->run('newsfeed');

        $this->out('Completed syncing newsfeed');
    }

    public function sync_images()
    {
        $this->out('Syncing images');

        $this->manager->run('images');

        $this->out('Completed syncing images');
    }

    public function sync_videos()
    {
        $this->out('Syncing videos');

        $this->manager->run('videos');

        $this->out('Completed syncing videos');
    }

    public function sync_groups()
    {
        $this->out('Syncing groups');

        $this->manager->run('groups');

        $this->out('Completed syncing groups');
    }

    public function sync_blogs()
    {
        $this->out('Syncing blogs');

        $this->manager->run('blogs');

        $this->out('Completed syncing blogs');
    }

}
