<?php
/**
 * Minds Translations Engine
 * @version 1
 * @author Emiliano Balbuena
 */
namespace Minds\Core\Translation;

use Minds\Core;
use Minds\Core\Data\Cassandra;

class Storage
{
    protected $prepared;

    public function __construct($db = null)
    {
        $di = Core\Di\Di::_();

        $this->db = $db ?: $di->get('Database\Cassandra\Cql');
    }

    public function get($guid, $field, $target)
    {
        $prepared = new Cassandra\Prepared\Custom();
        $prepared->query("SELECT * FROM translations WHERE guid= ? AND field= ? AND language= ? LIMIT 1",
          [ (string) $guid, $field, $target ]);

        $result = $this->db->request($prepared);

        if (!$result[0]) {
           return false;
        }
        return $result[0];
    }

    public function set($guid, $field, $target, $sourceLanguage, $content)
    {
        if (!$guid || !$target) {
            return false;
        }

        if (!$sourceLanguage) {
            $sourceLanguage = '';
        }

        $content = (string) $content;

        $prepared = new Cassandra\Prepared\Custom();
        $prepared->query("INSERT INTO translations (guid, field, language, source_language, content) VALUES (?, ?, ?, ?, ?)",
          [ (string) $guid, $field, $target, $sourceLanguage, $content ]);

        $result = (array) $this->db->request($prepared);

        return isset($result[0]) ? $result[0] : false;
    }

    public function purge($guid)
    {
        if (!$guid) {
            return false;
        }

        $prepared = new Cassandra\Prepared\Custom();
        $prepared->query("DELETE FROM translations WHERE guid= ?", [ (string) $guid ]);

        return $this->db->request($prepared);
    }
}

/*
    Schema:

    CREATE TABLE translations (
        guid varchar,
        field varchar,
        language varchar,
        source_language varchar,
        content text,
        PRIMARY KEY (guid, field, language)
    );
*/
