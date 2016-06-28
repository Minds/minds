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

    public function get($guid, $target)
    {
        $prepared = new Cassandra\Prepared\Custom();
        $prepared->query("SELECT * FROM translations WHERE guid=:guid AND language=:language LIMIT 1", [
            'guid' => (string) $guid,
            'language' => $target
        ]);

        $result = (array) $this->db->request($prepared);

        if (!$result) {
            return false;
        }

        return $result[0];
    }

    public function set($guid, $target, $sourceLanguage, $content)
    {
        if (!$guid || !$target) {
            return false;
        }

        if (!$sourceLanguage) {
            $sourceLanguage = '';
        }

        $content = (string) $content;

        $prepared = new Cassandra\Prepared\Custom();
        $prepared->query("INSERT INTO translations (guid, language, source_language, content) VALUES (:guid, :language, :sourcelanguage, :content)", [
            'guid' => (string) $guid,
            'language' => $target,
            'sourcelanguage' => $sourceLanguage,
            'content' => $content,
        ]);

        $result = (array) $this->db->request($prepared);

        return isset($result[0]) ? $result[0] : false;
    }
}

/*
    Schema:

    CREATE TABLE translations (
        guid varchar,
        language varchar,
        source_language varchar,
        content text,
        PRIMARY KEY (guid, language)
    );
*/
