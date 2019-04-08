<?php

namespace Minds\Core\Email\Partials;

use Minds\Core\Email\Template;
use Minds\Traits\MagicAttributes;

class SuggestedChannels extends Template
{
    use MagicAttributes;

    protected $tracking;
    protected $suggestions;

    public function build()
    {
        $this->loadFromFile = false;
        $this->setTemplate('./Templates/SuggestedChannels.tpl');
        $this->set('tracking', $this->tracking);
        $this->set('suggestions', $this->suggestions);
        if ($this->suggestions) {
            return $this->render();
        }
    }
}
