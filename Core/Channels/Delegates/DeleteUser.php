<?php
/**
 * Delete Delegate
 */
namespace Minds\Core\Channels\Delegates;

use Minds\Core\Data\Call;

class DeleteUser
{
    /** @var Call $indexes */
    private $indexes;

    /** @var Call $entities */
    private $entities;

    /** @var Call $lookup */
    private $lookup;

    public function __construct(
        $indexes = null,
        $entities = null,
        $lookup = null
    )
    {
        $this->indexes = $indexes ?: new Call('entities_by_time');
        $this->entities = $entities ?: new Call('entities');
        $this->lookup = $lookup ?: new Call('user_index_to_guid');
    }

    /**
     * Delete a channel
     * @param User $user
     * @return void
     */
    public function delete($user)
    {
        $this->entities->removeRow($user->guid);
        $this->indexes->removeAttributes('user', [ $user->guid ]);
        $this->lookup->removeRow($user->username);
    }

}
