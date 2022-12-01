CREATE TABLE IF NOT EXISTS `friends` (
  `user_guid` bigint NOT NULL,
  `friend_guid` bigint NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_guid`,`friend_guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nostr_events` (
  `id` varchar(64) NOT NULL,
  `pubkey` varchar(64) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `kind` int DEFAULT NULL,
  `tags` text,
  `e_ref` varchar(64) DEFAULT NULL,
  `p_ref` varchar(64) DEFAULT NULL,
  `content` text,
  `sig` varchar(128) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`,`pubkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nostr_kind_1_to_activity_guid` (
  `id` varchar(64) NOT NULL,
  `activity_guid` bigint NOT NULL,
  `owner_guid` bigint DEFAULT NULL,
  `is_external` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`,`activity_guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nostr_mentions` (
  `id` varchar(64) NOT NULL,
  `pubkey` varchar(64) NOT NULL,
  PRIMARY KEY (`id`,`pubkey`),
  CONSTRAINT `nostr_mentions_ibfk_1` FOREIGN KEY (`id`) REFERENCES `nostr_events` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nostr_nip26_tokens` (
  `delegate_pubkey` varchar(64) NOT NULL,
  `delegator_pubkey` varchar(64) DEFAULT NULL,
  `conditions_query_string` text,
  `sig` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`delegate_pubkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nostr_pubkey_whitelist` (
  `pubkey` varchar(64) NOT NULL,
  PRIMARY KEY (`pubkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nostr_replies` (
  `id` varchar(64) NOT NULL,
  `event_id` varchar(64) NOT NULL,
  `relay_url` text,
  `marker` text,
  PRIMARY KEY (`id`,`event_id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `nostr_replies_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `nostr_events` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nostr_users` (
  `pubkey` varchar(64) NOT NULL,
  `user_guid` bigint DEFAULT NULL,
  `is_external` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`pubkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pseudo_seen_entities` (
  `pseudo_id` varchar(128) NOT NULL,
  `entity_guid` bigint NOT NULL,
  `last_seen_timestamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pseudo_id`,`entity_guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `recommendations_clustered_recs` (
  `entity_guid` bigint DEFAULT NULL,
  `entity_owner_guid` bigint DEFAULT NULL,
  `cluster_id` int DEFAULT NULL,
  `score` float DEFAULT NULL,
  `total_views` int DEFAULT NULL,
  `total_engagement` int DEFAULT NULL,
  `first_engaged` datetime DEFAULT NULL,
  `last_engaged` datetime DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  `time_created` datetime DEFAULT NULL,
  KEY `idx_cluster_id` (`cluster_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `recommendations_clustered_recs_orig` (
  `cluster_id` int NOT NULL,
  `entity_guid` bigint NOT NULL,
  `entity_owner_guid` bigint DEFAULT NULL,
  `score` float(5,2) DEFAULT NULL,
  `first_engaged` timestamp NULL DEFAULT NULL,
  `last_engaged` timestamp NULL DEFAULT NULL,
  `last_updated` timestamp NULL DEFAULT NULL,
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `total_views` bigint DEFAULT NULL,
  `total_engagement` bigint DEFAULT NULL,
  PRIMARY KEY (`cluster_id`,`entity_guid`),
  KEY `score` (`score`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `supermind_refunds` (
  `supermind_request_guid` bigint NOT NULL,
  `tx_id` varchar(32) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`supermind_request_guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `superminds` (
  `guid` bigint NOT NULL,
  `activity_guid` bigint DEFAULT NULL,
  `reply_activity_guid` bigint DEFAULT NULL,
  `sender_guid` bigint DEFAULT NULL,
  `receiver_guid` bigint DEFAULT NULL,
  `status` int DEFAULT NULL,
  `payment_amount` float(7,2) DEFAULT NULL,
  `payment_method` int DEFAULT NULL,
  `payment_reference` text,
  `created_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_timestamp` timestamp NULL DEFAULT NULL,
  `twitter_required` tinyint(1) DEFAULT NULL,
  `reply_type` int DEFAULT NULL,
  PRIMARY KEY (`guid`),
  KEY `sender_guid` (`sender_guid`,`status`),
  KEY `receiver_guid` (`receiver_guid`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_configurations` (
  `user_guid` bigint NOT NULL,
  `terms_accepted_at` timestamp NULL DEFAULT NULL,
  `supermind_cash_min` float(7,2) DEFAULT NULL,
  `supermind_offchain_tokens_min` float(7,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
