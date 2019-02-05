<?php
/**
 * @author: eiennohi.
 */

namespace Minds\Controllers\Cli;

use Minds\Cli;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Interfaces;

class HelpdeskScores extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function __construct()
    {
        $minds = new Core\Minds;
        $minds->start();
    }

    public function help($command = null)
    {
        $this->out('TBD');
    }

    public function exec()
    {
        $this->out('[HelpdeskScores]:: updating scores');
        /** @var Core\Helpdesk\Question\Manager $manager */
        $manager = Di::_()->get('Helpdesk\Question\Manager');
        $offset = '';

        while (true) {
            $questions = $manager->getAll(['limit' => 2000, 'offset' => $offset]);
            $offset = $questions->getPagingToken();

            $this->out("[HelpdeskScores]:: fetched {$questions->count()} questions");

            foreach ($questions as $question) {
                $score = $question->getThumbsUp() ? count($question->getThumbsUp()) : 1;
                $question->setScore($score);
                $manager->update($question);
                $this->out("[HelpdeskScores]:: updated {$question->getUuid()} | score: {$score}");
            }

            if (!$offset || $questions->count() === 0) {
                break;
            }
        }

        $this->out('[HelpdeskScores]:: Done');
    }
}
