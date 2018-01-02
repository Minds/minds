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

    public function getCategory()
    {
        return $this->category;
    }

    public function setQuestion(Question $question)
    {
        $this->questions[] = $question;
        return $this;
    }

    public function getQuestions()
    {
        return $this->questions;
    }

    public function jsonSerialize()
    {
        return [
            'category' => $this->getCategory(),
            'questions' => $this->getQuestions()
        ];
    }
    
}
