<?php
namespace Minds\Core\Data\Cassandra\Prepared;

use Minds\Core\Data\Interfaces;
use Cassandra\Type;

// @todo: support indexes prefixes for hosted
class MonetizationLedger implements Interfaces\PreparedInterface
{
    protected $template;
    protected $values = [];

    public function get(array $options = [])
    {
        $options = array_merge([
            'guid' => '',
            'limit' => null,
        ], $options);

        $this->template = "SELECT * from monetization_ledger ";
        $allowFiltering = false;

        if ($options['guid']) {
            $wheres = [];
            $values = [];

            if (!is_array($options['guid'])) {
                $wheres[] = 'guid = ?';
                $values[] = (string) $options['guid'];
            } else {
                $placeholders = implode(', ', array_fill(0, count($options['guid']), '?'));
                $wheres[] = "guid IN ({$placeholders})";
                $values = array_merge($values, array_map(function ($value) {
                    return (string) $value;
                }, $options['guid']));
            }

            $this->template .= " WHERE " . implode(' AND ', $wheres) . " ";
            $this->values = array_merge($this->values, $values);
        }

        if ($options['limit']) {
            $this->template .= " LIMIT ? ";
            $this->values[] = (int) $options['limit'];
        }

        return $this;
    }

    public function upsert($guid, $data)
    {
        $this->template = "UPDATE monetization_ledger SET ";
        $this->values = [];

        $sets = [];
        $values = [];
        foreach ($data as $field => $value) {
            $sets[] = "{$field} = ?";
            $values[] = $value;
        }

        $this->template .= implode(',', $sets);
        $this->values = array_merge($this->values, $values);

        $this->template .= " WHERE guid = ?";
        $this->values[] = (string) $guid;

        return $this;
    }

    public function build()
    {
        if (!$this->template) {
            throw new \Exception('Empty CQL query');
        }

        return [
            'string' => $this->template,
            'values' => $this->values
        ];
    }
}
