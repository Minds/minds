<?php
namespace Minds\Core\Security;

class ReCaptcha
{

    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function setAnswer($answer)
    {
        $this->answer = $answer;
        return $this;
    }

    public function validate()
    {
        if (!isset($this->config->google['recaptcha']['secret_key']) || !$this->config->google['recaptcha']['secret_key']) {
            return true;
        }

        $data = [
            'secret' => $this->config->google['recaptcha']['secret_key'],
            'response' => $this->answer
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if ($response) {
            $data = json_decode($response, true);
            if($data['success']) {
                return true;
            }
        }
        return false;
    }

}
