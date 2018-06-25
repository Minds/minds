<?php

/**
 * Minds Blog Legacy Entity
 *
 * @author emi
 */

namespace Minds\Core\Blogs\Legacy;

use Minds\Core\Blogs\Blog;
use Minds\Helpers\Counters;

class Entity
{
    static $attributeMap = [
        'type' => 'type',
        'subtype' => 'subtype',
        'guid' => 'guid',
        'ownerGuid' => 'owner_guid',
        'containerGuid' => 'container_guid',
        'accessId' => 'access_id',
        'title' => 'title',
        'body' => 'description',
        'excerpt' => 'excerpt',
        'slug' => 'slug',
        'permaUrl' => 'perma_url',
        'hasHeaderBg' => 'header_bg',
        'headerTop' => 'header_top',
        'timeCreated' => 'time_created',
        'timeUpdated' => 'time_updated',
        'lastUpdated' => 'last_updated',
        'status' => 'status',
        'published' => 'published',
        'monetized' => 'monetized',
        'license' => 'license',
        'timePublished' => 'time_published',
        'categories' => 'categories',
        'customMeta' => 'custom_meta',
        'rating' => 'rating',
        'draftAccessId' => 'draft_access_id',
        'lastSave' => 'last_save',
        'wireThreshold' => 'wire_threshold',
        'paywall' => 'paywall',
        'mature' => 'mature',
        'spam' => 'spam',
        'deleted' => 'deleted',
        'boostRejectionReason' => 'boost_rejection_reason',
        'ownerObj' => 'ownerObj',
    ];

    static $jsonEncodedFields = [
        'categories',
        'custom_meta',
        'wire_threshold',
        'ownerObj',
    ];

    static $boolFields = [
        // 'published' is a special case
        'mature',
        'spam',
        'deleted',
        'header_bg',
        'monetized',
        'paywall',
    ];

    /**
     * Created a Blog instance based on an array. Used by Entities Factory
     * and this class.
     * @param array $data
     * @return Blog
     */
    public function build($data)
    {
        $blog = new Blog();

        if (!$data) {
            return $blog;
        } elseif ($data instanceof \stdClass) {
            $data = (array) $data;
        }

        foreach (static::$attributeMap as $attribute => $column) {
            if (isset($data[$column])) {
                $setter = 'set' . ucfirst($attribute);
                $value = $data[$column];

                if (in_array($column, static::$jsonEncodedFields) && is_string($value)) {
                    $value = json_decode($value, true);
                } elseif (in_array($column, static::$boolFields)) {
                    $value = !!$value;
                } elseif ($column == 'published') {
                    $value = $value === '' || !!$value;
                }

                $blog->$setter($value);
            }
        }

        if (isset($data['thumbs:up:user_guids'])) {
            $user_guids = $data['thumbs:up:user_guids'] ?: [];

            if (is_string($user_guids)) {
                $user_guids = json_decode($user_guids, true);
            }

            $blog->setVotesUp($user_guids);
        }

        if (isset($data['thumbs:down:user_guids'])) {
            $user_guids = $data['thumbs:down:user_guids'] ?: [];

            if (is_string($user_guids)) {
                $user_guids = json_decode($user_guids, true);
            }

            $blog->setVotesDown($user_guids);
        }

        $blog->markAllAsPristine();

        return $blog;
    }

    public function exportCounters(Blog $blog)
    {
        $output = [];

        $output['thumbs:up:count'] = Counters::get($blog->getGuid(), 'thumbs:up');
        $output['thumbs:down:count'] = Counters::get($blog->getGuid(), 'thumbs:down');
        $output['reminds'] = Counters::get($blog->getGuid(), 'remind');

        return $output;
    }
}
