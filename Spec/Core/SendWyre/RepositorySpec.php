<?php

namespace Spec\Minds\Core\SendWyre;

use Cassandra\Varint;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\SendWyre\SendWyreAccount;
use Minds\Core\SendWyre\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Spec\Minds\Mocks\Cassandra\Rows;

class RepositorySpec extends ObjectBehavior
{
    protected $db;

    public function let(Client $db)
    {
        $this->db = $db;
        $this->beConstructedWith($db);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    public function it_should_get_an_account()
    {
        $userGuid = new VarInt(123);
        $testSendWyreAccount = (new SendWyreAccount())
            ->setUserGuid($userGuid)
            ->setSendWyreAccountId('sendwyre');
        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();

            return $built['string'] === 'SELECT * FROM sendwyre_accounts WHERE user_guid = ?';
        }))
                ->shouldBeCalled()
                ->willReturn(new Rows([
                        [
                            'user_guid' => new Varint(123),
                            'sendwyre_account_id' => 'sendwyre',
                        ],
                    ], ''));

        $this->get($userGuid)->shouldBeLike($testSendWyreAccount);
    }

    public function it_should_throw_if_calling_add_without_user_guid()
    {
        $this->shouldThrow(new \Exception('user_guid is required'))->duringSave(new SendWyreAccount());
    }

    public function it_should_throw_if_calling_add_without_an_account_id()
    {
        $model = new SendWyreAccount();
        $model->setUserGuid(123);

        $this->shouldThrow(new \Exception('sendwyre_account_id is required'))->duringSave($model);
    }

    public function it_should_save_a_new_sendwyre_account()
    {
        $model = new SendWyreAccount();
        $model->setUserGuid(123)
            ->setSendWyreAccountId('123');

        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();

            return $built['string'] === 'INSERT INTO sendwyre_accounts (user_guid, sendwyre_account_id) VALUES (?, ?)';
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->save($model)->shouldReturn(true);
    }

    public function it_should_delete_a_sendwyre_account()
    {
        $model = (new SendWyreAccount())
            ->setUserGuid(123);

        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();
            var_dump($built['string']);

            return $built['string'] === 'DELETE FROM sendwyre_accounts WHERE user_guid = ?';
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->delete($model)->shouldReturn(true);
    }

    public function it_should_throw_if_calling_delete_without_user_guid()
    {
        $this->shouldThrow(new \Exception('user_guid is required'))->duringDelete(new SendWyreAccount());
    }
}
