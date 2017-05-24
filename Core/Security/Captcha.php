<?php
namespace Minds\Core\Security;

use Minds\Core\Security\Captcha\QuestionsInterface;

class Captcha
{

    private $config;
    private $secret = "";

    private $questions = [];

    public function __construct($config)
    {
        $this->config = $config;
        $this->init();
    }

    private function init()
    {
        $this->addQuestions(new Captcha\SimpleSums($this->secret));
        //$this->addQuestions(new Captcha\SimpleFruits);
    }

    /**
     * Add questions
     * @param QuestionsInterface $questions
     * @return $this
     */
    private function addQuestions($questions)
    {
        $this->questions[] = $questions;
        return $this;
    }

    public function getQuestion()
    {
        $questions = $this->questions[rand(0, count($this->questions)-1)];
        return $questions->getQuestion();
    }

    public function validateAnswer($payload)
    {
        $answer = json_decode($payload, true);
        $sum = $this->questions[0]; //refactor when we add more questions
        return $sum->validateAnswer($answer['question'], $answer['answer'], $answer['nonce'], $answer['hash']);
    }


}
