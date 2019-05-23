<?php
namespace Minds\Core\Reports\Appeals;

class NotAppealableException extends \Exception
{
    protected $message = "This report is not able to be appealed";
}
