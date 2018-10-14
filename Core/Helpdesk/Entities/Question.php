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
 * @method Question setCategoryUuid()
 * @method Category getCategory()
 * @method Question setCategory()
 * @method int getThumbsUpCount()
 * @method Question setThumbsUpCount(int $value)
 * @method int getThumbsDownCount()
 * @method Question setThumbsDownCount(int $value)
 * @method array getUserGuids()
 * @method Question setUserGuids(array $value)
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
    protected $thumbsUpCount;
    protected $thumbsDownCount;
    /** @var array */
    protected $userGuids;
}