<?php

namespace Minds\Core\Helpdesk\Question;

use Minds\Traits\MagicAttributes;

/**
 * Class Question
 * @package Minds\Core\Helpdesk\Entities
 * @method string getUuid()
 * @method Question setUuid(string $value)
 * @method string getQuestion()
 * @method Question setQuestion(string $value)
 * @method string getAnswer()
 * @method Question setAnswer(string $value)
 * @method string getCategoryUuid()
 * @method Question setCategoryUuid(string $value)
 * @method Category getCategory()
 * @method Question setCategory()
 * @method bool getThumbUp()
 * @method Question setThumbUp(bool $value)
 * @method bool getThumbDown()
 * @method Question setThumbDown(bool $value)
 */
class Question
{
    use MagicAttributes;

    protected $uuid;
    protected $question;
    protected $answer;
    protected $category_uuid;
    /** @var Category */
    protected $category;
    protected $thumbsUp;
    protected $thumbsDown;

    public function export()
    {
        $export = [];

        $export['uuid'] = $this->getUuid();
        $export['question'] = $this->getQuestion();
        $export['answer'] = $this->getAnswer();
        $export['category_uuid'] = $this->getCategoryUuid();
        $export['category'] = $this->getCategory() ? $this->getCategory()->export() : null;
        $export['thumb_up'] = $this->getThumbUp();
        $export['thumb_down'] = $this->getThumbDown();

        return $export;
    }
}