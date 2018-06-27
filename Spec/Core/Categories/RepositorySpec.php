<?php

namespace Spec\Minds\Core\Categories;

use Minds\Core\Categories\Repository;
use Minds\Core\Config\Config;
use Minds\Core\Data\Cassandra\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Spec\Minds\Mocks\Cassandra\Rows;

class RepositorySpec extends ObjectBehavior
{
    protected $client;
    protected $config;

    function let(Client $client, Config $config)
    {
        $this->beConstructedWith($client, $config);

        $this->client = $client;
        $this->config = $config;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_get_the_categories()
    {
        $this->config->get('categories')
            ->shouldBeCalled()
            ->willReturn(['art' => 'art', 'music' => 'music']);
        $this->getCategories()->shouldReturn(['art', 'music']);
    }

    function it_should_set_the_categories()
    {
        $this->config->get('categories')
            ->shouldBeCalled()
            ->willReturn(['art' => 'art', 'music' => 'music', 'news' => 'news']);

        $this->setCategories(['art' => 'art', 'music' => 'music']);

        $this->getCategories()->shouldReturn(['art', 'music']);
    }

    function it_should_reset_the_categories()
    {
        $this->config->get('categories')
            ->shouldBeCalled()
            ->willReturn(['art' => 'art', 'music' => 'music', 'news' => 'news']);

        $this->setCategories(['art' => 'art', 'music' => 'music']);
        $this->getCategories()->shouldReturn(['art', 'music']);

        $this->reset();

        $this->getCategories()->shouldReturn(['art', 'music', 'news']);
    }

    function it_should_get_categories_from_the_database()
    {
        $this->config->get('categories')
            ->shouldBeCalled()
            ->willReturn(['art' => 'art', 'music' => 'music', 'news' => 'news']);

        $this->client->request(Argument::that(function ($query) {
            $built = $query->build();

            return $built['string'] === "SELECT * FROM categories
          WHERE type = ?
          AND filter = ?
          AND category IN ?
          ALLOW FILTERING";
        }))
            ->shouldBeCalled()
            ->willReturn(new Rows([
                ['guid' => 'art'],
                ['guid' => 'music'],
                ['guid' => 'programming']
            ], ''));

        $this->get()->shouldReturn(['art', 'music', 'programming']);
    }

    function it_should_save_a_new_category()
    {
        $this->config->get('categories')
            ->shouldBeCalled()
            ->willReturn(['art' => 'art', 'music' => 'music', 'news' => 'news']);

        $this->setCategories(['art' => 'art', 'music' => 'music']);

        $this->client->request(Argument::that(function ($query) {
            $built = $query->build();

            return $built['string'] === "INSERT INTO categories
              (type, category, filter, guid)
              VALUES (?, ?, ?, ?)"
                && $built['values'][0] === 'activity'
                && in_array($built['values'][1], ['art', 'music'])
                && $built['values'][2] === 'featured';
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->setType('activity');
        $this->setFilter('featured');
        $this->add('music');
    }

    function it_should_remove_a_category() {
        $this->config->get('categories')
            ->shouldBeCalled()
            ->willReturn(['art' => 'art', 'music' => 'music', 'news' => 'news']);

        $this->setCategories(['art' => 'art', 'music' => 'music']);

        $this->client->request(Argument::that(function ($query) {
            $built = $query->build();

            return $built['string'] === "DELETE FROM categories
              WHERE type = ? AND category = ? AND filter = ? AND guid = ?"
                && $built['values'][0] === 'activity'
                && in_array($built['values'][1], ['art', 'music'])
                && $built['values'][2] === 'featured';
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->setType('activity');
        $this->setFilter('featured');
        $this->remove('music');
    }
}
