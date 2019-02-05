<?php
/**
 * @author: eiennohi.
 */

namespace Minds\Controllers\Cli\Migrations;

use Minds\Cli;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Interfaces;

class HelpdeskScores extends Cli\Controller implements Interfaces\CliControllerInterface
{
    /** @var \PDO */
    private $db;

    private $dry = false;

    public function __construct()
    {
        $minds = new Core\Minds;
        $minds->start();

        $this->db = Di::_()->get('Database\PDO');
    }

    public function help($command = null)
    {
        $this->out('TBD');
    }

    public function exec()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->dry = !!$this->getOpt('dry');

        $this->migrateCategories();
        $this->migrateQuestions();

        $this->out('[Migrations/HelpDesk]:: Done');
    }

    private function migrateCategories()
    {
        $this->out('[Migrations/HelpDesk]:: migrating categories');

        /** @var Core\Helpdesk\Category\Manager $manager */
        $manager = Di::_()->get('Helpdesk\Category\Manager');
        $offset = 0;

        while (true) {
            $query = "SELECT * FROM helpdesk_categories LIMIT 2000 OFFSET {$offset}";

            $statement = $this->db->prepare($query);
            $statement->execute();

            $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

            $offset += 2000;

            foreach ($data as $row) {
                if (!$this->dry) {
                    $category = new Core\Helpdesk\Category\Category();
                    $category->setUuid($row['uuid'])
                        ->setBranch($row['branch'])
                        ->setParentUuid($row['parent'])
                        ->setTitle($row['title']);

                    $manager->add($category);
                }

                $this->out("[Migrations/HelpDesk]:: successfully migrated {$row['uuid']}");
            }

            if (!$offset || count($data) === 0) {
                break;
            }
        }

        $this->out("[Migrations/HelpDesk]:: done migrating categories");
    }

    private function migrateQuestions()
    {
        $this->out('[Migrations/HelpDesk]:: migrating questions');

        /** @var Core\Helpdesk\Question\Manager $manager */
        $manager = Di::_()->get('Helpdesk\Question\Manager');

        $offset = 0;

        while (true) {
            $query = "SELECT * FROM helpdesk_faq LIMIT 2000 OFFSET {$offset}";

            $statement = $this->db->prepare($query);
            $statement->execute();

            $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

            $offset += 2000;

            foreach ($data as $row) {
                if (!$this->dry) {
                    $question = new Core\Helpdesk\Question\Question();
                    $question->setUuid($row['uuid'])
                        ->setQuestion($row['question'])
                        ->setAnswer($row['answer'])
                        ->setCategoryUuid($row['category_uuid'])
                        ->setThumbsUp($this->getVotes($row['uuid'], 'up'))
                        ->setThumbsDown($this->getVotes($row['uuid'], 'down'));

                    $question->setScore($question->getThumbsUp() ? count($question->getThumbsUp()) : 1);

                    $manager->add($question);
                }

                $this->out("[Migrations/HelpDesk]:: successfully migrated {$row['uuid']}");
            }

            if (!$offset || count($data) === 0) {
                break;
            }
        }

        $this->out("[Migrations/HelpDesk]:: done migrating questions");
    }

    private function getVotes($question_uuid, $direction = 'up')
    {
        if ($direction != 'up' && $direction != 'down') {
            throw new \Exception('invalid direction');
        }

        $query = "SELECT count(*) AS votes FROM helpdesk_votes WHERE question_uuid = ? AND direction = ?";

        $statement = $this->db->prepare($query);
        $statement->execute([$question_uuid, $direction]);

        return $statement->fetch(\PDO::FETCH_ASSOC)['votes'];
    }
}
