<?php
/**
 * Simple Sums
 */

 namespace Minds\Core\Security\Captcha;

class SimpleSums implements QuestionsInterface
{

    private $secret = "";

    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    public function getQuestion()
    {

        $a = rand(1,6);
        $operator = '+';
        $b = rand(1,6);

        $question = "$a $operator $b";
        $answer = eval("return $a $operator $b;");
        $nonce = time();

        return [
          'type' => 'sum',
          'question' => [ $a, $operator, $b ],
          'nonce' => $nonce,
          'hash' => hash('sha512', "$this->secret-$nonce-$question-$answer")
        ];
    }

    public function validateAnswer($question, $answer, $nonce, $hash)
    {
        if (!is_array($question)) {
            return false;
        }

        if ($nonce < time() - (60*5)) { //if the nonce is less than 5 minutes
            return false;
        }

        $a = $question[0];
        $operator = $question[1];
        $b = $question[2];

        if (eval("return $a $operator $b;") != $answer) {
            return false;
        }

        if (hash('sha512', "$this->secret-$nonce-$a $operator $b-$answer") == $hash) {
            return true;
        }

        return false;
    }

}
