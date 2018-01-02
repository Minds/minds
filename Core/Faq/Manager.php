<?php
/**
 * Reads the faq from the CSV
 */
namespace Minds\Core\Faq;

class Manager
{

    protected $categories = [];

    public function __construct($csv = null)
    {
        $this->csv = $csv ?: dirname(__FILE__) . '/faq.csv';
    }

    protected function build()
    {
        $fo = fopen($this->csv, "r");
        $row = 0;
        while (($data = fgetcsv($fo, 10000, ",")) !== FALSE) {
            if ($row++ <= 1) {
                continue;
            }

            $id = strtolower($data[1]);
            if (!$id) {
                continue;
            }

            $question = new Question();
            $question->setQuestion($data[2]);
            $answer = new Answer();
            $answer->setAnswer($data[3]);

            $question->setAnswer($answer);
            $answer->setQuestion($question);

            $this->categories[$id] = $category = CategoryFactory::_($id);
            $category->setQuestion($question);
        }
        return true;
    }

    public function get() {
        $this->build();
        return $this->categories;
    }

}