<?php
/**
 * FAQ Answer
 */
namespace Minds\Core\Faq;

class Answer implements \JsonSerializable
{
    protected $answer = '';
    protected $question;

    public function setQuestion(Question $question)
    {
        $this->question = $question;
        return $this;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function setAnswer(string $answer)
    {
        $this->answer = $answer;
        return $this;
    }

    public function getAnswer()
    {
        return $this->answer;
    }

    public function jsonSerialize()
    {
        return $this->getAnswer();
    }
    
}
