<?php

namespace Spec\Minds\Core\Email\Partials;

use Minds\Core\Email\Partials\SuggestedChannels;
use PhpSpec\ObjectBehavior;
use Minds\Core\Suggestions\Suggestion;
use Minds\Entities\User;
use DomDocument;

class SuggestedChannelsSpec extends ObjectBehavior
{
    private $guid = '933120961241157645';
    private $name = 'test name';
    private $briefDescription = 'test description';

    public function it_is_initializable()
    {
        $this->shouldHaveType(SuggestedChannels::class);
    }

    public function it_should_build()
    {
        $guid =
        $tracking = [
            '__e_ct_guid' => $this->guid,
            'campaign' => 'campaign',
            'topic' => 'topic',
            'state' => 'state',
        ];
        $this->setTracking(htmlentities(http_build_query($tracking)));
        $this->setSuggestions($this->mockSuggestions());
        $dom = new DomDocument();
        $dom->loadHTML($this->build()->getWrappedObject());
        $anchors = $dom->getElementsByTagName('a');
        expect(trim($anchors[1]->nodeValue))->toEqual($this->name);
        expect(trim($anchors[2]->nodeValue))->toEqual($this->briefDescription);
        expect($dom->getElementById("suggested-channel-{$this->guid}"))->shouldHaveType('DomElement');
    }

    private function mockSuggestions()
    {
        $user = new User($this->guid);
        $user['name'] = $this->name;
        $user['briefdescription'] = $this->briefDescription;

        $suggestion = (new Suggestion())
            ->setEntityType('user')
            ->setEntity($user);

        return [$suggestion];
    }
}
