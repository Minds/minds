<?php
/**
 * FAQ Answer
 */
namespace Minds\Core\Faq;

class Answer implements \JsonSerializable
{
    protected $answer = '';
    protected $question;

    public function setQuestion(Question $question) : Answer
    {
        $this->question = $question;
        return $this;
    }

    public function getQuestion() : Question
    {
        return $this->question;
    }

    public function setAnswer(string $answer) : Answer
    {
        $this->answer = $answer;
        return $this;
    }

    public function getAnswer() : string
    {
        return $this->answer;
    }

    public function jsonSerialize() : String
    {
        return $this->getAnswer();
    }
    
}