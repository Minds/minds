<?php

namespace Spec\Minds\Core\Faq;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Faq\Answer;
use Minds\Core\Faq\Question;

class CategorySpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Faq\Category');
    }

    function it_should_set_category()
    {
        $this->setCategory('id')->shouldReturn($this);
        $this->getCategory()->shouldBe('id');
    }

    function it_should_set_question(Question $question)
    {
        $this->setQuestion($question)->shouldReturn($this);
        $this->getQuestions()->shouldHaveCount(1);
        $this->getQuestions()->shouldHaveKeyWithValue(0, $question);
    }

    function it_should_set_multiple_questions(Question $question, Question $question2)
    {
        $this->setQuestion($question)->shouldReturn($this);
        $this->setQuestion($question2)->shouldReturn($this);
        $this->getQuestions()->shouldHaveCount(2);
        $this->getQuestions()->shouldHaveKeyWithValue(0, $question);
        $this->getQuestions()->shouldHaveKeyWithValue(1, $question2);        
    }

}
