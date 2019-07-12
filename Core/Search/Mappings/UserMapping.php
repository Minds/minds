<?php

/**
 * Mapping for user documents
 *
 * @author emi
 */

namespace Minds\Core\Search\Mappings;

use Minds\Exceptions\BannedException;

class UserMapping extends EntityMapping implements MappingInterface
{
    /**
     * UserMapping constructor.
     */
    public function __construct()
    {
        $this->mappings = array_merge($this->mappings, [
            'username' => [ 'type' => 'text', '$exportField' => 'username' ],
            'briefdescription' => [ 'type' => 'text', '$exportField' => 'briefdescription' ],
            'group_membership' => [ 'type' => 'text' ],
            'suggest' => [ 'type' => 'completion' ]
        ]);
    }

    /**
     * @param array $defaultValues
     * @return array
     */
    public function map(array $defaultValues = [])
    {
        $map = parent::map($defaultValues);

        //if (isset($map['tags'])) {
        //    unset($map['tags']);
        //}

        if ($this->entity->isBanned() == 'yes') {
            throw new BannedException('User is banned');
        }

        if (method_exists($this->entity, 'isMature')) {
            $map['mature'] = $this->entity->isMature();
        } else {
            $map['mature'] = false;
        }

        if (method_exists($this->entity, 'getGroupMembership')) {
            $map['group_membership'] = array_values($this->entity->getGroupMembership());
        } else {
            $map['group_membership'] = [];
        }

        $map['tags'] = array_values(array_unique($this->entity->getTags()));

        return $map;
    }

    /**
     * @param array $defaultValues
     * @return array
     */
    public function suggestMap(array $defaultValues = [])
    {
        $map = parent::suggestMap($defaultValues);

        $name = preg_replace('/[0-9]*/', '', $this->entity->name);
        $username = preg_replace('/[0-9]*/', '', $this->entity->username);

        if (!$name && !$username) {
            error_log('[es]: tried to save user without username or name ' . $this->entity->guid);
            return $map;
        }

        $inputs = [ $username, $name ];
        //split out the name based on CamelCase
        $nameParts = preg_split('/([\s])?(?=[A-Z])/', $name, -1, PREG_SPLIT_NO_EMPTY);
        $inputs = array_unique(array_merge($inputs, $this->permutateInputs($nameParts)));

        $map = array_merge($map, [
            'input' => array_values($inputs),
            'weight' => count(array_values($inputs)) == 1 ? 4 : 2
        ]);

        if ($this->entity->featured_id) {
            $map['weight'] += 50;
        }

        if ($this->entity->isAdmin()) {
            $map['weight'] += 100;
        }

        if (strlen($username) > 30) {
            $map['weight'] = 1; //spammy username
        }
        
        return $map;
    }

    //

    /**
     * @param $inputs
     * @param int $calls
     * @return array
     */
    protected function permutateInputs($inputs, $calls = 0)
    {
        if (count($inputs) <= 1 || count($inputs) >= 4 || $calls > 5) {
            return $inputs;
        }

        $result = [];
        foreach ($inputs as $key => $item) {
            foreach ($this->permutateInputs(array_diff_key($inputs, [$key => $item]), $calls++) as $p) {
                $result[] = "$item $p";
            }
        }

        return $result;
    }
}
