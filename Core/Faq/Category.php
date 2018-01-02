<?php
/**
 * FAQ Category
 */
namespace Minds\Core\Faq;

class Category implements \JsonSerializable
{

    protected $category;
    protected $questions;

    public function setCategory(string $category)
    {
        $this->category = $category;
        return $this;
    }

    public function getCategory() : string
    {
        return $this->category;
    }

    public function setQuestion(Question $question)
    {
        $this->questions[] = $question;
        return $this;
    }

    public function getQuestions() : Array
    {
        return $this->questions;
    }

    public function jsonSerialize() : Array
    {
        return [
            'category' => $this->getCategory(),
            'questions' => $this->getQuestions()
        ];
    }
    
}