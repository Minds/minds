<?php

namespace Minds\Core\Helpdesk\Entities;

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
 * @method int getThumbsUpCount()
 * @method Question setThumbsUpCount(int $value)
 * @method int getThumbsDownCount()
 * @method Question setThumbsDownCount(int $value)
 * @method array getThumbsUpUserGuids()
 * @method Question setThumbsUpUserGuids(array $value)
 * @method array getThumbsDownUserGuids()
 * @method Question setThumbsDownUserGuids(array $value)
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
        $export['thumbUp'] = $this->getThumbUp();
        $export['thumbDown'] = $this->getThumbDown();

        return $export;
    }
}