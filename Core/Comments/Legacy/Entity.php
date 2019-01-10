<?php

/**
 * Description
 *
 * @author emi
 */

namespace Minds\Core\Comments\Legacy;

use Minds\Core\Comments\Comment;

class Entity
{
    /**
     * Build a Comment entity using row data from legacy tables
     * @param array $row
     * @return Comment
     */
    public function build(array $row)
    {
        $attachments = [];
        $attachmentKeyMap = ['custom_type', 'custom_data', 'attachment_guid', 'title', 'blurb', 'perma_url', 'thumbnail_src' ];

        foreach ($attachmentKeyMap as $key) {
            if (isset($row[$key])) {
                $attachments[$key] = $row[$key];
            }
        }

        $comment = new Comment();
        $comment
            ->setEntityGuid($row['parent_guid'])
            ->setParentGuid(0)
            ->setGuid($row['guid'])
            ->setHasChildren(false)
            ->setOwnerGuid($row['owner_guid'])
            ->setContainerGuid($row['container_guid'])
            ->setTimeCreated($row['time_created'])
            ->setTimeUpdated($row['time_updated'])
            ->setAccessId($row['access_id'])
            ->setBody($row['description'])
            ->setAttachments($attachments)
            ->setMature(isset($row['mature']) && $row['mature'])
            ->setEdited(isset($row['edited']) && $row['edited'])
            ->setSpam(isset($row['spam']) && $row['spam'])
            ->setDeleted(isset($row['deleted']) && $row['deleted'])
            ->setOwnerObj(isset($row['owner_obj']) ? $row['owner_obj'] : (isset($row['ownerObj']) ? $row['ownerObj'] : null))
            ->setVotesUp(isset($row['thumbs:up:user_guids']) ? json_decode($row['thumbs:up:user_guids']) : [])
            ->setVotesDown(isset($row['thumbs:down:user_guids']) ? json_decode($row['thumbs:down:user_guids']) : [])
            ->setEphemeral(false)
            ->markAllAsPristine();

        return $comment;
    }
}
