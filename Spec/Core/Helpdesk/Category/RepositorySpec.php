<?php

namespace Spec\Minds\Core\Helpdesk\Category;

use Minds\Core\Helpdesk\Category\Repository;
use Minds\Core\Helpdesk\Category\Category;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    private $db;

    function let(\PDO $db)
    {
        $this->db = $db;

        $this->beConstructedWith($db);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_get_by_uuid(\PDOStatement $statement)
    {
        $this->db->prepare(Argument::any())
            ->shouldBeCalled()
            ->willReturn($statement);

        $statement->execute(['uuid1'])
            ->shouldBeCalled();

        $statement->fetch(\PDO::FETCH_ASSOC)
            ->shouldBeCalled()
            ->willReturn([
                'uuid' => 'uuid1',
                'title' => 'title',
                'parent' => null,
                'branch' => 'uuid1'
            ]);

        $this->get('uuid1')
            ->shouldBeAnInstanceOf(Category::class);
    }

    function it_should_add(\PDOStatement $statement, Category $category)
    {
        $category->getParentUuid()
            ->shouldBeCalled()
            ->willReturn(null);

        $category->getTitle()
            ->shouldBeCalled()
            ->willReturn('title');

        $this->db->prepare(Argument::any())
            ->shouldBeCalled()
            ->willReturn($statement);

        $statement->execute(Argument::any())
            ->shouldBeCalled()
            ->willReturn(true);

        $this->add($category)->shouldBeString();
    }
}
